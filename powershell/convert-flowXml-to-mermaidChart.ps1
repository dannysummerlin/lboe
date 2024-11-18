$types = @(
    @{ name = "actionCalls"; open = "[/"; close = "/]"; color = ""; },
    @{ name = "decisions"; open = "{{"; close = "}}"; color = ""; },
    @{ name = "assignments"; open = "[["; close = "]]"; color = ""; },
    @{ name = "recordLookups"; open = "[("; close = ")]"; color = ""; },
    @{ name = "screens"; open = "["; close = "]"; color = ""; },
    @{  name = "loops"; open = "(("; close = "))"; color = ""; },
    @{ name = "waits"; open = "{"; close = "}"; color = ""; }
)

ForEach($f in $(gci *.flow)) {
    $content = (select-xml -Path $f -XPath '/*').Node
    $flow = $conent
    $o = ForEach ($t in $types) {
        ForEach($s in $content.$($t.name)) {
            echo ($s.name + $t.open + '"`' + $s.label + '`"' + $t.close + "`n")
            "$($s.name) --> $($s.connector.targetReference ?? $s.defaultConnector.targetReference ?? $s.nextValueConnector.targetReference)`n"
            $s.noMoreValuesConnector ? (echo "$($s.name) --> $($s.noMoreValuesConnector.targetReference)`n") : $null
            $s.rules | % { $_ ? (echo "$($s.name) --> $($_.connector.targetReference)`n") : $null }
            $s.waitEvents | % { $_ ? (echo "$($s.name) --> $($_.connector.targetReference)`n") : $null }
        }
    }
# output to Markdown
@"
# Description

Type: $($content.processType)
$($content.description)

``````mermaid
flowchart
$o
``````
"@ | out-file -filePath "$($f.name -replace "_", " ").md"
}
