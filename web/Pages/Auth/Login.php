<?php
include '../../Controllers/Header.php';

$_ThongBao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!isValidInput($username) || $password === '') {
        $_ThongBao = 'Vui lòng nhập đúng tài khoản và mật khẩu.';
    } elseif ((int) $_AuthLog === 1) {
        $_ThongBao = 'Đăng nhập đang tạm khóa.';
    } else {
        $account = webFetchAccountByUsername($conn, $username);
        if ($account && (string) $account['password'] === $password) {
            $_SESSION['account'] = $account['username'];
            $_SESSION['id'] = $account['id'];
            header('Location: /');
            exit;
        }
        $_ThongBao = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }
}
?>

<div class="site-panel p-3 mx-auto" style="max-width:420px;">
    <div class="h4 text-center mb-3">Đăng nhập</div>
    <?php if ($_ThongBao !== '') { ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_ThongBao, ENT_QUOTES, 'UTF-8') ?></div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tài khoản</label>
            <input class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
    </form>
    <div class="text-center mt-3">
        Chưa có tài khoản? <a href="/Auth/Register">Đăng ký ngay</a>
    </div>
</div>

<?php include '../../Controllers/Footer.php'; ?>
