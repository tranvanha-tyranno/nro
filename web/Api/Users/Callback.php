<?php
require_once '../../Controllers/Connections.php';
require_once '../../Controllers/GameData.php';
require_once '../../Controllers/Configs.php';

$jsonBody = json_decode(file_get_contents('php://input'));
$log = '';

function writeToLog($message)
{
    file_put_contents(__DIR__ . '/nduckien.log', $message . ' | ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
}

if (!isset($jsonBody->callback_sign, $jsonBody->code, $jsonBody->serial, $jsonBody->status)) {
    writeToLog('Callback thieu du lieu.');
    echo 'OK';
    exit;
}

$expectedSign = md5($Partner_Key . $jsonBody->code . $jsonBody->serial);
if (!hash_equals($expectedSign, (string) $jsonBody->callback_sign)) {
    writeToLog('Callback khong hop le.');
    echo 'OK';
    exit;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("SELECT * FROM napthe WHERE code = :code AND serial = :serial LIMIT 1 FOR UPDATE");
    $stmt->execute([
        'code' => $jsonBody->code,
        'serial' => $jsonBody->serial,
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $conn->rollBack();
        writeToLog('Khong tim thay giao dich the.');
        echo 'OK';
        exit;
    }

    $userNap = $row['user_nap'];
    $newStatus = (int) $jsonBody->status;
    $oldStatus = (int) ($row['status'] ?? 99);
    $price = ((int) $row['amount']) * max(1, (int) $_GiaTri);

    $statusUpdate = $conn->prepare("UPDATE napthe SET status = :status WHERE id = :id");
    $statusUpdate->execute([
        'status' => $newStatus,
        'id' => $row['id'],
    ]);

    if ($newStatus === 1 && $oldStatus !== 1) {
        $accountUpdate = $conn->prepare("UPDATE account SET vnd = vnd + :price, tongnap = tongnap + :price WHERE username = :username");
        $accountUpdate->execute([
            'price' => $price,
            'username' => $userNap,
        ]);
        $log = "Thanh cong (User: {$userNap} | cong: " . webFormatCurrency($price) . ')';
    } elseif ($newStatus === 1) {
        $log = "Callback lap lai, da cong truoc do (User: {$userNap})";
    } else {
        $log = "The that bai (User: {$userNap} | status: {$newStatus})";
    }

    $conn->commit();
} catch (Throwable $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $log = 'Callback loi: ' . $e->getMessage();
}

writeToLog($log);
echo 'OK';
