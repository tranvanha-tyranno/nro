<?php
include '../../Controllers/Header.php';

$powerRows = [];
$stmt = $conn->query("
    SELECT p.name, p.gender, CAST(JSON_EXTRACT(p.data_point, '$[1]') AS UNSIGNED) AS power
    FROM player p
    INNER JOIN account a ON a.id = p.account_id
    WHERE a.ban = 0
    ORDER BY power DESC
    LIMIT 10
");
$powerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$topupRows = [];
$stmt = $conn->query("
    SELECT a.username, a.tongnap, p.name
    FROM account a
    LEFT JOIN player p ON p.account_id = a.id
    WHERE a.ban = 0
    ORDER BY a.tongnap DESC, a.id ASC
    LIMIT 10
");
$topupRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="site-panel p-3">
    <div class="h4 mb-3">Bảng xếp hạng</div>

    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-danger top-switch" data-target="power-pane">Sức mạnh</button>
        <button class="btn btn-outline-danger top-switch" data-target="topup-pane">Tổng nạp</button>
    </div>

    <div>
        <div class="top-pane" id="power-pane">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>TOP</th>
                        <th>Nhân vật</th>
                        <th>Hành tinh</th>
                        <th>Sức mạnh</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($powerRows as $index => $row) { ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= ['Trái Đất', 'Namek', 'Xayda'][(int) $row['gender']] ?? 'Không rõ' ?></td>
                            <td><?= webFormatNumber($row['power']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="top-pane" id="topup-pane" style="display:none;">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>TOP</th>
                        <th>Tài khoản</th>
                        <th>Nhân vật</th>
                        <th>Tổng nạp</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($topupRows as $index => $row) { ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['name'] ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= webFormatCurrency($row['tongnap']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.top-switch').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.top-pane').forEach((pane) => {
            pane.style.display = 'none';
        });
        document.getElementById(button.dataset.target).style.display = 'block';
        document.querySelectorAll('.top-switch').forEach((btn) => {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        });
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-danger');
    });
});
</script>
<?php include '../../Controllers/Footer.php'; ?>
