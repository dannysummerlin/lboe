'=================================================================
'based on work by Robert Sparnaaij
'=================================================================
Public Sub TimeSpentReportManual()
	Dim objOL As Outlook.Application
	Dim objSelection As Outlook.Selection
	Dim objItem As Object
	Dim Duration As Long
	Dim TotalWork As Long
	Dim Mileage As Long
	Dim Result As Integer
	Dim ShowMileage As Boolean

	'Change to True if you also want to calculate
	'the total mileage in the report.
	ShowMileage = False
	Duration = 0
	TotalWork = 0
	Mileage = 0

	On Error Resume Next

	Set objOL = Outlook.Application
	Set objSelection = objOL.ActiveExplorer.Selection

	For Each objItem In objSelection
		If objItem.Class = olAppointment Then
			Duration = Duration + objItem.Duration
			Mileage = Mileage + objItem.Mileage
		ElseIf objItem.Class = olTask Then
			Duration = Duration + objItem.ActualWork
			TotalWork = TotalWork + objItem.TotalWork
			Mileage = Mileage + objItem.Mileage
		ElseIf objItem.Class = Outlook.olJournal Then
			Duration = Duration + objItem.Duration
			Mileage = Mileage + objItem.Mileage
		Else
			Result = MsgBox("No Calendar, Task or Journal item selected.", vbCritical, "Time Spent")
			Exit Sub
		End If
	Next

	'Building the message box text
	Dim MsgBoxText As String
	MsgBoxText = "Total time spent on the selected items; " & vbNewLine & Duration & " minutes"
	If Duration > 60 Then
		MsgBoxText = MsgBoxText & HoursMinsMsg(Duration)
	End If
	If TotalWork > 0 Then
		MsgBoxText = MsgBoxText & vbNewLine & vbNewLine & "Total work recorded for the selected Tasks; " & vbNewLine & TotalWork & " minutes"
		If TotalWork > 60 Then
			MsgBoxText = MsgBoxText & HoursMinsMsg(TotalWork)
		End If
	End If
	If ShowMileage = True Then
		MsgBoxText = MsgBoxText & vbNewLine & vbNewLine & "Total mileage; " & Mileage
	End If

	Result = MsgBox(MsgBoxText, vbInformation, "Time spent")

ExitSub:
	Set objItem = Nothing
	Set objSelection = Nothing
	Set objOL = Nothing
End Sub

Public Function HoursMinsMsg(TotalMinutes As Long) As String
		Dim Hours As Long
		Dim Minutes As Long
		Hours = TotalMinutes \ 60
		Minutes = TotalMinutes Mod 60
		HoursMinsMsg = " (" & Hours & " hours and " & Minutes & " minutes)"
End Function

Function AddItem(arr, val)
	ReDim Preserve arr(UBound(arr) + 1)
	arr(UBound(arr)) = val
	AddItem = arr
End Function

Function DateYWkWd(year, wNumber, wDay)
	Jan4 = DateSerial(year, 1, 4)
	DateYWkWd = DateAdd("ww", wNumber - 1, DateAdd("d", wDay - weekDay(Jan4, 2) - 1, Jan4))
End Function

Public Sub TimeSpentReport()
	Dim objOL As Outlook.Application
	Dim oNS As Outlook.NameSpace
	Dim objSelection As Outlook.Selection
	Dim calendarItems As Outlook.Items
	Dim filteredItems As Outlook.Items
	Dim objItem As Object
	Dim Duration As Long
	Dim TotalWork As Long
	Dim Mileage As Long
	Dim Result As Integer
	Dim ShowMileage As Boolean
	Dim ListOfCategories()
	Dim WeekStart
	'Change to True if you also want to calculate
	'the total mileage in the report.
	ShowMileage = False

	ListOfCategories = Array()
	WeekStart = ""
	Duration = 0
	TotalWork = 0
	Mileage = 0
	
	On Error Resume Next

	Set objOL = Outlook.Application
	Set objSelection = objOL.ActiveExplorer.Selection
	For Each objItem In objSelection
		If objItem.Class = olAppointment Then
			If WeekStart = "" Then WeekStart = DateYWkWd(DatePart("yyyy", objItem.Start), DatePart("ww", objItem.Start, vbMonday, vbFirstFourDays), 1)
			ListComparison = Filter(ListOfCategories, objItem.Categories)
			If UBound(ListComparison) = -1 Then ListOfCategories = AddItem(ListOfCategories, objItem.Categories)
		Else
			Result = MsgBox("No Calendar selected.", vbCritical, "Time Spent")
			Exit Sub
		End If
	Next

	' get all with Categories
	Set olNS = objOL.GetNamespace("MAPI")
	' Set calendarItems = olNS.GetDefaultFolder(olFolderCalendar).Items
	Set filteredItems = olNS.GetDefaultFolder(olFolderCalendar).Items.Restrict("[Start] > '" & WeekStart & "' and [Start] < '" & DateAdd("d", 7, WeekStart) & "' and [End] < '" & DateAdd("d", 7, WeekStart) & "'")

	For Each objItem In filteredItems
		If objItem.Class = olAppointment And objItem.BusyStatus <> olOutOfOffice Then
			If UBound(Filter(ListOfCategories, objItem.Categories)) > -1 Then
'                e = objItem.GetRecurrencePattern().Exceptions.Count
'               If e > 0 Then
'                  eDate = CDate(objItem.GetRecurrencePattern().Exceptions.Item(e).OriginalDate)
'                 If eDate > WeekStart And eDate < DateAdd("d", 7, WeekStart) Then
'                    Temp = MsgBox(objItem.Subject & " - " & objItem.GetRecurrencePattern().Exceptions.Item(e).Class, vbInformation, "test")
'                   GoTo NextStep
'              End If
'         End If
				Duration = Duration + objItem.Duration
				If ShowMileage Then Mileage = Mileage + objItem.Mileage
			End If
		End If
	Next

	Dim MsgBoxText As String
	MsgBoxText = "Total time spent on the selected items; " & vbNewLine & Duration & " minutes"
	If Duration > 60 Then
		MsgBoxText = MsgBoxText & HoursMinsMsg(Duration)
	End If
	If TotalWork > 0 Then
		MsgBoxText = MsgBoxText & vbNewLine & vbNewLine & "Total work recorded for the selected Tasks; " & vbNewLine & TotalWork & " minutes"
		
		If TotalWork > 60 Then
			MsgBoxText = MsgBoxText & HoursMinsMsg(TotalWork)
		End If
	End If
	If ShowMileage = True Then
		MsgBoxText = MsgBoxText & vbNewLine & vbNewLine & "Total mileage; " & Mileage
	End If
	Result = MsgBox(MsgBoxText, vbInformation, "Time spent")

ExitSub:
	Set objItem = Nothing
	Set objSelection = Nothing
	Set objOL = Nothing
End Sub
