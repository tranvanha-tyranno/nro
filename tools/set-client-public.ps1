$scriptPath = Join-Path $PSScriptRoot "patch-nrlink3.ps1"
powershell -ExecutionPolicy Bypass -File $scriptPath -Hosts "nro.luminostech.tech","nro.luminostech.tech","nro.luminostech.tech"

