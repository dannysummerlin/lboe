<#
LastPass license management, simple call to their API to get list of inactive users
#>

function Call-LastPassAPI {
	[CmdletBinding()]
	Param(
		[Parameter(Mandatory = $true)][System.Management.Automation.PSCredential]$credential,
		[Parameter(Mandatory = $true)]$cmd,
		$data
	)
	$post = @{
		cid = $credential.UserName
		apiuser='PHP_API'
		provhash = $credential.Password
		cmd = $cmd
		data = $data
	}
	try {
		$response = Invoke-WebRequest -Uri 'https://lastpass.com/enterpriseapi.php' -Body (ConvertTo-JSON $post) -Method 'POST' -Headers @{'Content-Type' = 'application/json'}
		return ($response.Content | ConvertFrom-Json)
	} catch {
		Write-Output $_
		return @{ error = $_ }
	}
}

Function Delicense-InactiveLastPassUsers {
	[CmdletBinding()]
	Param(
		[Parameter(Mandatory = $true)][string]$username,
		[[Parameter(Mandatory = $true)]][string]$password,
		[int]$daysAgo = 90
	)
	Write-Output 'Updating LastPass licensing'
	$credentials = ([pscredential]::New($username, ($password | ConvertTo-SecureString -AsPlainText -Force)))
	$activeUsers = Call-LastPassAPI -credential $credentials -cmd 'getuserdata' -data @{disabled = 0; admin = 0}
	$activeUsers = $activeUsers.Users.PSObject.Properties | %{ $_.value }
	$checkDate = (Get-Date).AddDays(-1 * $daysAgo)
	$usersToDisable = $activeUsers | ? { (Get-Date -Date $_.created) -le $checkDate -and ($_.last_login -eq '' -or (Get-Date -Date $_.last_login) -le $checkDate) }
	$usersToDisable | % {
		$null = Call-LastPassAPI -credential $credentials -cmd 'deluser' -data @{ deleteaction = 0; username = $_ }
		$null = Call-LastPassAPI -credential $credentials -cmd 'updateuser' -data @{ disabled = $true; username = $_ }
	}
}
