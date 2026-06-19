<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webJsonResponse(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], 405);
}

if ($_Login === null || $_Id === null) {
    webJsonResponse(['success' => false, 'message' => 'Bạn cần đăng nhập trước.'], 401);
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$currentPassword = trim($postData['current_password'] ?? '');
$newPassword = trim($postData['newpassword'] ?? '');
$confirmPassword = trim($postData['newpassword_confirm'] ?? '');

if ($currentPassword !== $_Password) {
    webJsonResponse(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng.'], 422);
}

if (!isValidInput($newPassword)) {
    webJsonResponse(['success' => false, 'message' => 'Mật khẩu mới chỉ được dùng chữ, số và dấu gạch dưới.'], 422);
}

if ($newPassword !== $confirmPassword) {
    webJsonResponse(['success' => false, 'message' => 'Mật khẩu xác nhận chưa khớp.'], 422);
}

if ($newPassword === $currentPassword) {
    webJsonResponse(['success' => false, 'message' => 'Mật khẩu mới phải khác mật khẩu cũ.'], 422);
}

$stmt = $conn->prepare("UPDATE account SET password = :password WHERE id = :id");
$stmt->execute([
    'password' => $newPassword,
    'id' => $_Id,
]);

webJsonResponse([
    'success' => true,
    'message' => 'Đổi mật khẩu thành công.'
]);
