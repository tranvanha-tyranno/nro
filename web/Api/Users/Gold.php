<?php
include_once '../../Controllers/Connections.php';
include_once '../../Controllers/Sessions.php';
include_once '../../Controllers/Configs.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    webJsonResponse(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], 405);
}

if ($_Login === null || $_Id === null) {
    webJsonResponse(['success' => false, 'message' => 'Bạn cần đăng nhập trước.'], 401);
}

if (!webHasAccountColumn($conn, 'vnd') || !webHasAccountColumn($conn, 'thoi_vang')) {
    webJsonResponse(['success' => false, 'message' => 'Database thiếu cột vnd hoặc thoi_vang.'], 500);
}

$postData = json_decode(file_get_contents('php://input'), true) ?: [];
$vndAmount = (int) ($postData['vnd_amount'] ?? 0);
$goldRate = max(1, (int) ($_ThoiVangRate ?? 1));
$goldMap = [
    10000 => 50,
    20000 => 100,
    30000 => 150,
    50000 => 250,
    100000 => 500,
    200000 => 1000,
    500000 => 2500,
    1000000 => 5000,
    2000000 => 10000,
];

if (!array_key_exists($vndAmount, $goldMap)) {
    webJsonResponse(['success' => false, 'message' => 'Mốc đổi không hợp lệ.'], 422);
}

$goldAmount = $goldMap[$vndAmount] * $goldRate;

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("
        UPDATE account
        SET vnd = vnd - :vnd_amount,
            thoi_vang = thoi_vang + :gold_amount
        WHERE id = :id AND vnd >= :vnd_amount
    ");
    $stmt->execute([
        'vnd_amount' => $vndAmount,
        'gold_amount' => $goldAmount,
        'id' => $_Id,
    ]);

    if ($stmt->rowCount() < 1) {
        $conn->rollBack();
        webJsonResponse(['success' => false, 'message' => 'Số dư không đủ để thực hiện giao dịch.'], 422);
    }

    $conn->commit();
    webJsonResponse(['success' => true, 'message' => 'Đổi thành công ' . webFormatNumber($goldAmount) . ' thỏi.']);
} catch (Throwable $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('Gold exchange failed: ' . $e->getMessage());
    webJsonResponse(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'], 500);
}
