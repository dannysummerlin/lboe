Sub ReplaceTextWithMergeField()
    On Error GoTo ErrHandler

    Dim Document As Document
    Set Document = ActiveDocument
    Dim search_string As String
    search_string = InputBox("Enter the text you want to replace:")
    If search_string = "" Then
        MsgBox "No text entered. Exiting."
        Exit Sub
    End If
    ' Collect all available merge fields
    Dim data_source As MailMergeDataSource
    Set data_source = Document.MailMerge.dataSource
    If data_source Is Nothing Then
        MsgBox "No mail merge data source found. Please connect a data source first.", vbCritical
        Exit Sub
    End If
    Dim field_names As String
    Dim i As Integer
    For i = 1 To data_source.DataFields.Count
        field_names = field_names & data_source.DataFields.Item(i).Name & vbCrLf
    Next i
    Dim field_name As String
    field_name = InputBox("Available merge fields:" & vbCrLf & field_names & vbCrLf & _
                         "Enter the name of the field to insert:")
    If field_name = "" Then
        MsgBox "No field name entered. Exiting."
        Exit Sub
    End If
    ' Check if entered field exists
    Dim fieldExists As Boolean
    fieldExists = False
    For i = 1 To data_source.DataFields.Count
        If StrComp(data_source.DataFields.Item(i).Name, field_name, vbTextCompare) = 0 Then
            fieldExists = True
            Exit For
        End If
    Next i
    If Not fieldExists Then
        MsgBox "Field name '" & field_name & "' not found in merge fields.", vbExclamation
        Exit Sub
    End If

    Dim page As page
    Dim shape As shape
    Dim text_range As TextRange
    Dim start_position As Integer
    For Each page In Document.Pages
        ActiveDocument.ActiveView.ActivePage = page
        For Each shape In page.Shapes
            If shape.HasTextFrame Then
                Set text_range = shape.TextFrame.TextRange
                Do
                    start_position = InStr(1, text_range.Text, search_string, vbTextCompare)
                    If start_position > 0 Then
                        text_range.Characters(start_position).Select
                        text_range.Characters(start_position, Len(search_string)).Delete
                        text_range.Characters(start_position).InsertMailMergeField (field_name)
                    End If
                Loop While start_position > 0
            End If
        Next shape
    Next page
    MsgBox "Replacement complete!", vbInformation
    Exit Sub

ErrHandler:
    Debug.Print Err.Description
    Stop
    Resume
End Sub
