<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webJsonResponse(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], 405);
}

if ($_Login === null || $_Id === null) {
    webJsonResponse(['success' => false, 'message' => 'Bạn cần đăng nhập trước.'], 401);
}

if (!webHasAccountColumn($conn, 'active') || !webHasAccountColumn($conn, 'vnd')) {
    webJsonResponse(['success' => false, 'message' => 'Database thiếu cột active hoặc vnd.'], 500);
}

if ((int) $_Status === 1) {
    webJsonResponse(['success' => false, 'message' => 'Tài khoản đã được kích hoạt rồi.']);
}

if ((int) $_BalanceVnd < 10000) {
    webJsonResponse(['success' => false, 'message' => 'Số dư không đủ 10.000đ để kích hoạt.'], 422);
}

$stmt = $conn->prepare("UPDATE account SET active = 1, vnd = vnd - 10000 WHERE id = :id AND active = 0 AND vnd >= 10000");
$stmt->execute(['id' => $_Id]);

webJsonResponse([
    'success' => $stmt->rowCount() > 0,
    'message' => $stmt->rowCount() > 0 ? 'Kích hoạt tài khoản thành công.' : 'Không thể kích hoạt tài khoản lúc này.'
]);
