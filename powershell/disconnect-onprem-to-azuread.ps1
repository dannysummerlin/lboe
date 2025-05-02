Import-Module MSOnline
Connect-MSOLService

$emails = @(
"test@contoso.com",
"email@contoso.com"
)
$syncExcludedLocation = "OU=Excluded from Sync,DC=contoso,dc=com" # an OU or container in AD to move accounts to

$all_users=(Get-MSOLuser -All:$true -EnabledFilter EnabledOnly)
$all_emails = New-Object System.Collections.ArrayList
$all_users | % { [void]$all_emails.Add($_.UserPrincipalName.ToLower()) }
$enabled = New-Object System.Collections.ArrayList
$emails | ? { $_.ToLower() -in $all_emails } | % { [void]$enabled.Add($_.ToLower()) }
$disabled = New-Object System.Collections.ArrayList
$emails | ? { $_.ToLower() -notin $all_emails } | % { [void]$disabled.Add($_.ToLower()) }

# remove immutableID
$disabled | % { Set-MSOLUser -UserPrincipalName $_ -ImmutableId "$null" }

# move accounts in AD
$disabled | % { Get-ADuser -filter "userprincipalname -eq '$_'" | Move-ADObject -TargetPath $syncExcludedLocation }
# force AD-Azure sync
Start-ADSyncSyncCycle -PolicyType Delta
Start-Sleep -Seconds 60
# restore accounts - hopefully no longer necessary
# $disabled_guid | % { Restore-AzureADMSDeletedDirectoryObject -Id $_ }
