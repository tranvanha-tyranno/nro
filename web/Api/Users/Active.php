<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

header('Content-Type: application/json');

if ($_Login === null || $_Id === null) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập trước.']);
    exit;
}

if ((int) $_Status === 1) {
    echo json_encode(['success' => false, 'message' => 'Tài khoản đã được kích hoạt rồi.']);
    exit;
}

if ((int) $_BalanceVnd < 10000) {
    echo json_encode(['success' => false, 'message' => 'Số dư không đủ 10.000đ để kích hoạt.']);
    exit;
}

$stmt = $conn->prepare("UPDATE account SET active = 1, vnd = vnd - 10000 WHERE id = :id AND active = 0");
$stmt->execute(['id' => $_Id]);

echo json_encode([
    'success' => $stmt->rowCount() > 0,
    'message' => $stmt->rowCount() > 0 ? 'Kích hoạt tài khoản thành công.' : 'Không thể kích hoạt tài khoản lúc này.'
]);
