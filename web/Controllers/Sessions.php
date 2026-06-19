<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/GameData.php';

$_Login = null;
$_Users = $_SESSION['account'] ?? null;
$_Ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

$_Admin = 0;
$_Id = null;
$_Username = null;
$_Password = null;
$_Email = null;
$_Status = 0;
$_BalanceVnd = 0;
$_Coins = 0;
$_TCoins = 0;
$_ThoiVang = 0;
$_EventPoint = 0;
$_Player = null;
$_PlayerName = null;
$_PlayerPower = 0;

if ($_Users !== null) {
    $userState = webCurrentUserState($conn, $_Users);

    if ($userState !== null) {
        $_Login = 'on';
        $_Admin = $userState['is_admin'];
        $_Id = $userState['id'];
        $_Username = $userState['username'];
        $_Password = $userState['password'];
        $_Email = $userState['email'];
        $_Status = $userState['status'];
        $_BalanceVnd = $userState['balance_vnd'];
        $_Coins = $_BalanceVnd;
        $_TCoins = $userState['tongnap'];
        $_ThoiVang = $userState['thoi_vang'];
        $_EventPoint = $userState['event_point'];
        $_Player = $userState['player'];
        $_PlayerName = $userState['player_name'];
        $_PlayerPower = $userState['player_power'];
    } else {
        unset($_SESSION['account'], $_SESSION['id']);
    }
}

function formatMoney($number)
{
    if (!is_numeric($number) || $number === null) {
        return '0';
    }

    return webFormatNumber($number);
}

function isValidInput($input)
{
    return (bool) preg_match('/^[a-zA-Z0-9_]+$/', $input) && strlen($input) <= 255;
}

function generateCaptcha($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captcha;
}

if (!isset($_POST['captcha'])) {
    $_SESSION['captcha'] = generateCaptcha(6);
}

function checkExistingUsername($conn, $username)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM account WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn() > 0;
}

function checkExistingEmail($conn, $email)
{
    if (!webHasAccountColumn($conn, 'email')) {
        return false;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM account WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0;
}

function insertAccount($conn, $username, $password, $ipAddress)
{
    $columns = ['username', 'password'];
    $params = [
        'username' => $username,
        'password' => $password,
    ];

    if (webHasAccountColumn($conn, 'ip_address')) {
        $columns[] = 'ip_address';
        $params['ip_address'] = $ipAddress;
    }

    if (webHasAccountColumn($conn, 'email')) {
        $columns[] = 'email';
        $params['email'] = '';
    }

    if (webHasAccountColumn($conn, 'create_time')) {
        $columns[] = 'create_time';
        $params['create_time'] = date('Y-m-d H:i:s');
    }

    if (webHasAccountColumn($conn, 'update_time')) {
        $columns[] = 'update_time';
        $params['update_time'] = date('Y-m-d H:i:s');
    }

    if (webHasAccountColumn($conn, 'active')) {
        $columns[] = 'active';
        $params['active'] = 1;
    }

    $placeholders = [];
    foreach ($columns as $column) {
        $placeholders[] = ':' . $column;
    }
    $sql = sprintf(
        'INSERT INTO account (%s) VALUES (%s)',
        implode(', ', $columns),
        implode(', ', $placeholders)
    );

    $stmt = $conn->prepare($sql);
    return $stmt->execute($params);
}

function hasIPBeenUsedForReferral($conn, $ip)
{
    if (!webTableExists($conn, 'referrals')) {
        return false;
    }

    $stmt = $conn->prepare("SELECT idRef FROM referrals WHERE IP = ? LIMIT 1");
    $stmt->execute([$ip]);
    return $stmt->fetchColumn();
}

function checkReferralAlreadyAwarded($conn, $ip, $idRef)
{
    if (!webTableExists($conn, 'referrals')) {
        return false;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM referrals WHERE IP = ? AND idRef = ?");
    $stmt->execute([$ip, $idRef]);
    return $stmt->fetchColumn() > 0;
}

function getReferrerIP($conn, $idRef)
{
    $stmt = $conn->prepare("SELECT ip_address FROM account WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $idRef]);
    return $stmt->fetchColumn();
}

function updateReferralPoints($conn, $idRef)
{
    if (!webHasAccountColumn($conn, 'referral_points')) {
        return;
    }

    $stmt = $conn->prepare("UPDATE account SET referral_points = referral_points + 1 WHERE id = ?");
    $stmt->execute([$idRef]);
}

function logReferral($conn, $ip, $idRef)
{
    if (!webTableExists($conn, 'referrals')) {
        return;
    }

    $stmt = $conn->prepare("INSERT INTO referrals (IP, idRef) VALUES (?, ?)");
    $stmt->execute([$ip, $idRef]);
}
