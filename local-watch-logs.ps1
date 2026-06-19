param(
    [int]$Last = 50
)

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$OutLog = Join-Path $Root "logs\server-local.out.log"
$ErrLog = Join-Path $Root "logs\server-local.err.log"

if (-not (Test-Path $OutLog)) {
    Write-Host "No server log yet. Start the server first."
    exit 0
}

Write-Host "Tailing server logs. Press Ctrl+C to stop."
Write-Host ""

Get-Content -Path $OutLog -Tail $Last -Wait
