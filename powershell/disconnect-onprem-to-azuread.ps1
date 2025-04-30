Install-Module AzureAD
Import-Module AzureAD
Connect-AzureAD

$emails = @(
"test@contoso.com",
"email@contoso.com"
)
$TokenSet = [Char[]]'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'

$enabled = $emails | ? { (Get-AzureADUser -ObjectId $_).AccountEnabled -eq $True }
$disabled = $emails | ? { (Get-AzureADUser -objectid $_).AccountEnabled -eq $False }

$disabled | % { Set-AzureADUser -ObjectId $_ -ImmutableId (
	[Convert]::ToBase64String((Get-Random -Count 36 -InputObject $TokenSet))
) }
