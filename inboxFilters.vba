Sub ChangeView(viewName As String)
    Dim objViews As Views
    Dim objView As View
    Set objViews = Application.ActiveExplorer.CurrentFolder.Views
    Set objView = objViews.Item(viewName)
    objView.Apply
End Sub
Public Sub viewJumpstart()
    ChangeView ("Jumpstart")
End Sub
Public Sub viewTNB()
    ChangeView ("TNB")
End Sub
Public Sub viewBase()
    ChangeView ("Base View")
End Sub
Public Sub viewYouthBuild()
    ChangeView ("YouthBuild")
End Sub
Public Sub viewTheDiabetesLink()
    ChangeView ("The Diabetes Link")
End Sub
Public Sub viewMountAuburnCemetary()
    ChangeView ("Mount Auburn Cemetary")
End Sub
Public Sub viewJusticeAtWork()
    ChangeView ("Justice at Work")
End Sub
