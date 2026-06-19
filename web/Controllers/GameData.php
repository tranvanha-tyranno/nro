<?php

function webTableExists(PDO $conn, string $tableName): bool
{
    static $cache = [];
    if (array_key_exists($tableName, $cache)) {
        return $cache[$tableName];
    }

    $stmt = $conn->prepare("SHOW TABLES LIKE :table_name");
    $stmt->execute(['table_name' => $tableName]);
    $cache[$tableName] = (bool) $stmt->fetchColumn();
    return $cache[$tableName];
}

function webAccountColumns(PDO $conn): array
{
    static $columns = null;
    if ($columns !== null) {
        return $columns;
    }

    $columns = [];
    foreach ($conn->query("SHOW COLUMNS FROM account") as $row) {
        $columns[] = $row['Field'];
    }

    return $columns;
}

function webHasAccountColumn(PDO $conn, string $column): bool
{
    return in_array($column, webAccountColumns($conn), true);
}

function webTableColumns(PDO $conn, string $tableName): array
{
    static $columnsByTable = [];
    if (array_key_exists($tableName, $columnsByTable)) {
        return $columnsByTable[$tableName];
    }

    if (!webTableExists($conn, $tableName)) {
        $columnsByTable[$tableName] = [];
        return [];
    }

    $columnsByTable[$tableName] = [];
    foreach ($conn->query("SHOW COLUMNS FROM `$tableName`") as $row) {
        $columnsByTable[$tableName][] = $row['Field'];
    }

    return $columnsByTable[$tableName];
}

function webHasTableColumn(PDO $conn, string $tableName, string $column): bool
{
    return in_array($column, webTableColumns($conn, $tableName), true);
}

function webFetchAccountByUsername(PDO $conn, string $username): ?array
{
    $stmt = $conn->prepare("SELECT * FROM account WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    return $account ?: null;
}

function webFetchPlayerByAccountId(PDO $conn, int $accountId): ?array
{
    if (!webTableExists($conn, 'player')) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM player WHERE account_id = :account_id LIMIT 1");
    $stmt->execute(['account_id' => $accountId]);
    $player = $stmt->fetch(PDO::FETCH_ASSOC);
    return $player ?: null;
}

function webSafeAccountValue(array $account, string $column, $default = 0)
{
    return array_key_exists($column, $account) ? $account[$column] : $default;
}

function webJsonStat($json, int $index, int $default = 0): int
{
    if ($json === null || $json === '') {
        return $default;
    }

    $decoded = json_decode($json, true);
    if (!is_array($decoded) || !isset($decoded[$index])) {
        return $default;
    }

    return (int) $decoded[$index];
}

function webCurrentUserState(PDO $conn, ?string $username): ?array
{
    if ($username === null || $username === '') {
        return null;
    }

    $account = webFetchAccountByUsername($conn, $username);
    if ($account === null) {
        return null;
    }

    $player = webFetchPlayerByAccountId($conn, (int) $account['id']);
    $tongNap = (int) webSafeAccountValue($account, 'tongnap', 0);
    $balance = (int) webSafeAccountValue($account, 'vnd', 0);
    $goldBar = (int) webSafeAccountValue($account, 'thoi_vang', 0);
    $adminValue = webSafeAccountValue($account, 'is_admin', webSafeAccountValue($account, 'admin', 0));

    return [
        'account' => $account,
        'player' => $player,
        'id' => (int) $account['id'],
        'username' => $account['username'],
        'password' => (string) webSafeAccountValue($account, 'password', ''),
        'email' => (string) webSafeAccountValue($account, 'email', ''),
        'is_admin' => (int) $adminValue,
        'status' => (int) webSafeAccountValue($account, 'active', 0),
        'balance_vnd' => $balance,
        'tongnap' => $tongNap,
        'thoi_vang' => $goldBar,
        'event_point' => (int) webSafeAccountValue($account, 'event_point', 0),
        'player_name' => $player['name'] ?? null,
        'player_power' => $player ? webJsonStat($player['data_point'] ?? '[]', 1) : 0,
        'player_hp' => $player ? webJsonStat($player['data_point'] ?? '[]', 2) : 0,
        'player_mp' => $player ? webJsonStat($player['data_point'] ?? '[]', 4) : 0,
        'player_head' => $player['head'] ?? null,
        'player_gender' => isset($player['gender']) ? (int) $player['gender'] : null,
    ];
}

function webFormatNumber($number): string
{
    return number_format((float) $number, 0, ',', '.');
}

function webFormatCurrency($number, string $suffix = 'đ'): string
{
    return webFormatNumber($number) . $suffix;
}

function webJsonResponse(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}
