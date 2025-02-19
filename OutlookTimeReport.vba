'=================================================================
'Description: Outlook macro which allows you to calculate the
'         total amount of time spent on the selected Calendar,
'         Task and Journal items.
'
'author : Robert Sparnaaij
'version: 1.0
'website: https://www.howto-outlook.com/howto/timespent.htm
'=================================================================

'Limitation; This code does not work for recurring meeting items
'in a List view (like All Appointments) since recurring items
'are only listed once.
'To work with recurring items and for full reporting features,
'you can use a reporting add-in;
'https://www.howto-outlook.com/tag/reporting

'==================================================================

Public Sub TimeSpentReportManual()
    Dim Result
    Result = runTimeSpentReportManual()
End Sub
Function runTimeSpentReportManual()
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
            Exit Function
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

    Set objItem = Nothing
    Set objSelection = Nothing
    Set objOL = Nothing
End Function

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
    DateYWkWd = DateAdd("ww", wNumber - 1, DateAdd("d", wDay - Weekday(Jan4, 2) - 1, Jan4))
End Function

Public Sub TimeSpentReport()
    Dim Result
    Result = runTimeSpentReport(False)
End Sub
Public Sub TimeSpentReportMonth()
    Dim Result
    Result = runTimeSpentReport(True)
End Sub

Function runTimeSpentReport(monthMode As Boolean)
    Dim objOL As Outlook.Application
    Dim oNS As Outlook.NameSpace
    Dim objSelection As Outlook.Selection
    Dim calendarItems As Outlook.Items
    Dim filteredItems As Outlook.Items
    Dim objItem As Object
    Dim Duration As Long
    Dim SanityCount As Long
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
    SanityCount = 0
    TotalWork = 0
    Mileage = 0
    
    On Error Resume Next

    Set objOL = Outlook.Application
    Set objSelection = objOL.ActiveExplorer.Selection

    For Each objItem In objSelection
        If objItem.Class = olAppointment Then
            If WeekStart = "" Then WeekStart = DateYWkWd(DatePart("yyyy", objItem.Start), DatePart("ww", objItem.Start, vbMonday), 1)
            ListComparison = Filter(ListOfCategories, objItem.Categories)
            If UBound(ListComparison) = -1 Then ListOfCategories = AddItem(ListOfCategories, objItem.Categories)
        Else
            Result = MsgBox("No Calendar selected.", vbCritical, "Time Spent")
            Exit Function
        End If
    Next

    ' get all with Categories
    Set olNS = objOL.GetNamespace("MAPI")
    Set calendarItems = olNS.GetDefaultFolder(olFolderCalendar).Items
    calendarItems.Sort "[Start]"
    calendarItems.IncludeRecurrences = True
    If monthMode Then
        monthStartDate = DateSerial(year(WeekStart), Month(WeekStart), 1)
        monthEndDate = DateAdd("m", 1, DateSerial(year(WeekStart), Month(WeekStart), 1))
        Set objItem = calendarItems.Find("[Start] > '" & monthStartDate & "' and [Start] < '" & monthEndDate & "' and [End] < '" & monthEndDate & "'")
    Else
        Set objItem = calendarItems.Find("[Start] > '" & WeekStart & "' and [Start] < '" & DateAdd("d", 7, WeekStart) & "' and [End] < '" & DateAdd("d", 7, WeekStart) & "'")
    End If
    
    While TypeName(objItem) <> "Nothing" And SanityCount < 100
        SanityCount = SanityCount + 1
        If objItem.Class = olAppointment And objItem.BusyStatus <> olOutOfOffice Then
            If objItem.Categories <> "" And UBound(Filter(ListOfCategories, objItem.Categories)) > -1 Then
                Duration = Duration + objItem.Duration
                If ShowMileage Then Mileage = Mileage + objItem.Mileage
            End If
        End If
        Set objItem = calendarItems.FindNext
    Wend

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
    Set objItem = Nothing
    Set objSelection = Nothing
    Set objOL = Nothing
End Function
