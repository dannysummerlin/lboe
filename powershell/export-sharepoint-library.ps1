# Parameters required in .env file
# =======================================
# ClientID=xxxxx-xxxxx-xxxx-xxx-xxxxxxxxxx
# Tenant=xxxxx-xxxxx-xxxx-xxx-xxxxxxxxxx
# ThumbPrint=xxxxxxxxxxxxxxxxxxxxxxx
# SiteURL=https://xxxxxxx.sharepoint.com/

Get-Content .env | foreach {
    $name, $value = $_.split('=')
    Set-Content env:\$name $value
}
$ListName= "Documents"
$Pagesize = 5000

#Connect to SharePoint Online site
Connect-PnPOnline -Url $ENV:SiteURL -ClientId $ENV:ClientID -Thumbprint $ENV:ThumbPrint -Tenant $ENV:Tenant
$global:totalCounter = 0
$global:fileCounter = 0

#Get all Documents from the document library
$List  = Get-PnPList -Identity $ListName
Write-host "Connected, beginning"
Get-PnPListItem -List $ListName -PageSize $Pagesize -Fields Author, Editor, Created, File_x0020_Type, File_x0020_Size -ScriptBlock  {
        Param($rawItems)

        $Results = New-Object System.Collections.ArrayList
        $items = $rawItems | Where {$_.FileSystemObjectType -eq "File"}
        Write-Progress -PercentComplete (($global:totalCounter / $List.ItemCount) * 100) -Activity "Getting Documents from Library" -CurrentOperation "Getting partial List"
        $global:totalCounter += $items.Count
        $itemCounter = 0
        Foreach ($Item in $items) {
                echo 't'
                Write-Progress -PercentComplete (($itemCounter / $items.Count) * 100) -Activity "Getting Documents from Library" -CurrentOperation "Processing list items"
                $o = New-Object PSObject -Property ([ordered]@{
                        Name              = $Item["FileLeafRef"]
                        FileType          = $Item["File_x0020_Type"]
                        FileSize          = $Item["File_x0020_Size"]
                        RelativeURL       = $Item["FileRef"]
                        CreatedByEmail    = $Item["Author"].LookupValue
                        CreatedOn         = $Item["Created"]
                        Modified          = $Item["Modified"]
                        ModifiedByEmail   = $Item["Editor"].LookupValue
                })
                $Results.Add($o) > $null
                $itemCounter++ > $null
        }
        #Export the results to CSV
        $global:fileCounter++ > $null
        $Results | Export-Csv -Path "2 List From $($global:fileCounter).csv" -NoTypeInformation
        # may need to rate limit
        Start-Sleep -Seconds 2
} > $null

Write-host "Document Library Inventory Exported to CSVs Successfully!"
