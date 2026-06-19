param(
    [switch]$SkipBuild,
    [switch]$ResetDatabase
)

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path

function Resolve-JavaHome {
    $candidates = @()
    if ($env:JAVA_HOME) {
        $candidates += $env:JAVA_HOME
    }
    $candidates += "C:\Program Files\Eclipse Adoptium\jdk-17.0.19.10-hotspot"

    if (Test-Path "C:\Program Files\Eclipse Adoptium") {
        $candidates += Get-ChildItem "C:\Program Files\Eclipse Adoptium" -Directory |
            Sort-Object LastWriteTime -Descending |
            ForEach-Object { $_.FullName }
    }

    foreach ($candidate in $candidates) {
        if ($candidate -and (Test-Path (Join-Path $candidate "bin\java.exe"))) {
            return $candidate
        }
    }

    throw "JDK 17 was not found. Install Eclipse Temurin JDK 17 or set JAVA_HOME."
}

Push-Location $Root
try {
    $dbArgs = @()
    if ($ResetDatabase) {
        $dbArgs += "-ResetDatabase"
    }
    & (Join-Path $Root "local-start-db.ps1") @dbArgs

    if (-not $SkipBuild) {
        & (Join-Path $Root "local-build.ps1") "-Clean"
    }

    $JavaHome = Resolve-JavaHome
    $Java = Join-Path $JavaHome "bin\java.exe"
    $ClassesDir = Join-Path $Root "build\classes"
    $libs = Get-ChildItem -Path (Join-Path $Root "lib") -Filter "*.jar" |
        Sort-Object FullName |
        ForEach-Object { $_.FullName }
    $classpath = (@($ClassesDir) + $libs) -join [System.IO.Path]::PathSeparator

    Write-Host "Starting NgocRongOnline local server on 127.0.0.1:14445..."
    & $Java "-server" "-Dfile.encoding=UTF-8" "-cp" $classpath "nro.models.server.ServerManager"
}
finally {
    Pop-Location
}
