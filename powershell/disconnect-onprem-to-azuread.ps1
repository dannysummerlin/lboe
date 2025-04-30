Install-Module AzureAD
Import-Module AzureAD
Connect-AzureAD

$emails = @(
"test@contoso.com",
"email@contoso.com"
)
$TokenSet = [Char[]]'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
$syncExcludedLocation = "OU=Excluded from Sync,DC=contoso,dc=com" # an OU or container in AD to move accounts to

$enabled = $emails | ? { (Get-AzureADUser -ObjectId $_).AccountEnabled -eq $True }
$disabled = $emails | ? { (Get-AzureADUser -objectid $_).AccountEnabled -eq $False }

# save Guids to save from recycling bin
$disabled_guids = $disabled | % { (Get-AzureADUser -ObjectId $_).ObjectId }
# set dummy value on ImmutableId
$disabled | % { Set-AzureADUser -ObjectId $_ -ImmutableId (
	[Convert]::ToBase64String((Get-Random -Count 36 -InputObject $TokenSet))
) }

# move accounts in AD
$disabled | % { Get-ADuser -filter "userprincipalname -eq '$_'" | Move-ADObject -TargetPath $syncExcludedLocation }
# force AD-Azure sync
Start-ADSyncSyncCycle -PolicyType Delta
Start-Sleep -Seconds 60
# restore accounts
$disabled_guid | % { Restore-AzureADMSDeletedDirectoryObject -Id $_ }
