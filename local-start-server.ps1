param(
    [switch]$SkipBuild,
    [switch]$ResetDatabase
)

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$LogDir = Join-Path $Root "logs"
$OutLog = Join-Path $LogDir "server-local.out.log"
$ErrLog = Join-Path $LogDir "server-local.err.log"

New-Item -ItemType Directory -Force -Path $LogDir | Out-Null

$args = @(
    "-NoProfile",
    "-ExecutionPolicy",
    "Bypass",
    "-File",
    (Join-Path $Root "local-run.ps1")
)

if ($SkipBuild) {
    $args += "-SkipBuild"
}
if ($ResetDatabase) {
    $args += "-ResetDatabase"
}

$process = Start-Process -FilePath "powershell.exe" -ArgumentList $args -WorkingDirectory $Root -WindowStyle Hidden -RedirectStandardOutput $OutLog -RedirectStandardError $ErrLog -PassThru

Write-Host "Started local server process $($process.Id)."
Write-Host "stdout: $OutLog"
Write-Host "stderr: $ErrLog"
