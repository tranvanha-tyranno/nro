<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';
include '../../Controllers/Configs.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webJsonResponse(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], 405);
}

if ($_Login === null || $_Username === null) {
    webJsonResponse(['success' => false, 'message' => 'Bạn cần đăng nhập để nạp thẻ.'], 401);
}

if ((int) $_TrangThai !== 1) {
    webJsonResponse(['success' => false, 'message' => 'Nạp thẻ đang bảo trì, vui lòng thử lại sau.'], 503);
}

if (!webTableExists($conn, 'napthe')) {
    webJsonResponse(['success' => false, 'message' => 'Database chưa có bảng napthe.'], 500);
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$telco = strtoupper(trim($postData['telco'] ?? ''));
$amount = (int) ($postData['amount'] ?? 0);
$serial = trim($postData['serial'] ?? '');
$code = trim($postData['code'] ?? '');
$allowedTelcos = ['VIETTEL', 'VINAPHONE', 'MOBIFONE', 'GATE', 'ZING'];
$allowedAmounts = [10000, 20000, 50000, 100000, 200000, 500000, 1000000];

if (!in_array($telco, $allowedTelcos, true) || !in_array($amount, $allowedAmounts, true) || $serial === '' || $code === '') {
    webJsonResponse(['success' => false, 'message' => 'Thông tin thẻ không hợp lệ.'], 422);
}

if (strlen($serial) > 255 || strlen($code) > 255) {
    webJsonResponse(['success' => false, 'message' => 'Mã thẻ hoặc serial quá dài.'], 422);
}

$requestId = (string) random_int(100000000, 999999999);
$stmt = $conn->prepare("
    INSERT INTO napthe (request_id, user_nap, telco, serial, code, amount, status)
    VALUES (:request_id, :user_nap, :telco, :serial, :code, :amount, 99)
");
$stmt->execute([
    'request_id' => $requestId,
    'user_nap' => $_Username,
    'telco' => $telco,
    'serial' => $serial,
    'code' => $code,
    'amount' => $amount,
]);

webJsonResponse([
    'success' => true,
    'message' => 'Đã lưu yêu cầu nạp thẻ. Hệ thống sẽ cập nhật khi thẻ được xử lý.'
]);
