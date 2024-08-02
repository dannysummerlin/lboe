<#
built from https://blog.darrenjrobinson.com/microsoft-graph-using-msal-with-powershell/ + others
required modules
Install-Module PnP.PowerShell
Install-Module MSAL.PS
#>
if(!(Get-InstalledModule -Name MSAL.PS)) { Install-Module MSAL.PS -confirm:$false }
if(!(Get-InstalledModule -Name ExchangeOnlineManagement)) { Install-Module ExchangeOnlineManagement -confirm:$false }
if(!(Get-InstalledModule -Name PnP.PowerShell)) { Install-Module -Name PnP.PowerShell -confirm:$false -force }

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

function Invoke-WithRetry {
	[CmdletBinding()]
	Param(
		[Parameter(Position=0, Mandatory=$true)][scriptblock]$scriptBlock,
		[Parameter(Position=1, Mandatory=$false)][int]$maxCount = 3,
		[Parameter(Position=2, Mandatory=$false)][int]$delay = 10
	)
	Begin { $tryCounter = 0 }
	Process {
		do {
			$tryCounter++
			try {
				Invoke-Command -Command $scriptBlock
				return
			} catch {
				Write-Error $_.Exception.InnerException.Message -ErrorAction Continue
				Start-Sleep -Milliseconds $delay
			}
		} while ($tryCounter -lt $maxCount)
		throw "Exceeded retry count"
	}
}
# ===================================================================================================

Function Archvie-OneDrive {
	[CmdletBinding()]
	param(
		[Parameter(Mandatory = $true)][string]$username,
		[Parameter(Mandatory = $true)][string]$password,
		[Parameter(Mandatory = $true)][string]$subdomain,
		[Parameter(Mandatory = $true)][string]$sourceUsername, # "demo.data@example.com"
		[Parameter(Mandatory = $true)][string]$targetSite = "Backup",
		[Parameter(Mandatory = $true)][string]$sourceLibraryName = "Documents",
		[string]$targetLibraryName
	)
 	Import
	$urledSourceUsername = $sourceUsername -replace "@","_" -replace "\.(\w{2,3})$",'_$1'
	if($targetLibraryName -eq '') { $targetLibraryName = $urledSourceUsername }

	$sourceConnection = Connect-PnPOnline -Url "https://$($subdomain)-my.sharepoint.com/personal/$urledSourceUsername" -Interactive -ReturnConnection
	$sourceLibrary = Get-PnPList -Identity "Documents" -Connection $sourceConnection
	$targetConnection = Connect-PnPOnline -Url $targetSite -Interactive -ReturnConnection
	$targetLibrary = Get-PnPList -Identity $targetLibraryName -Connection $targetConnection
	$targetRootFolder = Get-PnPProperty -ClientObject $targetLibrary -Property RootFolder

	(Get-PnPListItem -List $sourceLibrary -Connection $sourceConnection  | Where { $_.FileSystemObjectType -eq "Folder"}) | % {
		$sourceFolderSiteRelativePath = $_.FieldValues.FileRef.Replace($sourceLibrary.RootFolder.ServerRelativeUrl, [string]::Empty)
		$targetFolderSiteRelativePath = Join-Path $targetRootFolder.Name $sourceFolderSiteRelativePath
		Write-Debug "Ensuring Folder at $targetFolderSiteRelativePath" -ForegroundColor Green
		Resolve-PnPFolder -SiteRelativePath $targetFolderSiteRelativePath | Out-Null
	}

	$sourceFiles = Get-PnPListItem -List $sourceLibrary -Connection $sourceConnection | Where { $_.FileSystemObjectType -eq "File"}
	$totalCounter = 1
	$totalFiles = $sourceFiles.Count
	ForEach ($File in $sourceFiles) {
		Write-Progress -PercentComplete ($totalCounter/$totalFiles*100) -Activity "Copying Files" -Status "Copying File $($file.FieldValues.FileRef) ($totalCounter of $totalFiles)..."
		$localFileCopy = Get-PnPFile -Url $file.FieldValues.FileRef -Connection $sourceConnection -AsFile -Path $Env:TEMP -Filename $File.FieldValues.FileLeafRef -Force
		$localFilePath = Join-Path $Env:TEMP $File.FieldValues.FileLeafRef
		$sourceFileSiteRelativePath = $File.FieldValues.FileDirRef.Replace($sourceLibrary.RootFolder.ServerRelativeUrl, [string]::Empty)
		$targetFolderSiteRelativePath = Join-Path $targetRootFolder.Name $sourceFileSiteRelativePath
		Invoke-WithRetry -scriptBlock {
			Add-PnPFile -Path $localFilePath -Folder $targetFolderSiteRelativePath -Connection $targetConnection -Values @{Modified=$File.FieldValues.Modified; Created = $File.FieldValues.Created} | Out-null
			$totalCounter++
			Remove-Item $localFilePath
			Write-Debug "Copied File from $($File.FieldValues.FileRef)" -f Green
		}
	}
}


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

Function ConvertTo-SharedMailbox {
	[CmdletBinding()]
	param(
		[Parameter(Mandatory = $true)][string]$username,
		[Parameter(Mandatory = $true)][string]$password,
		[Parameter(Mandatory = $true)][string]$emailAddress
	)

	Import-Module ExchangeOnlineManagement
	Invoke-WithRetry -scriptBlock {
		Connect-ExchangeOnline -Credential (New-Object -typename System.Management.Automation.PSCredential -argumentlist $username,(ConvertTo-SecureString -String $password -AsPlainText -Force))
		Write-Output (Set-Mailbox -Identity $emailAddress -Type Shared)
	}
}

# Used with New-AdUser or Get-AdUser cmdlets
Function Link-MailboxToADUser {
	[CmdletBinding()]
	param(
		[Parameter(Mandatory = $true)][string]$username,
		[Parameter(Mandatory = $true)][string]$password,
		[Parameter(Mandatory = $true)][ADUser]$adUser,
		[Parameter(Mandatory = $true)][string]$sharedInboxAddress
	)

	Import-Module ExchangeOnlineManagement
	Invoke-WithRetry {
		Connect-ExchangeOnline -Credential (New-Object -typename System.Management.Automation.PSCredential -argumentlist $username,(ConvertTo-SecureString -String $password -AsPlainText -Force))
		Set-MsolUserPrincipalName $sharedInboxAddress -ImmutableId ([System.Convert]::ToBase64String($adUser.objectGuid.ToByteArray()))
	}
}
<#
# Notes for later - Azure App assignment manager
for($role in $roleNames) {
	$appRole = $ServicePrincipal.AppRoles | Where-Object { $_.DisplayName -eq $role }
	$appAssignments = Get-AzureADServiceAppRoleAssignment -ObjectId $ServicePrincipal.ObjectId
	$groupName = $role.Replace(" ","")
	$members = Get-ADGroupMember $groupName -Recursive
	for($member in $members) {
		$adUser = Get-ADUser $member
		$user = Get-AzureADUser -ObjectId $adUser.userPrincipalName
		$assignment = $appAssignments | Where {$_.PrincipalDisplayName -eq $user.DisplayName}
		if(!$assignment) {
			New-AzureADUserAppRoleAssignment -ObjectId $user.ObjectId -PrincipalId $user.ObjectId -ResourceId $ServicePrincipal.ObjectId -Id $appRole.Id
		} else {
			$appAssignments = $appAssignments | Where-Object { $_.ObjectId –ne $assignment.ObjectId }
		}
	}
}

for($a in $appAssignments) {
	Remove-AzureADServiceAppRoleAssignment -ObjectId $ServicePrincipal.ObjectId -AppRoleAssignmentId $a.ObjectId
}
#>
