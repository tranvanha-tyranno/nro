<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../Vendor/autoload.php';
include '../../Controllers/Connections.php';
include '../../Controllers/Configs.php';
include '../../Controllers/Sessions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webJsonResponse(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], 405);
}

if (!webHasAccountColumn($conn, 'email')) {
    webJsonResponse(['success' => false, 'message' => 'Database chưa có cột email.'], 500);
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$email = filter_var($postData['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    webJsonResponse(['success' => false, 'message' => 'Email không hợp lệ.'], 422);
}

if (!checkExistingEmail($conn, $email)) {
    webJsonResponse(['success' => false, 'message' => 'Email không tồn tại trong hệ thống.'], 404);
}

$newPassword = (string) random_int(100000, 999999);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ForgotEmail;
    $mail->Password = $_ForgotPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($_ForgotEmail, $_ServerName ?? $_Title);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Mật khẩu mới';
    $mail->Body = '<p>Mật khẩu mới của bạn là: <strong>' . htmlspecialchars($newPassword, ENT_QUOTES, 'UTF-8') . '</strong></p><p>Vui lòng đăng nhập và đổi lại mật khẩu sau khi vào web.</p>';
    $mail->send();

    $stmt = $conn->prepare("UPDATE account SET password = :password WHERE email = :email");
    $stmt->execute([
        'password' => $newPassword,
        'email' => $email,
    ]);

    webJsonResponse(['success' => true, 'message' => 'Đã gửi mật khẩu mới về email.']);
} catch (Exception $e) {
    error_log('Forgot password mail failed: ' . $e->getMessage());
    webJsonResponse(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.'], 500);
}
