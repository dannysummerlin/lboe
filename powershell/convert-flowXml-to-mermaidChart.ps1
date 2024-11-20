$types = @(
    @{ name = "actionCalls"; open = "{{"; close = "}}"; style = "fill:#dac4f7"; },
    @{ name = "subflows"; open = "{{"; close = "}}"; style = "fill:#d3c0f9"; },
    @{ name = "decisions"; open = "{"; close = "}"; style = "fill:#ebd2b4"; },
    @{ name = "assignments"; open = ">"; close = "]"; style = "fill:#d6f6dd"; },
    @{ name = "recordLookups"; open = "[/"; close = "\]"; style = "fill:#acecf7"; },
    @{ name = "recordUpdates"; open = "[\"; close = "/]"; style = "fill:#acecf7"; },
    @{ name = "recordCreates"; open = "[/"; close = "/]"; style = "fill:#acecf7"; },
    @{ name = "screens"; open = "["; close = "]"; style = "fill:#f4989c"; },
    @{  name = "loops"; open = "((("; close = ")))"; style = "fill:#f4989c"; },
    @{ name = "waits"; open = "{"; close = "}"; style = "fill:#f4989c"; }
)

$styles = "classDef default color:#000`n"
ForEach ($t in $types) {
    $styles += "classDef $($t.name) $($t.style)`n"
}


ForEach($f in $(gci *.flow)) {
    $content = (select-xml -Path $f -XPath '/*').Node
    $flow = $conent
    $o = ForEach ($t in $types) {
        ForEach($s in $content.$($t.name)) {
            echo ($s.name + $t.open + '"`' + $s.label + '`"' + $t.close + ":::$($t.name)`n")
            $defaultConnector = $s.connector.targetReference ?? $s.defaultConnector.targetReference ?? $s.nextValueConnector.targetReference
            $defaultConnector ? (echo "$($s.name) ---> $defaultConnector`n") : $null
            $s.noMoreValuesConnector ? (echo "$($s.name) ---> $($s.noMoreValuesConnector.targetReference)`n") : $null
            $s.rules | % { $_ ? (echo "$($s.name) -- $($_.label) ---> $($_.connector.targetReference)`n") : $null }
            $s.waitEvents | % { $_ ? (echo "$($s.name) ---> $($_.connector.targetReference)`n") : $null }
        }
    }
# output to Markdown
@"
# Description

Type: $($content.processType)
$($content.description)

``````mermaid
flowchart
$styles
$o
``````
"@ | out-file -filePath "$($f.name -replace "_", " ").md"
}
