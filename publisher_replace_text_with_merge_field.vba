' based on Cindy Meister's work at https://stackoverflow.com/questions/50122503/replace-text-with-matching-mail-merge-field
Sub find_replace_text_to_merge_field()
    Dim merge_field_name As String
    Dim search_string As String
    Dim mm As Publisher.MailMergeDataField
    Dim field_list As String
    Dim counter As Long

    If ActiveDocument.MailMerge.Type <> pbMergeDefault Then
        search_string = InputBox("Enter the text to find", "Find and Replace Text to Merge Field")
        For Each mm In ActiveDocument.MailMerge.DataSource.DataFields
            field_list = field_list & vbNewLine & mm.Name
        Next
        merge_field_name = InputBox("Enter the merge field name to use. Field names are:" & field_list, "Enter Merge Field Name")
        If Not merge_field_name Is Nothing Then
            Dim found As Boolean
            Do While replace_text_with_merge_field(search_string, merge_field_name)
                counter = counter + 1
            Loop
            Debug.Print counter & " merge fields inserted for " & merge_field_name
        Else
            MsgBox ("You must enter a merge field name to proceed")
        End If
    Else
        MsgBox ("You can only run this command on documents that are mail-merge enabled")
    End If
End Sub

Function replace_text_with_merge_field(search_string As String, merge_field_name As String) As Boolean
    Dim found as Boolean
    Dim finder As FindReplace
    Set finder = ActiveDocument.Find
    Set found = True
    With finder
        .ClearFormatting
        .Forward = True
        .wrap = wdFindStop
        .Format = False
        .MatchCase = False
        .MatchWholeWord = True ' maybe false?
        .MatchWildcards = False
        .MatchSoundsLike = False
        .MatchAllWordForms = False
        .ReplaceWithText = ""
        .ReplaceScope = pbReplaceScopeOne
        .FindText = search_string
        Do While found = True
            found = .Execute
            If Not .FoundTextRange Is Nothing Then
                .FoundTextRange.Font.Bold = True
            End If
        Loop
    End With
     '     Do While .Execute = True
    '         .FoundTextRange.Fields.Add(range, wdFieldMergeField, merge_field_name, False)
    '         found = True
    '     Loop
    ' End With
    return found
End Function
