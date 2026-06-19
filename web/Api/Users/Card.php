<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';
include '../../Controllers/Configs.php';

header('Content-Type: application/json');

if ($_Login === null || $_Username === null) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để nạp thẻ.']);
    exit;
}

if ((int) $_TrangThai !== 1) {
    echo json_encode(['success' => false, 'message' => 'Nạp thẻ đang bảo trì, vui lòng thử lại sau.']);
    exit;
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$required = ['telco', 'amount', 'serial', 'code'];
foreach ($required as $field) {
    if (empty($postData[$field])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin thẻ.']);
        exit;
    }
}

$requestId = random_int(100000000, 999999999);
$insertQuery = "
    INSERT INTO napthe (request_id, user_nap, telco, serial, code, amount, status)
    VALUES (:request_id, :user_nap, :telco, :serial, :code, :amount, 99)
";
$stmt = $conn->prepare($insertQuery);
$stmt->execute([
    'request_id' => $requestId,
    'user_nap' => $_Username,
    'telco' => $postData['telco'],
    'serial' => $postData['serial'],
    'code' => $postData['code'],
    'amount' => (int) $postData['amount'],
]);

echo json_encode([
    'success' => true,
    'message' => 'Đã lưu yêu cầu nạp thẻ. Callback sẽ tự cộng khi thẻ thành công.'
]);
