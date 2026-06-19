<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';

if ($_Login === null || $_Id === null) {
    echo '<div class="callout callout-danger">Bạn cần đăng nhập để xem lịch sử ATM.</div>';
    exit;
}

if (!webTableExists($conn, 'atm_lichsu')) {
    echo '<div class="callout callout-info">Chưa có bảng lịch sử ATM trong database.</div>';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM atm_lichsu WHERE user_nap = :id ORDER BY thoigian DESC LIMIT 10");
$stmt->execute(['id' => $_Id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo '<div class="callout callout-info">Không có dữ liệu lịch sử giao dịch ATM.</div>';
    exit;
}

function atmStatusLabel($status): array
{
    return match ((int) $status) {
        1 => ['status-ok', 'Đã thanh toán'],
        2 => ['status-off', 'Chưa thanh toán'],
        default => ['status-warn', 'Đang xử lý'],
    };
}
?>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Trạng thái</th>
        <th>Số tiền</th>
        <th>Mã giao dịch</th>
        <th>Ngày tháng</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $index => $row) {
        [$className, $label] = atmStatusLabel($row['status'] ?? 0);
    ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><span class="status-pill <?= $className ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span></td>
            <td><?= webFormatCurrency($row['sotien'] ?? 0) ?></td>
            <td><?= htmlspecialchars($row['magiaodich'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['thoigian'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
