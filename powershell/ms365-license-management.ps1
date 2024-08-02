# built from https://blog.darrenjrobinson.com/microsoft-graph-using-msal-with-powershell/
Import-Module MSAL.PS

# ===================================================================================================
# Library Functions
Function AuthN {
	[cmdletbinding()]
	param(
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][string]$tenantID,
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][System.Management.Automation.PSCredential]$credential
	)
	if (!(get-command Get-MsalToken)) {
		Install-Module -name MSAL.PS -Force -AcceptLicense
	}
	try {
		$token = Get-MsalToken -ClientId $credential.UserName -ClientSecret $credential.Password -TenantId $tenantID  
		return $token
	} catch {
		Write-Error $_
		return $false
	}
}

Function Get-M365Content {
	[cmdletbinding()]
	param(
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][string]$url,
		[Parameter(Mandatory = $true)]$token,
		[Parameter(Mandatory = $false)][string]$method = 'Get'
	)
	$webRequestMethod = [Microsoft.PowerShell.Commands.WebRequestMethod]::$method
	$returnData = @()
	try {
		$m365Response = Invoke-RestMethod -Headers @{Authorization = "Bearer $($token.AccessToken)" } `
			-Uri $url `
			-Method $webRequestMethod
		$returnData += $m365Response.value
		if($m365Response.'@odata.nextLink') {
			$returnData += Get-M365Content -url $m365Response.'@odata.nextLink' -token $token
		}
		return $returnData
	} catch {
		Write-Error $_
		return $false
	}
}

Function Get-M365UserActivity {
	[cmdletbinding()]
	param(
		[Parameter(Mandatory = $false, ValueFromPipeline = $true)]][string]$days = "90",
		[Parameter(Mandatory = $true)]$token
	)
	$url = "https://graph.microsoft.com/beta/reports/getM365AppUserDetail(period='D$($days)')/content?`$format=application/json&`$top=4000"
	try {
		$data = Get-M365Content -token $token -url $url
		return $data
	} catch {
		Write-Error "Error getting user activity report"
		return $false
	}
}

Function Get-M365EstablishedUsers {
	[cmdletbinding()] Param(
		[Parameter(Mandatory = $true)]$token,
		[Parameter(Mandatory = $false, ValueFromPipeline = $true)]][string]$days = "90",
	)
	$daysAgo = (Get-Date).AddDays(-1 * [int]($days))
	$year = [String]$daysAgo.Year
	$month = ([String]$daysAgo.month).PadLeft(2,"0")
	$url = "https://graph.microsoft.com/beta/users?`$filter=createdDateTime+le+$year-$month-01T00:00:00Z&`$select=userPrincipalName&`$format=application/json&`$top=999"
	try {
		Write-Output "Querying $url"
		$data = Get-M365Content -token $token -url $url
		return $data.userPrincipalName
	} catch {
		Write-Error "Error getting users with accounts greater than $($days) days old"
		return $false
	}
}

Function Connect-Tenant {
	[cmdletbinding()]
	param(
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][System.Management.Automation.PSCredential]$UserCredential
	)
	Write-Output "Connecting to Office 365"
	$Session = New-PSSession -ConfigurationName Microsoft.Exchange -ConnectionUri "https://outlook.office365.com/powershell-liveid/" -Credential $UserCredential -Authentication Basic -AllowRedirection
	Connect-MsolService -Credential $UserCredential
	Import-Module (Import-PSSession $Session -DisableNameChecking:$true -AllowClobber:$true) -Global
}
# ===================================================================================================

# Example use:
# Update-Licenses -tenantID "xxxx-xxxx-xxxx-xxxx" -credential ([pscredential]::New($ApplicationId, ($SecretKey | ConvertTo-SecureString -AsPlainText -Force)))

Function Delicense-InactiveUsers {
	[cmdletbinding()]
	param(
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][string]$tenantID,
		[Parameter(Mandatory = $true, ValueFromPipeline = $true)][System.Management.Automation.PSCredential]$credential,
		[Parameter(Mandatory = $true)][string]$license,
		[string]$adGroup, # if licensing is done by group rather than per user
		[integer]$reportDays = 90
	)

	$token = AuthN -credential $credential -tenantID $tenantID
	try {
		Write-Output "Getting account details for verification"
		$accountsOlderThanNDays = Get-M365EstablishedUsers -token $token -days $reportDays
		Write-Output "Getting user activity"
		$m365UserActivityData = Get-M365UserActivity -token $token -days $reportDays
		Write-Output "Found $($m365UserActivityData.Count) active users to check"
		Connect-Tenant
		Write-Output "Getting unlicensed users to skip"
		$unlicensedUsers = Get-MsolUser -All -UnlicensedUsersOnly | Select -ExpandProperty userPrincipalName
	    $usersToDelicense = $m365UserActivityData | ? { $_.lastActivityDate -in $null, '' -and $_.userPrincipalName -notin $unlicensedUsers -and $_.userPrincipalName -in $accountsOlderThanNDays }
	    $usersToVerify = $m365UserActivityData | ? { $_.lastActivityDate -notin $null, '' -and $_.userPrincipalName -notin $unlicensedUsers -and $_.userPrincipalName -in $accountsOlderThanNDays }
		forEach($u in $usersToVerify) {
			$activityCheck = $u.details | % { $_.PSObject.Properties.Value } | ? { $_ -eq $true }
			if(!$activityCheck) {
				$usersToDelicense += $u
			}
		}
		Write-Output "Verified accounts for $($usersToDelicense.Count) users with no activity, delicensing"
		forEach($u in $usersToDelicense) {
			$upn = $u.userPrincipalName
			Write-Output "Deactivating $upn $(if($u.lastActivityDate -notin @($null,'')) { 'last activity: ' + $u.lastActivityDate } else { '' })"
			try{
				Set-Mailbox $upn -Type Shared # to preserve forwarding
				Set-MsolUserLicense -UserPrincipalName $upn -RemoveLicenses $license -ErrorAction Stop
			} catch {
				if($_.FullyQualifiedErrorId -like '*InvalidOperationOnGroupInheritedLicenseException*' -and $adGroup) {
					write-Output "Removing $upn from Active Directory Group"
					try {
						Get-AdUser -filter {userPrincipalName -eq $upn} | % { Remove-ADGroupMember $adGroup -Members $_.distinguishedName -Confirm:$false }
					} catch {
						Write-Error "Could not remove user $upn from $($adGroup): "
						Write-Error $_
					}
				} else {
					write-error $_
				}
			}
		}
	} catch {
		Write-Error $_
	}
}
