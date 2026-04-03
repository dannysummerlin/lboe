#!/bin/bash

# Required parameters:
# @raycast.schemaVersion 1
# @raycast.title Ask Claude
# @raycast.mode fullOutput

# Optional parameters:
# @raycast.icon 🛠
# @raycast.argument1 { "type": "text", "placeholder": "Enter text" }

claude -p "//btw $1"
