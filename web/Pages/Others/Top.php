<?php
include '../../Controllers/Header.php';

$powerRows = [];
$topupRows = [];
$topError = null;

try {
    if (webTableExists($conn, 'player') && webTableExists($conn, 'account')) {
        $banFilter = webHasAccountColumn($conn, 'ban') ? 'WHERE COALESCE(a.ban, 0) = 0' : '';
        $stmt = $conn->query("
            SELECT p.name, p.gender, CAST(JSON_UNQUOTE(JSON_EXTRACT(p.data_point, '$[1]')) AS UNSIGNED) AS power
            FROM player p
            INNER JOIN account a ON a.id = p.account_id
            $banFilter
            ORDER BY power DESC
            LIMIT 10
        ");
        $powerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->query("
            SELECT a.username, COALESCE(a.tongnap, 0) AS tongnap, p.name
            FROM account a
            LEFT JOIN player p ON p.account_id = a.id
            $banFilter
            ORDER BY tongnap DESC, a.id ASC
            LIMIT 10
        ");
        $topupRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    $topError = 'Không thể tải bảng xếp hạng lúc này.';
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Bảng xếp hạng</h2>
            <p class="panel-subtitle">Top sức mạnh và top tổng nạp lấy trực tiếp từ database game.</p>
        </div>
        <div class="segmented">
            <button class="top-switch active" type="button" data-target="power-pane">Sức mạnh</button>
            <button class="top-switch" type="button" data-target="topup-pane">Tổng nạp</button>
        </div>
    </div>
    <div class="panel-body">
        <?php if ($topError) { ?>
            <div class="callout callout-danger"><?= htmlspecialchars($topError, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>

        <div class="top-pane" id="power-pane">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Top</th>
                        <th>Nhân vật</th>
                        <th>Hành tinh</th>
                        <th>Sức mạnh</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($powerRows) { ?>
                        <?php foreach ($powerRows as $index => $row) { ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= ['Trái Đất', 'Namek', 'Xayda'][(int) $row['gender']] ?? 'Không rõ' ?></td>
                                <td><?= webFormatNumber($row['power']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="4">Chưa có dữ liệu xếp hạng.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="top-pane" id="topup-pane" style="display:none;">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Top</th>
                        <th>Tài khoản</th>
                        <th>Nhân vật</th>
                        <th>Tổng nạp</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($topupRows) { ?>
                        <?php foreach ($topupRows as $index => $row) { ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['name'] ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= webFormatCurrency($row['tongnap']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="4">Chưa có dữ liệu xếp hạng.</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.top-switch').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.top-pane').forEach((pane) => {
            pane.style.display = 'none';
        });
        document.getElementById(button.dataset.target).style.display = 'block';
        document.querySelectorAll('.top-switch').forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');
    });
});
</script>

<?php include '../../Controllers/Footer.php'; ?>
