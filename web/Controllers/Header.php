<?php
require_once __DIR__ . '/Connections.php';
require_once __DIR__ . '/Sessions.php';
require_once __DIR__ . '/Configs.php';

$requestPath = $_SERVER['REQUEST_URI'] ?? '/';
$isUserPage = strpos($requestPath, '/Users/') !== false;
$isAuthPage = strpos($requestPath, '/Auth/') !== false;

if (($isUserPage && $_Login === null) || ($isAuthPage && $_Login !== null)) {
    header('Location: /');
    exit;
}

if (isset($_FixWeb) && (int) $_FixWeb === 1) {
    echo 'Website đang bảo trì, vui lòng quay lại sau.';
    exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7b1e1e">
    <meta name="description" content="<?= htmlspecialchars($_Description, ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" href="/Assets/Images/<?= htmlspecialchars($_Logo, ENT_QUOTES, 'UTF-8') ?>">
    <link href="/Assets/Css/css/1/bootstrap1.min.css" rel="stylesheet">
    <script src="/Assets/Css/css/1/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            background: #f6e5cf url("/Assets/Images/img/c6b177d5b33f4f60badf36458a784fca.png") center/cover fixed;
            color: #4b2d1b;
        }
        .site-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 16px;
        }
        .site-banner {
            width: 100%;
            border-radius: 12px;
            display: block;
            margin-bottom: 12px;
        }
        .site-panel {
            background: rgba(255, 246, 232, 0.96);
            border: 1px solid #d7b98f;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(75, 45, 27, 0.08);
        }
        .site-nav a,
        .quick-links a {
            text-decoration: none;
        }
        .site-nav .btn,
        .quick-links .btn {
            border-radius: 8px;
            font-weight: 600;
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }
        .status-card {
            background: #fff7ec;
            border: 1px solid #ead1ae;
            border-radius: 8px;
            padding: 10px 12px;
        }
        .status-label {
            display: block;
            font-size: 12px;
            color: #8a6646;
        }
        .status-value {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: #7b1e1e;
        }
    </style>
</head>
<body>
<div class="site-shell">
    <a href="/"><img src="/Assets/Images/logo.gif" alt="Banner" class="site-banner"></a>

    <div id="customToast" style="display:none"></div>

    <div class="site-panel p-3 mb-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="h4 mb-1"><?= htmlspecialchars($_Title, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="text-muted"><?= htmlspecialchars($_Description, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="quick-links d-flex flex-wrap gap-2">
                <a href="/Others/Downloads" class="btn btn-warning">Tải game</a>
                <a href="/Others/Top" class="btn btn-outline-danger">BXH</a>
                <a href="/Users/Payment" class="btn btn-outline-primary">Nạp tiền</a>
                <?php if (!empty($_Zalo) && $_Zalo !== 'Link Fanpage') { ?>
                    <a href="<?= htmlspecialchars($_Zalo, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success" target="_blank" rel="noreferrer">Zalo</a>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if ($_Login !== null) { ?>
        <div class="site-panel p-3 mb-3">
            <div class="status-grid">
                <div class="status-card">
                    <span class="status-label">Tài khoản</span>
                    <span class="status-value"><?= htmlspecialchars($_Username, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Nhân vật</span>
                    <span class="status-value"><?= htmlspecialchars($_PlayerName ?? 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Số dư</span>
                    <span class="status-value"><?= webFormatCurrency($_BalanceVnd) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Tổng nạp</span>
                    <span class="status-value"><?= webFormatCurrency($_TCoins) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Thỏi vàng</span>
                    <span class="status-value"><?= webFormatNumber($_ThoiVang) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Trạng thái</span>
                    <span class="status-value"><?= $_Status ? 'Đã kích hoạt' : 'Chưa kích hoạt' ?></span>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="site-panel p-3 mb-3 site-nav">
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-danger" href="/">Trang chủ</a>
            <a class="btn btn-outline-danger" href="/Others/Downloads">Tải game</a>
            <a class="btn btn-outline-danger" href="/Others/Top">Bảng xếp hạng</a>
            <?php if ($_Login === null) { ?>
                <a class="btn btn-outline-primary" href="/Auth/Login">Đăng nhập</a>
                <a class="btn btn-outline-success" href="/Auth/Register">Đăng ký</a>
            <?php } else { ?>
                <a class="btn btn-outline-primary" href="/Users/Profile">Tài khoản</a>
                <a class="btn btn-outline-primary" href="/Users/Payment">Nạp tiền</a>
                <a class="btn btn-outline-primary" href="/Users/History">Lịch sử</a>
                <a class="btn btn-outline-primary" href="/Users/Gold">Đổi thỏi vàng</a>
                <?php if ((int) $_Admin === 1) { ?>
                    <a class="btn btn-outline-dark" href="/Users/Admin">Admin</a>
                <?php } ?>
                <a class="btn btn-outline-secondary" href="/Auth/Logout">Đăng xuất</a>
            <?php } ?>
        </div>
    </div>
