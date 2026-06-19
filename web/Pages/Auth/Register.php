<?php
include '../../Controllers/Header.php';

$_ThongBao = '';
$num1 = rand(1, 99);
$num2 = rand(1, 99);
$_SESSION['captcha_result'] = $num1 + $num2;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rePassword = trim($_POST['repassword'] ?? '');
    $captcha = (int) ($_POST['captcha'] ?? 0);

    if ((int) $_AuthLog === 1) {
        $_ThongBao = 'Đăng ký đang tạm khóa.';
    } elseif ($captcha !== (int) ($_SESSION['captcha_result'] ?? -1)) {
        $_ThongBao = 'Phép tính xác nhận chưa đúng.';
    } elseif (!isValidInput($username) || !isValidInput($password)) {
        $_ThongBao = 'Tài khoản và mật khẩu chỉ gồm chữ, số và dấu gạch dưới.';
    } elseif ($password !== $rePassword) {
        $_ThongBao = 'Mật khẩu nhập lại chưa khớp.';
    } elseif (checkExistingUsername($conn, $username)) {
        $_ThongBao = 'Tài khoản đã tồn tại.';
    } elseif (insertAccount($conn, $username, $password, $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1')) {
        $_ThongBao = 'Đăng ký thành công, giờ bạn có thể đăng nhập.';
    } else {
        $_ThongBao = 'Không thể tạo tài khoản lúc này.';
    }
}
?>

<div class="site-panel p-3 mx-auto" style="max-width:460px;">
    <div class="h4 text-center mb-3">Đăng ký</div>
    <?php if ($_ThongBao !== '') { ?>
        <div class="alert <?= strpos($_ThongBao, 'thành công') !== false ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($_ThongBao, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Tài khoản</label>
            <input class="form-control" name="username" minlength="3" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" minlength="3" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" name="repassword" minlength="3" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Xác nhận: <?= $num1 ?> + <?= $num2 ?> = ?</label>
            <input type="number" class="form-control" name="captcha" required>
        </div>
        <button class="btn btn-success w-100" type="submit">Tạo tài khoản</button>
    </form>
    <div class="text-center mt-3">
        Đã có tài khoản? <a href="/Auth/Login">Đăng nhập</a>
    </div>
</div>

<?php include '../../Controllers/Footer.php'; ?>
