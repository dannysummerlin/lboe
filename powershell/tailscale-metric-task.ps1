$cmd = 'Start-Sleep 10; Set-NetIPInterface -InterfaceAlias Tailscale -InterfaceMetric 1000'
$action = New-ScheduledTaskAction -Execute powershell.exe -Argument "-NoProfile -WindowStyle Hidden -Command `"$cmd`""

$class = Get-CimClass -ClassName MSFT_TaskEventTrigger -Namespace Root/Microsoft/Windows/TaskScheduler
$onNetwork = New-CimInstance -CimClass $class -ClientOnly
$onNetwork.Enabled = $true
$onNetwork.Subscription = '<QueryList><Query Id="0" Path="Microsoft-Windows-NetworkProfile/Operational"><Select Path="Microsoft-Windows-NetworkProfile/Operational">*[System[EventID=10000]]</Select></Query></QueryList>'

$onStartup = New-ScheduledTaskTrigger -AtStartup
$principal = New-ScheduledTaskPrincipal -UserId SYSTEM -RunLevel Highest
Register-ScheduledTask -TaskName "Tailscale Metric" -Action $action -Trigger $onStartup,$onNetwork -Principal $principal -Force
