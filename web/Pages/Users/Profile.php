<?php include '../../Controllers/Header.php'; ?>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Thông tin tài khoản</div>
            <table class="table mb-0">
                <tr><th>ID</th><td><?= (int) $_Id ?></td></tr>
                <tr><th>Tài khoản</th><td><?= htmlspecialchars($_Username, ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><th>Email</th><td><?= $_Email ? htmlspecialchars($_Email, ENT_QUOTES, 'UTF-8') : 'Chưa cập nhật' ?></td></tr>
                <tr><th>Số dư</th><td class="text-danger fw-bold"><?= webFormatCurrency($_BalanceVnd) ?></td></tr>
                <tr><th>Tổng nạp</th><td class="text-danger fw-bold"><?= webFormatCurrency($_TCoins) ?></td></tr>
                <tr><th>Thỏi vàng</th><td><?= webFormatNumber($_ThoiVang) ?></td></tr>
                <tr><th>Trạng thái</th><td><?= $_Status ? 'Đã kích hoạt' : 'Chưa kích hoạt' ?></td></tr>
            </table>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <a href="/Users/ChangePassword" class="btn btn-outline-primary">Đổi mật khẩu</a>
                <a href="/Users/History" class="btn btn-outline-secondary">Lịch sử giao dịch</a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Thông tin nhân vật</div>
            <?php if ($_Player) { ?>
                <table class="table mb-0">
                    <tr><th>Tên nhân vật</th><td><?= htmlspecialchars($_PlayerName, ENT_QUOTES, 'UTF-8') ?></td></tr>
                    <tr><th>ID nhân vật</th><td><?= (int) $_Player['id'] ?></td></tr>
                    <tr><th>Sức mạnh</th><td><?= webFormatNumber($_PlayerPower) ?></td></tr>
                    <tr><th>HP</th><td><?= webFormatNumber(webJsonStat($_Player['data_point'] ?? '[]', 2)) ?></td></tr>
                    <tr><th>KI</th><td><?= webFormatNumber(webJsonStat($_Player['data_point'] ?? '[]', 4)) ?></td></tr>
                    <tr><th>Giới tính</th><td><?= ['Trái Đất', 'Namek', 'Xayda'][$_Player['gender'] ?? 0] ?? 'Không rõ' ?></td></tr>
                    <tr><th>Tạo lúc</th><td><?= htmlspecialchars($_Player['create_time'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td></tr>
                </table>
            <?php } else { ?>
                <div class="text-muted">Tài khoản này chưa có nhân vật trong game.</div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include '../../Controllers/Footer.php'; ?>
