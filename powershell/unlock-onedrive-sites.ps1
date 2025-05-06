Import-Module Microsoft.Online.SharePoint.PowerShell -UseWindowsPowerShell
Connect-SPOService -Url https://contoso-admin.sharepoint.com

@("https://CONTOSO-my.sharepoint.com/personal/user_contoso_com") | % {
	# make sure OneDrive is not locked
	Set-SPOSite -Identity $_ -LockState Unlock
	# delete OneDrive if desired
	# Remove-SPOSite -Identity $_ -NoWait -Confirm:$false
}
