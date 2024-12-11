param (
	$username
	$adminUsername
	$adminPassword
	$orgName
)

if(!$username) { throw "-username is required." }

try {
	$user = Get-AdUser -Identity $username
	$user | Disable-AdAccount
	$user | Set-AdUser -Replace @{msExchHideFromAddressLists=$true}
	$email = ($user | select-object mail).mail
	if($email -ne "") {
		$password = $adminPassword | ConvertTo-Securestring
		$UserCredential = New-Object -typename System.Management.Automation.PSCredential -argumentlist $adminPassword,$password
# this won't work anymore because Basic is disabled, tofix
		$Session = New-PSSession -ConfigurationName Microsoft.Exchange -ConnectionUri "https://outlook.office365.com/powershell-liveid/" -Credential $UserCredential -Authentication Basic -AllowRedirection
		Connect-MsolService -Credential $UserCredential
		Import-PSSession $Session
	
		Set-Mailbox $email -Type shared
		echo ((get-mailbox $email | Select-object recipienttypedetails).RecipientTypeDetails -eq "SharedMailbox")
		if((get-mailbox $email | Select-object recipienttypedetails).RecipientTypeDetails -eq "SharedMailbox") {
			# remove all licenses, add other options here
			@("O365_BUSINESS_ESSENTIALS","MCOPSTNC","MCOMEETADV","ENTERPRISEPREMIUM") | % {
				Set-MsolUserLicense -UserPrincipalName $email -RemoveLicenses "$orgName:$_" -EA SilentlyContinue
			}
		}
		Remove-PSSession $Session
	} else {
		echo "$username not found"
	}
} catch {
	echo $_
}
