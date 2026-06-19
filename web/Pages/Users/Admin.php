<?php
include '../../Controllers/Header.php';

if ((int) $_Admin !== 1) {
    header('Location: /');
    exit;
}

$alert = null;
$selectedUser = null;

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
            $balanceVnd = max(0, (int) ($_POST['balance_vnd'] ?? 0));
            $tongnap = max(0, (int) ($_POST['tongnap'] ?? 0));
            $thoiVang = max(0, (int) ($_POST['thoi_vang'] ?? 0));
            $active = isset($_POST['active']) ? 1 : 0;
            $ban = isset($_POST['ban']) ? 1 : 0;
            $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

            $stmt = $conn->prepare("
                UPDATE account
                SET vnd = :vnd,
                    tongnap = :tongnap,
                    thoi_vang = :thoi_vang,
                    active = :active,
                    ban = :ban,
                    is_admin = :is_admin,
                    update_time = :update_time
                WHERE id = :id
            ");

            $params = [
                'vnd' => $balanceVnd,
                'tongnap' => $tongnap,
                'thoi_vang' => $thoiVang,
                'active' => $active,
                'ban' => $ban,
                'is_admin' => $isAdmin,
                'update_time' => date('Y-m-d H:i:s'),
                'id' => $selectedUser['id'],
            ];

            try {
                $stmt->execute($params);
                $selectedUser = webCurrentUserState($conn, $username);
                $alert = ['type' => 'success', 'message' => 'Đã cập nhật tài khoản thành công.'];
            } catch (Throwable $e) {
                $alert = ['type' => 'danger', 'message' => 'Cập nhật thất bại: ' . $e->getMessage()];
            }
        }
    }
}

$recentAccounts = $conn->query("
    SELECT id, username, active, is_admin, ban, vnd, tongnap, create_time
    FROM account
    ORDER BY id DESC
    LIMIT 12
")->fetchAll(PDO::FETCH_ASSOC);

$stats = [
    'accounts' => (int) $conn->query("SELECT COUNT(*) FROM account")->fetchColumn(),
    'players' => webTableExists($conn, 'player') ? (int) $conn->query("SELECT COUNT(*) FROM player")->fetchColumn() : 0,
    'active_accounts' => (int) $conn->query("SELECT COUNT(*) FROM account WHERE active = 1")->fetchColumn(),
    'total_topup' => (int) $conn->query("SELECT COALESCE(SUM(tongnap), 0) FROM account")->fetchColumn(),
];
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Tổng quan admin</div>
            <div class="status-grid">
                <div class="status-card">
                    <span class="status-label">Tài khoản</span>
                    <span class="status-value"><?= webFormatNumber($stats['accounts']) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Nhân vật</span>
                    <span class="status-value"><?= webFormatNumber($stats['players']) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Đã kích hoạt</span>
                    <span class="status-value"><?= webFormatNumber($stats['active_accounts']) ?></span>
                </div>
                <div class="status-card">
                    <span class="status-label">Tổng nạp</span>
                    <span class="status-value"><?= webFormatCurrency($stats['total_topup']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Tìm và sửa tài khoản</div>
            <?php if ($alert) { ?>
                <div class="alert alert-<?= $alert['type'] ?>"><?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php } ?>

            <form method="post" class="row g-2 mb-3">
                <input type="hidden" name="action" value="lookup">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="username" placeholder="Nhập username" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">Tìm tài khoản</button>
                </div>
            </form>

            <?php if ($selectedUser) { ?>
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($selectedUser['username'], ENT_QUOTES, 'UTF-8') ?>">

                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input class="form-control" value="<?= htmlspecialchars($selectedUser['username'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nhân vật</label>
                        <input class="form-control" value="<?= htmlspecialchars($selectedUser['player_name'] ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?>" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số dư `vnd`</label>
                        <input type="number" min="0" class="form-control" name="balance_vnd" value="<?= (int) $selectedUser['balance_vnd'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tổng nạp</label>
                        <input type="number" min="0" class="form-control" name="tongnap" value="<?= (int) $selectedUser['tongnap'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Thỏi vàng</label>
                        <input type="number" min="0" class="form-control" name="thoi_vang" value="<?= (int) $selectedUser['thoi_vang'] ?>">
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="active" id="active" <?= (int) $selectedUser['status'] === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">Đã kích hoạt</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="ban" id="ban" <?= (int) ($selectedUser['account']['ban'] ?? 0) === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="ban">Bị khóa/ban</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" <?= (int) $selectedUser['is_admin'] === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_admin">Quyền admin</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Lưu thay đổi</button>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>

    <div class="col-12">
        <div class="site-panel p-3">
            <div class="h5 mb-3">Tài khoản mới nhất</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
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
                            <td><?= (int) $account['active'] ? 'Có' : 'Không' ?></td>
                            <td><?= (int) $account['is_admin'] ? 'Có' : 'Không' ?></td>
                            <td><?= (int) $account['ban'] ? 'Có' : 'Không' ?></td>
                            <td><?= webFormatCurrency($account['vnd']) ?></td>
                            <td><?= webFormatCurrency($account['tongnap']) ?></td>
                            <td><?= htmlspecialchars($account['create_time'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../Controllers/Footer.php'; ?>
