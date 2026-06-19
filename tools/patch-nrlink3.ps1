param(
    [string]$FilePath = "$env:USERPROFILE\AppData\LocalLow\XUNGLORDLOCAL\XUNGLORDLOCAL\NRlink3",
    [string[]]$Hosts = @("127.0.0.1", "nro.luminostech.tech", "nro.luminostech.tech")
)

$ErrorActionPreference = "Stop"

if (-not (Test-Path -LiteralPath $FilePath)) {
    throw "NRlink file not found: $FilePath"
}

$entryNames = @(
    "XungLord LOCAL INT",
    "XungLord LOCAL LONG",
    "XungLord LOCAL LONG 2"
)

if ($Hosts.Length -ne $entryNames.Length) {
    throw "Hosts count must be exactly $($entryNames.Length)."
}

$bytes = [System.IO.File]::ReadAllBytes($FilePath)
$backupPath = "$FilePath.bak"
Copy-Item -LiteralPath $FilePath -Destination $backupPath -Force

$cursor = 0
$output = New-Object System.Collections.Generic.List[byte]

for ($idx = 0; $idx -lt $entryNames.Length; $idx++) {
    $nameBytes = [System.Text.Encoding]::ASCII.GetBytes($entryNames[$idx])
    $found = $false

    for ($i = $cursor; $i -le $bytes.Length - $nameBytes.Length; $i++) {
        $matched = $true
        for ($j = 0; $j -lt $nameBytes.Length; $j++) {
            if ($bytes[$i + $j] -ne $nameBytes[$j]) {
                $matched = $false
                break
            }
        }

        if (-not $matched) {
            continue
        }

        $found = $true

        $nameStart = $i
        $nameEnd = $nameStart + $nameBytes.Length - 1
        $hostMarkerIndex = $nameEnd + 1
        $hostLengthIndex = $nameEnd + 2
        $hostStart = $nameEnd + 3
        $hostLength = $bytes[$hostLengthIndex]
        $hostEnd = $hostStart + $hostLength - 1

        if ($bytes[$hostMarkerIndex] -ne 0) {
            throw "Unexpected NRlink layout near entry '$($entryNames[$idx])'."
        }

        for ($k = $cursor; $k -le $hostMarkerIndex; $k++) {
            $output.Add($bytes[$k])
        }

        $newHostBytes = [System.Text.Encoding]::ASCII.GetBytes($Hosts[$idx])
        $output.Add([byte]$newHostBytes.Length)
        foreach ($b in $newHostBytes) {
            $output.Add($b)
        }

        $cursor = $hostEnd + 1
        break
    }

    if (-not $found) {
        throw "Could not find NRlink entry '$($entryNames[$idx])'."
    }
}

for ($k = $cursor; $k -lt $bytes.Length; $k++) {
    $output.Add($bytes[$k])
}

[System.IO.File]::WriteAllBytes($FilePath, $output.ToArray())

Write-Host "Patched $($entryNames.Length) server entries in $FilePath"
Write-Host "Backup: $backupPath"
Write-Host "Hosts: $($Hosts -join ', ')"
