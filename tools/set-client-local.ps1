$scriptPath = Join-Path $PSScriptRoot "patch-nrlink3.ps1"
powershell -ExecutionPolicy Bypass -File $scriptPath -Hosts "127.0.0.1","127.0.0.1","127.0.0.1"

