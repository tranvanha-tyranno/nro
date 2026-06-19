param(
    [switch]$Clean
)

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$BuildDir = Join-Path $Root "build"
$ClassesDir = Join-Path $BuildDir "classes"
$SourceList = Join-Path $BuildDir "local-sources.txt"

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
        if ($candidate -and (Test-Path (Join-Path $candidate "bin\javac.exe"))) {
            return $candidate
        }
    }

    throw "JDK 17 was not found. Install Eclipse Temurin JDK 17 or set JAVA_HOME."
}

$JavaHome = Resolve-JavaHome
$Javac = Join-Path $JavaHome "bin\javac.exe"

if ($Clean -and (Test-Path $ClassesDir)) {
    Remove-Item -LiteralPath $ClassesDir -Recurse -Force
}

New-Item -ItemType Directory -Force -Path $ClassesDir | Out-Null

$rootLength = $Root.Length + 1
$sources = Get-ChildItem -Path (Join-Path $Root "src") -Recurse -Filter "*.java" |
    Sort-Object FullName |
    ForEach-Object {
        $relativePath = $_.FullName.Substring($rootLength).Replace('\', '/')
        '"' + $relativePath + '"'
    }

[System.IO.File]::WriteAllLines($SourceList, $sources, [System.Text.UTF8Encoding]::new($false))

$libs = Get-ChildItem -Path (Join-Path $Root "lib") -Filter "*.jar" |
    Sort-Object FullName |
    ForEach-Object { $_.FullName }

$classpath = (@($ClassesDir) + $libs) -join [System.IO.Path]::PathSeparator

Write-Host "Compiling Java sources with $Javac..."
& $Javac "-encoding" "UTF-8" "-source" "17" "-target" "17" "-cp" $classpath "-processorpath" $classpath "-d" $ClassesDir "@$SourceList"

if ($LASTEXITCODE -ne 0) {
    throw "javac failed with exit code $LASTEXITCODE."
}

Write-Host "Build complete: $ClassesDir"
