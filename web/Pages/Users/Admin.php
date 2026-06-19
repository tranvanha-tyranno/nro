<?php
include '../../Controllers/Header.php';

if ((int) $_Admin !== 1) {
    header('Location: /');
    exit;
}

$alert = null;
$selectedUser = null;

function adminAccountSelectColumns(PDO $conn): string
{
    $columns = ['id', 'username'];
    foreach (['active', 'is_admin', 'admin', 'ban', 'vnd', 'tongnap', 'thoi_vang', 'create_time'] as $column) {
        if (webHasAccountColumn($conn, $column)) {
            $columns[] = $column;
        }
    }

    return implode(', ', array_unique($columns));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');

    if ($username === '' || !isValidInput($username)) {
        $alert = ['type' => 'danger', 'message' => 'Tên tài khoản không hợp lệ.'];
    } else {
        $selectedUser = webCurrentUserState($conn, $username);

        if ($selectedUser === null) {
            $alert = ['type' => 'danger', 'message' => 'Không tìm thấy tài khoản.'];
        } elseif ($action === 'save') {
            $updates = [];
            $params = ['id' => $selectedUser['id']];

            $editableColumns = [
                'vnd' => max(0, (int) ($_POST['balance_vnd'] ?? 0)),
                'tongnap' => max(0, (int) ($_POST['tongnap'] ?? 0)),
                'thoi_vang' => max(0, (int) ($_POST['thoi_vang'] ?? 0)),
                'active' => isset($_POST['active']) ? 1 : 0,
                'ban' => isset($_POST['ban']) ? 1 : 0,
                'is_admin' => isset($_POST['is_admin']) ? 1 : 0,
            ];

            foreach ($editableColumns as $column => $value) {
                if (webHasAccountColumn($conn, $column)) {
                    $updates[] = "$column = :$column";
                    $params[$column] = $value;
                }
            }

            if (webHasAccountColumn($conn, 'update_time')) {
                $updates[] = 'update_time = :update_time';
                $params['update_time'] = date('Y-m-d H:i:s');
            }

            if (!$updates) {
                $alert = ['type' => 'danger', 'message' => 'Không có cột nào có thể cập nhật trong bảng account.'];
            } else {
                try {
                    $stmt = $conn->prepare('UPDATE account SET ' . implode(', ', $updates) . ' WHERE id = :id');
                    $stmt->execute($params);
                    $selectedUser = webCurrentUserState($conn, $username);
                    $alert = ['type' => 'success', 'message' => 'Đã cập nhật tài khoản thành công.'];
                } catch (Throwable $e) {
                    $alert = ['type' => 'danger', 'message' => 'Cập nhật thất bại: ' . $e->getMessage()];
                }
            }
        }
    }
}

$recentAccounts = [];
$stats = [
    'accounts' => 0,
    'players' => 0,
    'active_accounts' => 0,
    'total_topup' => 0,
];

try {
    $recentAccounts = $conn->query("
        SELECT " . adminAccountSelectColumns($conn) . "
        FROM account
        ORDER BY id DESC
        LIMIT 12
    ")->fetchAll(PDO::FETCH_ASSOC);

    $stats['accounts'] = (int) $conn->query("SELECT COUNT(*) FROM account")->fetchColumn();
    $stats['players'] = webTableExists($conn, 'player') ? (int) $conn->query("SELECT COUNT(*) FROM player")->fetchColumn() : 0;
    $stats['active_accounts'] = webHasAccountColumn($conn, 'active') ? (int) $conn->query("SELECT COUNT(*) FROM account WHERE active = 1")->fetchColumn() : 0;
    $stats['total_topup'] = webHasAccountColumn($conn, 'tongnap') ? (int) $conn->query("SELECT COALESCE(SUM(tongnap), 0) FROM account")->fetchColumn() : 0;
} catch (Throwable $e) {
    $alert = $alert ?: ['type' => 'danger', 'message' => 'Không thể tải dữ liệu admin: ' . $e->getMessage()];
}
?>

<div class="panel-grid">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Tổng quan admin</h2>
                <p class="panel-subtitle">Các chỉ số nhanh của database game.</p>
            </div>
        </div>
        <div class="panel-body">
            <div class="status-grid">
                <div class="stat-card">
                    <label>Tài khoản</label>
                    <strong><?= webFormatNumber($stats['accounts']) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Nhân vật</label>
                    <strong><?= webFormatNumber($stats['players']) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Đã kích hoạt</label>
                    <strong><?= webFormatNumber($stats['active_accounts']) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Tổng nạp</label>
                    <strong><?= webFormatCurrency($stats['total_topup']) ?></strong>
                </div>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Tìm và sửa tài khoản</h2>
                <p class="panel-subtitle">Nhập username để xem và cập nhật số dư, tổng nạp, trạng thái.</p>
            </div>
        </div>
        <div class="panel-body stack">
            <?php if ($alert) { ?>
                <div class="callout <?= $alert['type'] === 'success' ? 'callout-success' : 'callout-danger' ?>"><?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php } ?>

            <form method="post" class="form-grid two-col">
                <input type="hidden" name="action" value="lookup">
                <div class="field">
                    <label for="lookup_username">Username</label>
                    <input id="lookup_username" type="text" class="input" name="username" placeholder="Nhập username" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="field">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary" type="submit">Tìm tài khoản</button>
                </div>
            </form>

            <?php if ($selectedUser) { ?>
                <form method="post" class="form-grid">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($selectedUser['username'], ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-grid two-col">
                        <div class="field">
                            <label>Username</label>
                            <input class="input" value="<?= htmlspecialchars($selectedUser['username'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>
                        <div class="field">
                            <label>Nhân vật</label>
                            <input class="input" value="<?= htmlspecialchars($selectedUser['player_name'] ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>
                    </div>

                    <div class="form-grid two-col">
                        <div class="field">
                            <label>Số dư vnd</label>
                            <input type="number" min="0" class="input" name="balance_vnd" value="<?= (int) $selectedUser['balance_vnd'] ?>">
                        </div>
                        <div class="field">
                            <label>Tổng nạp</label>
                            <input type="number" min="0" class="input" name="tongnap" value="<?= (int) $selectedUser['tongnap'] ?>">
                        </div>
                        <div class="field">
                            <label>Thỏi vàng</label>
                            <input type="number" min="0" class="input" name="thoi_vang" value="<?= (int) $selectedUser['thoi_vang'] ?>">
                        </div>
                    </div>

                    <div class="action-row">
                        <label><input type="checkbox" name="active" <?= (int) $selectedUser['status'] === 1 ? 'checked' : '' ?>> Đã kích hoạt</label>
                        <label><input type="checkbox" name="ban" <?= (int) ($selectedUser['account']['ban'] ?? 0) === 1 ? 'checked' : '' ?>> Bị khóa</label>
                        <label><input type="checkbox" name="is_admin" <?= (int) $selectedUser['is_admin'] === 1 ? 'checked' : '' ?>> Quyền admin</label>
                    </div>

                    <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                </form>
            <?php } ?>
        </div>
    </section>
</div>

<section class="panel mt-3">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Tài khoản mới nhất</h2>
            <p class="panel-subtitle">12 tài khoản được tạo gần đây nhất.</p>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Kích hoạt</th>
                    <th>Admin</th>
                    <th>Ban</th>
                    <th>Số dư</th>
                    <th>Tổng nạp</th>
                    <th>Tạo lúc</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recentAccounts as $account) { ?>
                    <tr>
                        <td><?= (int) $account['id'] ?></td>
                        <td><?= htmlspecialchars($account['username'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= (int) ($account['active'] ?? 0) ? 'Có' : 'Không' ?></td>
                        <td><?= (int) ($account['is_admin'] ?? $account['admin'] ?? 0) ? 'Có' : 'Không' ?></td>
                        <td><?= (int) ($account['ban'] ?? 0) ? 'Có' : 'Không' ?></td>
                        <td><?= webFormatCurrency($account['vnd'] ?? 0) ?></td>
                        <td><?= webFormatCurrency($account['tongnap'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($account['create_time'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php } ?>
                <?php if (!$recentAccounts) { ?>
                    <tr><td colspan="8">Chưa có dữ liệu.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../../Controllers/Footer.php'; ?>
