# Icons: https://iconarchive.com/show/mini-icons-by-famfamfam.html
Add-Type -AssemblyName PresentationCore,PresentationFramework | Out-Null
[void][Reflection.Assembly]::LoadWithPartialName('Microsoft.VisualBasic')

# questionable - should change credentials strategy
function Get-CredentialFromFile {
[CmdletBinding()]Param(
	[string]$userName,
	[string]$secretFile,
	[switch]$plain
)
	try {
		$Secret = Get-Content $secretFile
	} catch { Write-Error "Unable to open the secrets file: $_";exit }
	try {
		if($plain) { $decodedPassword = $Secret }
		else {
			$decodedPassword = [System.Text.Encoding]::Unicode.GetString([System.Convert]::FromBase64String($Secret))
		}
		$password = ConvertTo-SecureString -String $decodedPassword -AsPlainText -Force
		$UserCredential = New-Object -typename System.Management.Automation.PSCredential -argumentlist $username,$password
		return $UserCredential
	} catch { Write-Error $_;exit }
}

$global:connection = $null
$global:path = $actionPath
$global:MSOnlineBlock = {
	Import-Module MSOnline
	$global:rawPassword # bad
	[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
	$password = ConvertTo-SecureString -String $rawPassword -AsPlainText -Force
	$UserCredential = New-Object -typename System.Management.Automation.PSCredential -argumentlist $adminUsername,$password
	Write-Host "Connecting to Microsoft Online"
	$null = Connect-MsolService -Credential $UserCredential
	$null = Connect-ExchangeOnline -Credential $UserCredential
}
$global:ADNestedGroupsBlock = {
	Function Get-ADNestedGroups {
	Param([string]$distinguishedName, [array]$Groups = @())
		$ADObject = Get-ADObject -Filter {DistinguishedName -eq $distinguishedName} -Properties memberOf, DistinguishedName, Name
		if($ADObject) {
			Foreach($groupDistinguishedName in $ADObject.memberOf) {
				$currentGroup = Get-ADObject -Filter {DistinguishedName -eq $groupDistinguishedName} -Properties memberOf, DistinguishedName, Name
				if(($Groups | Where-Object {$_.DistinguishedName -eq $groupDistinguishedName}).Count -eq 0) {
					$Groups +=  $currentGroup
					$Groups = Get-ADNestedGroups -DistinguishedName $groupDistinguishedName -Groups $Groups
				}
			}
		}
		Return $Groups
	}
}

function Run-Cmd {
[CmdletBinding()]Param(
	[Parameter(Mandatory,ValueFromPipeline)]$cmd,
	[Parameter(Mandatory)]$domainControllerName,
	[Parameter(Mandatory)]$adminUsername,
)
	if(!$global:connection) {
		try {
			# FIX
			$Credential = Get-CredentialFromFile -userName $adminUsername -secretFile "secretfile.txt"
			$global:connection = New-PSSession -ComputerName $domainControllerName -Credential $Credential
		} catch {
			Show-Prompt -error -msg $_.Exception.Message
			exit
		}
	}
	return (Invoke-Command -Session $global:connection -ScriptBlock $cmd)
}

function Reset-Password {
[CmdletBinding()]Param(
	[string]$email,
	[string]$username
)
	$confirm = Show-Prompt -msg "Reset password for $($username)?" -OKCancel -warningFlag
	if($confirm) {
		$user = Run-Cmd -cmd { Get-ADUser $Using:username }
		if($user) {
			$newPassword = GENREATE-PASSWORD FIX
			Run-Cmd -cmd { Get-ADUser $Using:username | Set-ADAccountPassword -Reset -NewPassword (ConvertTo-SecureString -AsPlainText $Using:newPassword -Force) }
			Set-Clipboard -Value $newPassword
			Show-Prompt -msg "$username password has been reset to: " -ask -default $newPassword
		} else { Show-Prompt -msg "Unable to find a user called $username" -errorFlag }
	}
}

function Add-UserGroup {
[CmdletBinding()]Param([string]$username)
	Add-ToGroup -identity $username
}

function Add-ToGroup {
[CmdletBinding()]Param(
	[string]$identity,
	[string]$display
)
	if($display -eq $null -or $display -eq "") { $display = $identity }
	$groupName = Show-Prompt -ask -msg "Search for the group to add $display to:" -title 'Add to Group'
	if($groupName -ne $null -and $groupName -ne "") {
		$adGroup = (Run-Cmd -cmd { Get-ADGroup -filter "name -like '*$Using:groupName*'" | Select Name } | Out-GridView -OutputMode 'Single' -Title 'Please select the group and click OK').Name
		if($adGroup) {
			try {
				$completed = Run-Cmd -cmd { 
					try { Add-ADGroupMember -Identity $Using:adGroup -Members $Using:identity; return $true }
					catch { Write-Host $_; return $false }
				}
				if($completed) { $null = Show-Prompt -msg "$display has been added to $adGroup" }
				else { $null = Show-Prompt -msg "ERROR: There was a problem adding $display to $adGroup" -errorFlag }
			} catch { $null = Show-Prompt -msg "ERROR: There was a problem adding $display to $adGroup" -errorFlag }
		} else { $null = Show-Prompt -msg "Group $adGroup not found" }
	}
}

function Remove-FromGroup {
[CmdletBinding()]Param([string]$identity, [string]$display)
	if($display -eq $null -or $display -eq "") { $display = $identity }
	$adObject = Run-Cmd -cmd { Get-ADObject -filter "samaccountname -like '$Using:identity*'" -Properties samaccountname,MemberOf | ? { $_.samaccountname -match ($Using:identity + "?") }}
	if($adObject) {
		$adGroup = ($adObject.MemberOf | Out-GridView -OutputMode 'Single' -Title 'Please select the group to remove and click OK')
		if($adGroup) {
			$adGroupName = $adGroup -replace "CN=","" -replace ",OU=.*",""
			try {
				$confirm = Show-Prompt -msg "Are you sure you want to remove $display from $adGroupName`?" -OKCancel -warningFlag
				if($confirm) {
					$completed = Run-Cmd -cmd {
						try { Remove-ADGroupMember -Identity $Using:adGroup -Members $Using:identity -Confirm:$false; return $true }
						catch { Write-Host $_; return $false }
					}
					if($completed) { $null = Show-Prompt -msg "$display has been removed from $adGroupName" }
					else { $null = Show-Prompt -msg "ERROR: There was a problem removing $display from $adGroupName" -errorFlag }
				}
			} catch {
				$null = Show-Prompt -msg "ERROR: There was a problem removing $display from $adGroupName" -errorFlag
			}
		} else {
			# $null = Show-Prompt -msg "Information for $adGroup not found"
		}
	} else {
		$null = Show-Prompt -msg "Information for $display not found"
	}
}

function Get-ADObjectGroups {
[CmdletBinding()]Param([string]$identity, [string]$mode)
	$display = $identity
	[ScriptBlock]$ScriptBlockInput = {
		try {
			$adObject = Get-ADObject -filter "samaccountname -like '$($Using:identity)'" -Properties DistinguishedName,samaccountname,MemberOf | ? { $_.samaccountname -match ($Using:identity + "?") }
			if($Using:mode -eq 'direct') {
				return $adObject.MemberOf
			} else {
				$Groups = Get-ADNestedGroups -DistinguishedName (Get-ADUser -Identity $adObject).DistinguishedName
				return $Groups
			}
		} catch {
			return $_.Exception.Message
		}
	}
	$ScriptBlockInput = [ScriptBlock]::Create($global:ADNestedGroupsBlock.ToString() + "`n" + $ScriptBlockInput.ToString())
	$result = Run-Cmd -cmd $ScriptBlockInput
	if($result -is [array]) {
		$title = 'Direct Group Memberships'
		if($mode -eq 'all') {
			$title = 'All Group Memberships (including nested memberships)'
			$result = ($result | Select-Object Name | Sort-Object -Property Name)
		}
		$selection = $result | Out-GridView -OutputMode Multiple -Title $title
		Set-Clipboard $selection
	} else {
		$null = Show-Prompt -msg "ERROR: There was a problem getting memberships for $display - `n $result" -errorFlag
	}
}

function Add-CalendarPermission {
[CmdletBinding()]Param([string]$identity)
	# get shareToUser
	$shareToUser = Show-Prompt -ask -title "Calendar Sharing for $identity" -msg "Enter the email address of the person to share with:"
	$baseDomain = $identity.split('@')[1]
	# check that email was set right
	if($shareToUser -ne $null -and $shareToUser -ne "") {
		if($shareToUser -like "*@$baseDomain") {
			# get accessRights
			$accessRights = Show-Prompt -ask -title "Calendar Sharing for $identity" -msg "Choose the level of access for this share (enter the number matching the access level):`n1. Reviewer - Read only`n2. Editor - Allows read, create, modify and delete items`n3. LimitedDetails - Allows view of the subject and location"
			if($accessRights -in 1,2,3) {
				$accessLevel = @("Reviewer", "Editor", "LimitedDetails")[$accessRights-1]
				[ScriptBlock]$ScriptBlockInput = {
					try {
						$ErrorActionPreference = 'Stop'
						Add-MailboxFolderPermission -Identity "$($Using:identity):\calendar" -User $Using:shareToUser -AccessRights $Using:accessLevel
						return "success"
					} catch {
						return $_.Exception.Message
					}
				}
				$ScriptBlockInput = [ScriptBlock]::Create($global:MSOnlineBlock.ToString() + "`n" + $ScriptBlockInput.ToString())
				$result = Run-Cmd -cmd $ScriptBlockInput
				if($result -eq 'success') {
					$null = Show-Prompt -title "Calendar Sharing for $identity" -msg "Calendar sharing from $identity to $shareToUser updated"
				} else {
					$null = Show-Prompt -title "Error for $identity" -msg "There was an error sharing from $identity to $shareToUser - `n`n$result"
				}
			} else {
				$null = Show-Prompt -title "Calendar Sharing for $identity" -msg "You must enter a 1, 2, or 3 to continue"
			}
		} else {
			$null = Show-Prompt -title "Calendar Sharing for $identity" -msg "You must enter a $baseDomain email address to share with"
		}
	}
}

function Get-CalendarPermission {
[CmdletBinding()]Param([string]$identity)
	[ScriptBlock]$ScriptBlockInput = {
		$perms = 'none'
		try {
			$ErrorActionPreference = 'Stop'
			$perms = Get-MailboxFolderPermission -Identity "$($Using:identity):\calendar"
			return $perms
		} catch { return $_.Exception.Message }
	}
	$ScriptBlockInput = [ScriptBlock]::Create($global:MSOnlineBlock.ToString() + "`n" + $ScriptBlockInput.ToString())
	$result = Run-Cmd -cmd $ScriptBlockInput
	if($result -ne 'none') {
		$result | Out-GridView -OutputMode Multiple -Title "Calendar Sharing for $identity"
	} else { $null = Show-Prompt -title "Error for $identity" -msg "There was an error getting calendar permissions for $identity - `n`n$result" }
}

function Enable-User {
[CmdletBinding()]Param(
	[string]$email,
	[string]$username
)
	$confirm = Show-Prompt -msg "Enable account for $username`?" -OKCancel -warningFlag
	if($confirm) {
		$user = Run-Cmd -cmd { Get-ADUser $Using:username }
		if($user) {
			$newPassword = GENREATE-PASSWORD FIX
			Run-Cmd -cmd {
				$user = Get-ADUser $Using:username -Properties Description,mail
				$user | Set-ADAccountPassword -Reset -NewPassword (ConvertTo-SecureString -AsPlainText $Using:newPassword -Force)
				$user | Enable-ADAccount
			}
			Set-Clipboard -Value $newPassword
			Show-Prompt -msg "$username has been enabled and their password has been reset to: " -ask -default $newPassword
		} else { Show-Prompt -msg "Unable to find a user called $username" -errorFlag }
	}
}

function Update-User {
[CmdletBinding()]Param(
	[string]$username,
	[string]$attribute
)
	$value = $null
	@($username, $attribute)
	if($attribute -ne $null) {
		$value = Show-Prompt -msg "Update $($attribute) for $($username) with:" -ask -title 'Set Value'
		if($value) {
			$user = Run-Cmd -cmd { Get-ADUser $Using:username }
			if($user) {
				Run-Cmd -cmd {
					$user = Get-ADUser $Using:username
					$attribs = New-Object HashTable
					$attribs.Add($Using:attribute, $Using:value)
					$user | Set-ADUser -replace $attribs
				}
			}
		}
	} else { Show-Prompt -msg 'You must specify an attribute and a value to update' }
}

Function Deactivate-User {
[CmdletBinding()]Param(
	[string]$email,
	[string]$username
)
	$confirm = Show-Prompt -msg "Deactivate account for $($username)?" -OKCancel -warningFlag
	if($confirm) {
		$user = Run-Cmd -cmd { Get-ADUser $Using:username }
		$UserPrincipalName = $user.UserPrincipalName
		if($user) {
			# disable account in AD and reset password
			$newPassword = GENREATE-PASSWORD FIX
			Run-Cmd -cmd {
				$user = Get-ADUser $Using:username -Properties Description,mail
				$user | Set-ADAccountPassword -Reset -NewPassword (ConvertTo-SecureString -AsPlainText $Using:newPassword -Force)
				$user | Disable-ADAccount
			}
			Write-Host "Active Directory stage 1: ${$result}"

			# turn on forward
			$emailUpdateBlock = ""
			$targetEmail = Show-Prompt -msg "If you would like to forward the email account, enter the target address below (or else leave it blank):" -ask -title 'Set Forward Email?'
			if($targetEmail -ne '') {
				[ScriptBlock]$emailUpdateBlock = {
					try {
						Set-Mailbox -Identity $Using:email -ForwardingAddress $Using:targetEmail
						Write-Host "Office 365 step 1: success forwarded"
					} catch { Write-Error ("Office 365 step 1: ERROR: " + $_.Exception.Message) }
				}
			}
			# convert to shared mailbox and remove license
			[ScriptBlock]$conversionBlock = {
				try {
					Set-Mailbox -Identity $Using:UserPrincipalName -Type Shared
					$MsolUser = Get-MsolUser -UserPrincipalName $Using:UserPrincipalName
					$AssignedLicenses = $MsolUser.licenses.AccountSkuId
					foreach($License in $AssignedLicenses) {
						Set-MsolUserLicense -UserPrincipalName $Using:UserPrincipalName -RemoveLicenses $License
					}
					Write-Host "Office 365 step 2: success: conversion"
				} catch { Write-Error ("Office 365 step 2: ERROR: " + $_.Exception.Message) }
			}
			$UserCredential = Get-CredentialFromFile -userName $adminUsername -secretFile FIX
			$ScriptBlockInput = [ScriptBlock]::Create($global:MSOnlineBlock.ToString() + "`n" + $emailUpdateBlock.ToString() + "`n" + $conversionBlock.ToString())
			$result = Run-Cmd -cmd $ScriptBlockInput
			# remove groups
			Run-Cmd -cmd {
				try {
					Write-Host ("Active Directory step 2: getting groups for " + $Using:user.UserPrincipalName)
					$user = Get-ADUser $Using:user.SamAccountName -Properties Description,mail,MemberOf
					Write-Host "Active Directory step 2: removing groups"
					if ($user.MemberOf -ne $null) {
						foreach($g in $user.MemberOf) {
							Remove-ADGroupMember -Identity $g -Members $Using:user.SamAccountName -Confirm:$false
						}
					}
					Write-Host "Active Directory step 2: success removed groups"
				} catch { Write-Error ("Active Directory step 2: ERROR: " + $_.Exception.Message) }
			}
			Set-Clipboard -Value $newPassword
			Show-Prompt -msg "$username has been disabled, converted to shared, and their password has been reset to: " -ask -default $newPassword -Title 'User Deactivated'
		} else { Show-Prompt -msg "Unable to find a user called $username" -errorFlag }
	}
}

Function Set-MFAforUser {
[CmdletBinding()]Param(
	[string]$identity,
	[string]$type
)
# credit to https://raw.githubusercontent.com/ruudmens/LazyAdmin/master/Office365/MFAEnableForUser.ps1
	$confirm = Show-Prompt -msg "Are you sure you want to ${type} MFA for ${identity}? They may be required to re-login to services, so be sure to communicate with them first" -OKCancel -warningFlag
	if ($confirm -and $identity -ne $null -and @('Disabled','Enabled', 'Enforced') -contains $type) {
		[ScriptBlock]$ScriptBlockInput = {
			try {
				$user = Get-ADUser $Using:identity
				$upn = $user.UserPrincipalName
				Import-Module MSOnline
				$sa = New-Object -TypeName Microsoft.Online.Administration.StrongAuthenticationRequirement
				$sa.RelyingParty = "*"
				$sa.State = $Using:type
				$sar = @($sa)
				Set-MsolUser -UserPrincipalName $upn -StrongAuthenticationRequirements $sar
				return "success"
			} catch {
				return $_.Exception.Message
			}
		}
		$UserCredential = Get-CredentialFromFile -userName $adminUsername -secretFile "secret.txt"
		$ScriptBlockInput = [ScriptBlock]::Create($global:MSOnlineBlock.ToString() + "`n" + $ScriptBlockInput.ToString())
		$result = Run-Cmd -cmd $ScriptBlockInput
		if($result -eq 'success') {
			$null = Show-Prompt -title "MFA Status for $identity" -msg "MFA Status for $identity has been set to $type"
		} else {
			$null = Show-Prompt -title "Error for $identity" -msg "There was an error setting MFA Status for $identity to $type - `n`n$result" -errorFlag
		}
	} elseif($confirm -ne $false) {
		$null = Show-Prompt -msg "No identity was provided to update, parameters were `nIdentity: '$identity'`nMFA type: '$type'" -errorFlag
	}
}

function Show-Prompt {
[CmdletBinding()]Param(
	$msg,
	[string]$title,
	[switch]$ask,
	[switch]$okCancel,
	[switch]$errorFlag,
	[switch]$questionFlag,
	[switch]$warningFlag,
	[string]$default
)
	$image = "None"
	if(@($null,'') -contains $title) { $title = 'User Management' }
	$buttonType = "Ok"
	if($okCancel) { $buttonType = "OKCancel" }
	if($errorFlag) { $image = "Error"}
	if($questionFlag) { $image = "Question"}
	if($warningFlag) { $image = "Warning"}
	if($ask) {
		$response = [Microsoft.VisualBasic.Interaction]::InputBox($msg, $title, $default)
	} else {
		$response = [System.Windows.MessageBox]::Show($msg, $title, $buttonType, $image)
	}
	switch ($response) {
		'OK' { return $true; break }
		'Cancel' { return $false; break }
		default { return $response }
	}
}
