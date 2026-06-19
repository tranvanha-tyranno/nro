<?php
require_once '../../Controllers/Connections.php';
require_once '../../Controllers/Sessions.php';
require_once '../../Controllers/Configs.php';

$message = '';

if ($_Login !== null) {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Vui lòng nhập tài khoản và mật khẩu.';
    } elseif (!isValidInput($username)) {
        $message = 'Tên tài khoản chỉ dùng chữ, số và dấu gạch dưới.';
    } elseif ((int) $_AuthLog === 1) {
        $message = 'Đăng nhập đang tạm đóng.';
    } else {
        $account = webFetchAccountByUsername($conn, $username);
        if ($account && (string) $account['password'] === $password) {
            $_SESSION['account'] = $account['username'];
            $_SESSION['id'] = $account['id'];
            header('Location: /Users/Profile');
            exit;
        }
        $message = 'Tài khoản hoặc mật khẩu chưa đúng.';
    }
}

include '../../Controllers/Header.php';
?>

<div class="auth-wrap">
    <section class="panel auth-card">
        <div class="auth-pane">
            <div class="auth-switch">
                <a class="btn btn-secondary active" href="/Auth/Login">Đăng nhập</a>
                <a class="btn btn-secondary" href="/Auth/Register">Đăng ký</a>
            </div>

            <div class="stack">
                <div>
                    <h2 class="panel-title">Chào mừng quay lại</h2>
                    <p class="panel-subtitle">Vào khu người dùng để nạp tiền, xem nhân vật và quản lý tài khoản.</p>
                </div>

                <?php if ($message !== '') { ?>
                    <div class="callout callout-danger"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
                <?php } ?>

                <form method="post" class="form-grid">
                    <div class="field">
                        <label for="username">Tài khoản</label>
                        <input id="username" name="username" class="input" placeholder="Nhập username" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" autofocus required>
                    </div>
                    <div class="field">
                        <label for="password">Mật khẩu</label>
                        <input id="password" type="password" name="password" class="input" placeholder="Nhập mật khẩu" required>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Đăng nhập</button>
                </form>

                <div class="help">Nếu vừa tạo tài khoản xong, bạn có thể đăng nhập ngay và kiểm tra hồ sơ.</div>
            </div>
        </div>

        <div class="auth-pane auth-side">
            <div class="auth-side-content">
                <div class="badge-soft">Truy cập nhanh</div>
                <h2>Vào game, quản lý web và nạp tiền trong một luồng gọn hơn.</h2>
                <ul>
                    <li>Xem số dư, tổng nạp và trạng thái kích hoạt.</li>
                    <li>Mở lịch sử nạp thẻ và giao dịch ngân hàng.</li>
                    <li>Đi tới trang tải game hoặc bảng xếp hạng chỉ bằng một lần bấm.</li>
                </ul>
                <div>
                    <a class="btn btn-ghost" href="/Others/Downloads">Tải game trước</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../../Controllers/Footer.php'; ?>
