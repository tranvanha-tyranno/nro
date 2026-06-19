$ErrorActionPreference = "Stop"

$connections = Get-NetTCPConnection -LocalPort 14445 -State Listen -ErrorAction SilentlyContinue
if (-not $connections) {
    Write-Host "No process is listening on port 14445."
    exit 0
}

$processIds = $connections | Select-Object -ExpandProperty OwningProcess -Unique
foreach ($processId in $processIds) {
    $process = Get-Process -Id $processId -ErrorAction SilentlyContinue
    if ($process) {
        Write-Host "Stopping process $processId ($($process.ProcessName)) listening on port 14445..."
        Stop-Process -Id $processId -Force
    }
}
