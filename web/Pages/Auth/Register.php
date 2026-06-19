<?php
require_once '../../Controllers/Connections.php';
require_once '../../Controllers/Sessions.php';
require_once '../../Controllers/Configs.php';

$message = '';
$success = false;

if ($_Login !== null) {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ((int) $_AuthLog === 1) {
        $message = 'Đăng ký đang tạm đóng.';
    } elseif ($username === '' || $password === '' || $confirm === '') {
        $message = 'Vui lòng nhập đủ các trường bắt buộc.';
    } elseif (!isValidInput($username) || !isValidInput($password)) {
        $message = 'Tài khoản và mật khẩu chỉ dùng chữ, số và dấu gạch dưới.';
    } elseif ($password !== $confirm) {
        $message = 'Mật khẩu xác nhận chưa khớp.';
    } elseif (checkExistingUsername($conn, $username)) {
        $message = 'Tài khoản này đã tồn tại.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email chưa đúng định dạng.';
    } else {
        if (insertAccount($conn, $username, $password, $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1')) {
            if ($email !== '' && webHasAccountColumn($conn, 'email')) {
                $stmt = $conn->prepare("UPDATE account SET email = :email WHERE username = :username");
                $stmt->execute([
                    'email' => $email,
                    'username' => $username,
                ]);
            }

            $_SESSION['account'] = $username;
            $account = webFetchAccountByUsername($conn, $username);
            if ($account) {
                $_SESSION['id'] = $account['id'];
            }

            header('Location: /Users/Profile');
            exit;
        }

        $message = 'Không thể tạo tài khoản lúc này.';
    }
}

include '../../Controllers/Header.php';
?>

<div class="auth-wrap">
    <section class="panel auth-card">
        <div class="auth-pane">
            <div class="auth-switch">
                <a class="btn btn-secondary" href="/Auth/Login">Đăng nhập</a>
                <a class="btn btn-secondary active" href="/Auth/Register">Đăng ký</a>
            </div>

            <div class="stack">
                <div>
                    <h2 class="panel-title">Tạo tài khoản mới</h2>
                    <p class="panel-subtitle">Đăng ký gọn hơn, vào thẳng khu người dùng sau khi tạo xong.</p>
                </div>

                <?php if ($message !== '') { ?>
                    <div class="callout <?= $success ? 'callout-success' : 'callout-danger' ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
                <?php } ?>

                <form method="post" class="form-grid">
                    <div class="field">
                        <label for="username">Tài khoản</label>
                        <input id="username" name="username" class="input" placeholder="Ví dụ: songoku" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="field">
                        <label for="email">Email không bắt buộc</label>
                        <input id="email" name="email" type="email" class="input" placeholder="Dùng khi cần lấy lại mật khẩu" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-grid two-col">
                        <div class="field">
                            <label for="password">Mật khẩu</label>
                            <input id="password" type="password" name="password" class="input" required>
                        </div>
                        <div class="field">
                            <label for="confirm_password">Nhập lại mật khẩu</label>
                            <input id="confirm_password" type="password" name="confirm_password" class="input" required>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Tạo tài khoản</button>
                </form>
            </div>
        </div>

        <div class="auth-pane auth-side">
            <div class="auth-side-content">
                <div class="badge-soft">Đăng ký nhanh</div>
                <h2>Tạo xong là vào ngay hồ sơ để kiểm tra nhân vật, số dư và trạng thái.</h2>
                <ul>
                    <li>Username và mật khẩu dùng chữ, số hoặc dấu gạch dưới.</li>
                    <li>Email có thể thêm sau nếu chưa muốn nhập ngay.</li>
                    <li>Giao diện mới gọn hơn cho cả điện thoại và máy tính.</li>
                </ul>
                <div>
                    <a class="btn btn-ghost" href="/Auth/Login">Tôi đã có tài khoản</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../../Controllers/Footer.php'; ?>
