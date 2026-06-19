<?php
require_once '../../Controllers/Connections.php';
require_once '../../Controllers/Configs.php';

$jsonBody = json_decode(file_get_contents('php://input'));
$log = '';

function writeToLog($message)
{
    file_put_contents(__DIR__ . '/nduckien.log', $message . ' | ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
}

if (
    isset($jsonBody->callback_sign, $jsonBody->code, $jsonBody->serial, $jsonBody->status) &&
    $jsonBody->callback_sign === md5($Partner_Key . $jsonBody->code . $jsonBody->serial)
) {
    $stmt = $conn->prepare("SELECT * FROM napthe WHERE code = :code AND serial = :serial LIMIT 1");
    $stmt->execute([
        'code' => $jsonBody->code,
        'serial' => $jsonBody->serial,
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $userNap = $row['user_nap'];
        $price = ((int) $row['amount']) * (int) $_GiaTri;

        $statusUpdate = $conn->prepare("UPDATE napthe SET status = :status WHERE id = :id");
        $statusUpdate->execute([
            'status' => (int) $jsonBody->status,
            'id' => $row['id'],
        ]);

        if ((int) $jsonBody->status === 1) {
            $accountUpdate = $conn->prepare("UPDATE account SET vnd = vnd + :price, tongnap = tongnap + :price WHERE username = :username");
            $accountUpdate->execute([
                'price' => $price,
                'username' => $userNap,
            ]);
            $log = "Thanh cong (User: {$userNap} | vnd: " . formatMoney($price) . ", tongnap: " . formatMoney($price) . ')';
        } else {
            $log = "The that bai (User: {$userNap} | status: {$jsonBody->status})";
        }
    } else {
        $log = 'Khong tim thay giao dich the.';
    }
} else {
    $log = 'Callback khong hop le.';
}

writeToLog($log);
echo 'OK';
