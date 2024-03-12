# Taken from https://community.jumpcloud.com/t5/community-scripts/starting-with-jumpcloud-with-organizational-best-practices/m-p/836
# Example config.json file
{
#     "jc_api_key" : "YOUR_JUMPCLOUD_API_KEY",
#     "teams" : [
#         "All-Company",
#         "Marketing",
#         "Operations",
#         "Finance",
#         "Sales",
#         "Eng-Product",
#         "Eng-BackEnd",
#         "Eng-FrontEnd",
#         "Eng-DevOps"
#     ],
#     "user_group_suffix" : "_Users",
#     "system_group_suffix" : "_Devices",
#     "policy_group_suffix" : "_Policies",
#     "backup_format" : "json",
#     "backup_path" : "~/Dev/JC-BestPractice/Backups"
# }
##BEGIN CONFIG VARIABLES
$config_file	= "config.json"	# Path to $config_file, note that the config file is in the JSON format
$backup_before	= $false	 # Mark this as $true if you want to backup before changes, note that backup output is always enabled
$create_groups	= $true	# Mark this as $true if you want the script to create groups based on $config_file
$backup_after	= $false	 # Mark this as $true if you want to backup after changes, note that backup output is always enabled
$print_summary	= $true	# Mark this as $true if you want to print the summary
##END CONFIG VARIABLES

## TODO check for and install JumpCloud module

# Test if $config_file exists
if ( -not ( Test-Path $config_file ) ) {
	Write-Host ""
	Write-Host "ERROR: Can't find the file '$config_file'"
	Write-Host ""
	Exit
}

# Variables
$ProgressPreference = "silentlyContinue"
$start_time = $(get-date)
$config = Get-Content $config_file | ConvertFrom-Json
$headers = @{}
$headers.Add("x-api-key", $config.jc_api_key)
$headers.Add("content-type", "application/json")
$results = New-Object System.Collections.ArrayList
$backup_before_changes = New-Object System.Object
$backup_after_changes = New-Object System.Object

# Connect to JumpCloud
Connect-JCOnline $config.jc_api_key -force
$jc_org = Get-JCOrganization
$backup_path = Join-Path -Path $config.backup_path -ChildPath $jc_org.OrgID

# Get existing User Grocups
$current_user_groups = Get-JCGroup -Type User
$current_user_groups = $current_user_groups | Group-Object -AsHashtable -Property name

# Get existing Device (aka System) Groups
$current_system_groups = Get-JCGroup -Type System
$current_system_groups = $current_system_groups | Group-Object -AsHashtable -Property name

# Get existing Policy Groups
$current_policy_groups = Invoke-RestMethod -Uri 'https://console.jumpcloud.com/api/v2/policygroups' -Method GET -Headers $headers -SkipHeaderValidation
$current_policy_groups = $current_policy_groups | Group-Object -AsHashtable -Property name

# Make sure Backup Path exists, otherwise create it
if ( $backup_before -or $backup_after ) {
	if ( -not (Test-Path $backup_path) ) {
		New-Item $backup_path -ItemType Directory | Out-Null
	}
}

# Backup before making changes to JC organization
if ( $backup_before ) {
	Write-Host ""
	$backup_before_changes = Backup-JCOrganization -Path:($backup_path) -All -Format:($config.backup_format) -PassThru
	Write-Host ""
}

## Create new User, Device (aka System), and Policy Groups
if ( $create_groups ) {
	foreach ( $team in $config.teams ) {
		# Create User Group
		$user_group_name = ($team + $config.user_group_suffix)
		if ( ( $null -eq $user_group_name) -or ( -not $current_user_groups.ContainsKey($user_group_name) ) ) {
			$res = New-JCUserGroup -GroupName $user_group_name
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "User"
			$res
			$results.Add($res) | Out-Null
		}
		else {
			$res = New-Object System.Object
			$res | Add-Member -MemberType NoteProperty -Name "Name" -Value $user_group_name
			$res | Add-Member -MemberType NoteProperty -Name "id" -Value $current_user_groups[$user_group_name].id
			$res | Add-Member -MemberType NoteProperty -Name "Result" -Value "Exists-Skipped"
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "User"
			$res
			$results.Add($res) | Out-Null
		}

		# Create Device (aka System) Group
		$system_group_name = ($team + $config.system_group_suffix)
		if ( ( $null -eq $current_system_groups) -or ( -not $current_system_groups.ContainsKey($system_group_name) ) ) {
			$res = New-JCSystemGroup -GroupName $system_group_name
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "Device"
			$res
			$results.Add($res) | Out-Null
		}
		else {
			$res = New-Object System.Object
			$res | Add-Member -MemberType NoteProperty -Name "Name" -Value $system_group_name
			$res | Add-Member -MemberType NoteProperty -Name "id" -Value $current_system_groups[$system_group_name].id
			$res | Add-Member -MemberType NoteProperty -Name "Result" -Value "Exists-Skipped"
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "Device"
			$res
			$results.Add($res) | Out-Null
		}

		# Create Policy Group
		$policy_group_name = ($team + $config.policy_group_suffix)
		if ( ( $null -eq $current_policy_groups) -or ( -not $current_policy_groups.ContainsKey($policy_group_name) ) ) {
			$body=@{}
			$body.Add("name", $policy_group_name)
			$body = $body | ConvertTo-Json
			$response = Invoke-RestMethod -Uri 'https://console.jumpcloud.com/api/v2/policygroups' -Method POST -Headers $headers -ContentType 'application/json' -Body $body
			$res = New-Object System.Object
			$res | Add-Member -MemberType NoteProperty -Name "Name" -Value $response.name
			$res | Add-Member -MemberType NoteProperty -Name "id" -Value $response.id
			$res | Add-Member -MemberType NoteProperty -Name "Result" -Value "Created"
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "Policy"
			$res
			$results.Add($res) | Out-Null
		}
		else {
			$res = New-Object System.Object
			$res | Add-Member -MemberType NoteProperty -Name "Name" -Value $policy_group_name
			$res | Add-Member -MemberType NoteProperty -Name "id" -Value $current_policy_groups[$policy_group_name].id
			$res | Add-Member -MemberType NoteProperty -Name "Result" -Value "Exists-Skipped"
			$res | Add-Member -MemberType NoteProperty -Name "GroupType" -Value "Policy"
			$res
			$results.Add($res) | Out-Null
		}
	}
}


# Backup after making changes to JC organization
if ( $backup_after ) {
	Write-Host ""
	$backup_after_changes = Backup-JCOrganization -Path:($backup_path) -All -Format:($config.backup_format) -PassThru
	Write-Host ""
}

# Print Summary
if ( $print_summary ) {
	$elapsed_time = $(get-date) - $start_time
	$total_time = "{0:HH:mm:ss}" -f ([datetime]$elapsed_time.Ticks)
	$number_of_groups_created = $results.get_Count()
	Write-Host ""
	Write-Host ("Execution Summary for JumpCloud OrgID: " + $jc_org.OrgID)
	Write-Host "* Start Time: $start_time"
	Write-Host "* Total runtime: $total_time"
	Write-Host "* Number of Groups Created: $number_of_groups_created"
	if ( $backup_before ) { Write-Host ("* Backup before changes file name: " + $backup_before_changes.BackupLocation.FullName + ".zip") }
	if ( $backup_after )	 { Write-Host ("* Backup after changes file name:	 " + $backup_after_changes.BackupLocation.FullName + ".zip") }
	Write-Host ""
}
