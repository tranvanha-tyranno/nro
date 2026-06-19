param(
    [switch]$ResetDatabase
)

$ErrorActionPreference = "Stop"

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$XamppRoot = "C:\xampp"
$MysqlBin = Join-Path $XamppRoot "mysql\bin"
$Mysql = Join-Path $MysqlBin "mysql.exe"
$Mysqld = Join-Path $MysqlBin "mysqld.exe"
$MyIni = Join-Path $MysqlBin "my.ini"
$DbName = "team2026"
$SqlFile = Join-Path $Root "sql\nro1.sql"

function Test-TcpPort {
    param(
        [string]$HostName,
        [int]$Port
    )

    $client = [System.Net.Sockets.TcpClient]::new()
    try {
        $connect = $client.BeginConnect($HostName, $Port, $null, $null)
        if (-not $connect.AsyncWaitHandle.WaitOne(500)) {
            return $false
        }
        $client.EndConnect($connect)
        return $true
    } catch {
        return $false
    } finally {
        $client.Close()
    }
}

if (-not (Test-Path $Mysql) -or -not (Test-Path $Mysqld) -or -not (Test-Path $MyIni)) {
    throw "XAMPP MariaDB was not found at C:\xampp. Install XAMPP 8.2 or update this script."
}

if (-not (Test-TcpPort -HostName "127.0.0.1" -Port 3306)) {
    Write-Host "Starting XAMPP MariaDB on 127.0.0.1:3306..."
    Start-Process -FilePath $Mysqld -ArgumentList "--defaults-file=$MyIni", "--standalone" -WorkingDirectory $XamppRoot -WindowStyle Hidden

    $ready = $false
    for ($i = 0; $i -lt 60; $i++) {
        Start-Sleep -Seconds 1
        if (Test-TcpPort -HostName "127.0.0.1" -Port 3306) {
            $ready = $true
            break
        }
    }

    if (-not $ready) {
        throw "MariaDB did not open port 3306. Check C:\xampp\mysql\data\mysql_error.log."
    }
}

if ($ResetDatabase) {
    Write-Host "Resetting database $DbName..."
    & $Mysql "-uroot" "--protocol=tcp" "-h127.0.0.1" "-P3306" "--execute=DROP DATABASE IF EXISTS $DbName; CREATE DATABASE $DbName CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
} else {
    & $Mysql "-uroot" "--protocol=tcp" "-h127.0.0.1" "-P3306" "--execute=CREATE DATABASE IF NOT EXISTS $DbName CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
}

$tableCountRaw = & $Mysql "-N" "-B" "-uroot" "--protocol=tcp" "-h127.0.0.1" "-P3306" "--execute=SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DbName';"
$tableCount = [int]($tableCountRaw | Select-Object -First 1)

if ($ResetDatabase -or $tableCount -eq 0) {
    if (-not (Test-Path $SqlFile)) {
        throw "SQL dump not found: $SqlFile"
    }

    Write-Host "Importing $SqlFile into $DbName..."
    $cmd = "`"$Mysql`" --default-character-set=utf8mb4 -uroot --protocol=tcp -h127.0.0.1 -P3306 $DbName < `"$SqlFile`""
    cmd.exe /d /c $cmd
    if ($LASTEXITCODE -ne 0) {
        throw "SQL import failed with exit code $LASTEXITCODE."
    }
} else {
    Write-Host "Database $DbName already has $tableCount tables. Use -ResetDatabase to re-import sql\nro1.sql."
}

Write-Host "MariaDB is ready on 127.0.0.1:3306, database $DbName."
