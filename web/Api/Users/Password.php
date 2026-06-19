<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

header('Content-Type: application/json');

if ($_Login === null || $_Id === null) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước.']);
    exit;
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$currentPassword = trim($postData['current_password'] ?? '');
$newPassword = trim($postData['newpassword'] ?? '');
$confirmPassword = trim($postData['newpassword_confirm'] ?? '');

if ($currentPassword !== $_Password) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng.']);
    exit;
}

if (!isValidInput($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu mới chỉ được dùng chữ, số và dấu gạch dưới.']);
    exit;
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận chưa khớp.']);
    exit;
}

if ($newPassword === $currentPassword) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải khác mật khẩu cũ.']);
    exit;
}

$stmt = $conn->prepare("UPDATE account SET password = :password WHERE id = :id");
$stmt->execute([
    'password' => $newPassword,
    'id' => $_Id,
]);

echo json_encode([
    'success' => true,
    'message' => 'Đổi mật khẩu thành công.'
]);
