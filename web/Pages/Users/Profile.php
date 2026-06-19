<?php include '../../Controllers/Header.php'; ?>

<div class="panel-grid">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Thông tin tài khoản</h2>
                <p class="panel-subtitle">Các thông tin quan trọng nhất được gom lại để nhìn vào là hiểu.</p>
            </div>
            <div class="status-pill <?= $_Status ? 'status-ok' : 'status-warn' ?>">
                <?= $_Status ? 'Đã kích hoạt' : 'Chưa kích hoạt' ?>
            </div>
        </div>
        <div class="panel-body stack">
            <div class="summary-grid">
                <div class="stat-card">
                    <label>ID tài khoản</label>
                    <strong><?= (int) $_Id ?></strong>
                </div>
                <div class="stat-card">
                    <label>Username</label>
                    <strong><?= htmlspecialchars($_Username, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="stat-card">
                    <label>Số dư</label>
                    <strong><?= webFormatCurrency($_BalanceVnd) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Tổng nạp</label>
                    <strong><?= webFormatCurrency($_TCoins) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Thỏi vàng</label>
                    <strong><?= webFormatNumber($_ThoiVang) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Điểm sự kiện</label>
                    <strong><?= webFormatNumber($_EventPoint) ?></strong>
                </div>
            </div>

            <div class="action-row">
                <a class="btn btn-primary" href="/Users/Payment">Mở nạp tiền</a>
                <a class="btn btn-secondary" href="/Users/History">Xem lịch sử</a>
                <a class="btn btn-secondary" href="/Users/Gold">Đổi thỏi vàng</a>
                <a class="btn btn-secondary" href="/Users/ChangePassword">Đổi mật khẩu</a>
            </div>
        </div>
    </section>

    <section class="stack">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Nhân vật gắn với tài khoản</h2>
                    <p class="panel-subtitle">Dữ liệu lấy trực tiếp từ bảng player của game server.</p>
                </div>
            </div>
            <div class="panel-body">
                <?php if ($_Player) { ?>
                    <div class="summary-grid">
                        <div class="stat-card">
                            <label>Tên nhân vật</label>
                            <strong><?= htmlspecialchars($_PlayerName, ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                        <div class="stat-card">
                            <label>Sức mạnh</label>
                            <strong><?= webFormatNumber($_PlayerPower) ?></strong>
                        </div>
                        <div class="stat-card">
                            <label>HP</label>
                            <strong><?= webFormatNumber(webJsonStat($_Player['data_point'] ?? '[]', 2)) ?></strong>
                        </div>
                        <div class="stat-card">
                            <label>KI</label>
                            <strong><?= webFormatNumber(webJsonStat($_Player['data_point'] ?? '[]', 4)) ?></strong>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="callout callout-info">Tài khoản này chưa có nhân vật trong game.</div>
                <?php } ?>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Nhắc nhanh</h2>
                    <p class="panel-subtitle">Một số lối vào người chơi thường cần sau khi đăng nhập.</p>
                </div>
            </div>
            <div class="panel-body stack">
                <a class="nav-link-card" href="/Others/Downloads">
                    <strong>Tải hoặc cập nhật client</strong>
                    <span>Mở trang tải game nếu bạn đang đăng nhập web từ máy mới.</span>
                </a>
                <a class="nav-link-card" href="/Others/Top">
                    <strong>Xem vị trí của mình</strong>
                    <span>Kiểm tra top sức mạnh và top nạp ngay trên web.</span>
                </a>
            </div>
        </div>
    </section>
</div>

<?php include '../../Controllers/Footer.php'; ?>
