Function ConvertTo-SharedMailbox {
	[CmdletBinding()]
	param(
		[Parameter(Mandatory = $true)][string]$username,
		[Parameter(Mandatory = $true)][string]$password,
		[Parameter(Mandatory = $true)][string]$emailAddress
	)

	Import-Module ExchangeOnlineManagement
	Connect-ExchangeOnline -Credential (New-Object -typename System.Management.Automation.PSCredential -argumentlist $username,(ConvertTo-SecureString -String $password -AsPlainText -Force))
	Write-Output (Set-Mailbox -Identity $emailAddress -Type Shared)
}
