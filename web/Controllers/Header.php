<?php
require_once __DIR__ . '/Connections.php';
require_once __DIR__ . '/Sessions.php';
require_once __DIR__ . '/Configs.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isUserPage = strpos($requestPath, '/Users/') === 0;
$isAuthPage = strpos($requestPath, '/Auth/') === 0;

if (($isUserPage && $_Login === null) || ($isAuthPage && $_Login !== null && $requestPath !== '/Auth/Logout')) {
    header('Location: /');
    exit;
}

if (isset($_FixWeb) && (int) $_FixWeb === 1 && (int) ($_Admin ?? 0) !== 1) {
    echo 'Website dang bao tri, vui long quay lai sau.';
    exit;
}

$currentLabel = 'Trang chủ';
if ($isAuthPage) {
    $currentLabel = strpos($requestPath, '/Auth/Register') === 0 ? 'Đăng ký' : 'Đăng nhập';
} elseif ($isUserPage) {
    $userMap = [
        '/Users/Profile' => 'Tài khoản',
        '/Users/Payment' => 'Nạp tiền',
        '/Users/History' => 'Lịch sử',
        '/Users/Gold' => 'Đổi thỏi vàng',
        '/Users/ChangePassword' => 'Đổi mật khẩu',
        '/Users/Admin' => 'Quản trị',
    ];
    $currentLabel = $userMap[$requestPath] ?? 'Khu người dùng';
} elseif (strpos($requestPath, '/Others/') === 0) {
    $otherMap = [
        '/Others/Downloads' => 'Tải game',
        '/Others/Top' => 'Bảng xếp hạng',
        '/Others/Sukien' => 'Cẩm nang',
        '/Others/Tinhnang' => 'Tính năng',
        '/Others/Mocnap' => 'Mốc nạp',
    ];
    $currentLabel = $otherMap[$requestPath] ?? 'Thông tin';
}

$domainLabel = $_Domain ?? ($_domain ?? 'Local');
$domainHost = parse_url($domainLabel, PHP_URL_HOST) ?: preg_replace('/^https?:\/\//', '', rtrim($domainLabel, '/'));
$serverName = $_ServerName ?? $_Title;
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101820">
    <meta name="description" content="<?= htmlspecialchars($_Description, ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" href="/Assets/Images/<?= htmlspecialchars($_Logo, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="/Assets/Css/app.css">
</head>
<body>
<div id="customToast" class="hidden" role="status" aria-live="polite"></div>
<div class="app-shell">
    <header class="topbar">
        <a class="brand" href="/">
            <img src="/Assets/Images/<?= htmlspecialchars($_Logo, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?>">
            <span>
                <span class="brand-title"><?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="brand-subtitle"><?= htmlspecialchars($serverName, ENT_QUOTES, 'UTF-8') ?></span>
            </span>
        </a>
        <div class="topbar-actions">
            <?php if ($_Login !== null) { ?>
                <?php if ((int) $_Admin === 1) { ?>
                    <a class="btn btn-ghost btn-sm" href="/Users/Admin">Admin</a>
                <?php } ?>
                <a class="btn btn-ghost btn-sm" href="/Users/Profile"><?= htmlspecialchars($_Username, ENT_QUOTES, 'UTF-8') ?></a>
                <a class="btn btn-secondary btn-sm" href="/Auth/Logout">Đăng xuất</a>
            <?php } else { ?>
                <a class="btn btn-ghost btn-sm" href="/Auth/Login">Đăng nhập</a>
                <a class="btn btn-primary btn-sm" href="/Auth/Register">Đăng ký</a>
            <?php } ?>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <div class="badge-soft"><?= htmlspecialchars($currentLabel, ENT_QUOTES, 'UTF-8') ?></div>
            <h1><?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars($_Description, ENT_QUOTES, 'UTF-8') ?></p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="/Others/Downloads">Tải game</a>
                <a class="btn btn-ghost" href="/Others/Top">Xem top</a>
                <a class="btn btn-ghost" href="<?= $_Login ? '/Users/Payment' : '/Auth/Login' ?>">Nạp tiền</a>
            </div>

            <div class="chip-row">
                <div class="chip">
                    <span class="chip-label">Máy chủ</span>
                    <span class="chip-value"><?= htmlspecialchars($serverName, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="chip">
                    <span class="chip-label">Tên miền</span>
                    <span class="chip-value"><?= htmlspecialchars($domainHost ?: 'Local', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="chip">
                    <span class="chip-label">Tài khoản</span>
                    <span class="chip-value"><?= $_Login ? ($_Status ? 'Đã kích hoạt' : 'Chưa kích hoạt') : 'Khách' ?></span>
                </div>
            </div>
        </div>
    </section>

    <nav class="nav-grid" aria-label="Điều hướng chính">
        <a class="nav-link-card" href="/">
            <strong>Trang chủ</strong>
            <span>Tổng quan nhanh.</span>
        </a>
        <a class="nav-link-card" href="/Others/Downloads">
            <strong>Tải game</strong>
            <span>Android, iOS, Windows.</span>
        </a>
        <a class="nav-link-card" href="/Others/Top">
            <strong>Bảng xếp hạng</strong>
            <span>Sức mạnh và tổng nạp.</span>
        </a>
        <a class="nav-link-card" href="/Others/Mocnap">
            <strong>Mốc nạp</strong>
            <span>Quà thưởng theo mốc.</span>
        </a>
        <?php if ($_Login) { ?>
            <a class="nav-link-card" href="/Users/Payment">
                <strong>Nạp tiền</strong>
                <span>Thẻ cào và ngân hàng.</span>
            </a>
            <a class="nav-link-card" href="/Users/History">
                <strong>Lịch sử</strong>
                <span>Giao dịch gần đây.</span>
            </a>
        <?php } else { ?>
            <a class="nav-link-card" href="/Auth/Login">
                <strong>Đăng nhập</strong>
                <span>Vào khu người dùng.</span>
            </a>
            <a class="nav-link-card" href="/Auth/Register">
                <strong>Đăng ký</strong>
                <span>Tạo tài khoản mới.</span>
            </a>
        <?php } ?>
    </nav>
