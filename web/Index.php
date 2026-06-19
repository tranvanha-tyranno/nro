<?php include 'Controllers/Header.php'; ?>

<div class="panel-grid">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Lối vào nhanh</h2>
                <p class="panel-subtitle">Các thao tác người chơi dùng nhiều nhất được gom lại để vào đúng màn chỉ sau một lần bấm.</p>
            </div>
        </div>
        <div class="panel-body">
            <div class="quick-grid">
                <div class="quick-card">
                    <img class="card-icon" src="/Assets/Images/taigamengay.png" alt="">
                    <strong>Tải game đúng nền tảng</strong>
                    <span>Chọn bản Android, iPhone, Windows hoặc Java từ một màn duy nhất.</span>
                    <div class="mt-3"><a class="btn btn-primary" href="/Others/Downloads">Mở trang tải game</a></div>
                </div>
                <div class="quick-card">
                    <img class="card-icon" src="/Assets/Images/Top.png" alt="">
                    <strong>Theo dõi bảng xếp hạng</strong>
                    <span>Xem top sức mạnh và top nạp lấy trực tiếp từ dữ liệu game.</span>
                    <div class="mt-3"><a class="btn btn-secondary" href="/Others/Top">Xem bảng xếp hạng</a></div>
                </div>
                <div class="quick-card">
                    <img class="card-icon" src="/Assets/Images/trangchu1.png" alt="">
                    <strong>Quản lý tài khoản</strong>
                    <span><?= $_Login ? 'Xem hồ sơ, đổi mật khẩu, kiểm tra số dư và trạng thái kích hoạt.' : 'Đăng nhập hoặc đăng ký nhanh để quản lý nhân vật và số dư.' ?></span>
                    <div class="mt-3">
                        <a class="btn btn-secondary" href="<?= $_Login ? '/Users/Profile' : '/Auth/Login' ?>"><?= $_Login ? 'Mở tài khoản' : 'Đăng nhập ngay' ?></a>
                    </div>
                </div>
                <div class="quick-card">
                    <img class="card-icon" src="/Assets/Images/napthengay.png" alt="">
                    <strong>Nạp tiền và đổi tài nguyên</strong>
                    <span>Nạp thẻ, chuyển khoản và đổi sang thỏi vàng trên giao diện dễ thao tác.</span>
                    <div class="mt-3"><a class="btn btn-secondary" href="<?= $_Login ? '/Users/Payment' : '/Auth/Login' ?>"><?= $_Login ? 'Mở nạp tiền' : 'Đăng nhập để nạp' ?></a></div>
                </div>
            </div>
        </div>
    </section>

    <section class="stack">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Tình trạng hiện tại</h2>
                    <p class="panel-subtitle">Tóm tắt nhanh trạng thái tài khoản trên web.</p>
                </div>
            </div>
            <div class="panel-body">
                <div class="stats-grid">
                    <div class="stat-card">
                        <label>Đăng nhập</label>
                        <strong><?= $_Login ? 'Đang online' : 'Khách' ?></strong>
                    </div>
                    <div class="stat-card">
                        <label>Kích hoạt</label>
                        <strong><?= $_Login ? ($_Status ? 'Đã mở' : 'Chưa mở') : 'Chưa có' ?></strong>
                    </div>
                    <div class="stat-card">
                        <label>Nhân vật</label>
                        <strong><?= htmlspecialchars($_PlayerName ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?></strong>
                    </div>
                    <div class="stat-card">
                        <label>Sức mạnh</label>
                        <strong><?= webFormatNumber($_PlayerPower ?? 0) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Thao tác nên dùng</h2>
                    <p class="panel-subtitle">Đi thẳng vào đúng nơi thay vì dò lại trong menu.</p>
                </div>
            </div>
            <div class="panel-body stack">
                <?php if ($_Login) { ?>
                    <a class="nav-link-card" href="/Users/History">
                        <strong>Xem lịch sử giao dịch</strong>
                        <span>Theo dõi nạp thẻ và giao dịch ngân hàng gần nhất.</span>
                    </a>
                    <a class="nav-link-card" href="/Users/Gold">
                        <strong>Đổi thỏi vàng</strong>
                        <span>Đổi số dư web sang tài nguyên trong game.</span>
                    </a>
                    <a class="nav-link-card" href="/Users/ChangePassword">
                        <strong>Đổi mật khẩu</strong>
                        <span>Cập nhật mật khẩu tài khoản nhanh và rõ trạng thái.</span>
                    </a>
                <?php } else { ?>
                    <a class="nav-link-card" href="/Auth/Register">
                        <strong>Tạo tài khoản mới</strong>
                        <span>Đăng ký nhanh, sau đó vào ngay khu người dùng.</span>
                    </a>
                    <a class="nav-link-card" href="/Auth/Login">
                        <strong>Đăng nhập lại</strong>
                        <span>Quay lại hồ sơ, nạp tiền và đổi thỏi vàng.</span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<?php include 'Controllers/Footer.php'; ?>
