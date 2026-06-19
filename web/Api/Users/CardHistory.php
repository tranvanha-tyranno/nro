<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

if ($_Login === null || $_Username === null) {
    echo '<div class="callout callout-danger">Bạn cần đăng nhập để xem lịch sử nạp thẻ.</div>';
    exit;
}

if (!webTableExists($conn, 'napthe')) {
    echo '<div class="callout callout-info">Chưa có bảng lịch sử nạp thẻ trong database.</div>';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM napthe WHERE user_nap = :username ORDER BY created_at DESC, id DESC LIMIT 10");
$stmt->execute(['username' => $_Username]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo '<div class="callout callout-info">Không có dữ liệu lịch sử nạp thẻ.</div>';
    exit;
}

function cardStatusLabel($status): array
{
    return match ((int) $status) {
        1 => ['status-ok', 'Thẻ đúng'],
        2 => ['status-off', 'Thẻ sai'],
        3 => ['status-off', 'Thẻ lỗi'],
        default => ['status-warn', 'Chờ duyệt'],
    };
}
?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Trạng thái</th>
        <th>Loại thẻ</th>
        <th>Mã thẻ</th>
        <th>Serial</th>
        <th>Mệnh giá</th>
        <th>Thời gian</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row) {
        [$className, $label] = cardStatusLabel($row['status'] ?? 99);
        $createdAt = !empty($row['created_at']) ? date('H:i d/m/Y', strtotime($row['created_at'])) : '-';
    ?>
        <tr>
            <td><?= (int) $row['id'] ?></td>
            <td><span class="status-pill <?= $className ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span></td>
            <td><?= htmlspecialchars($row['telco'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['code'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['serial'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= webFormatCurrency($row['amount'] ?? 0) ?></td>
            <td><?= htmlspecialchars($createdAt, ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
