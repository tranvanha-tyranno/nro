<?php include 'Controllers/Header.php'; ?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Tổng quan máy chủ</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="status-card h-100">
                        <div class="fw-bold mb-1">Tài khoản và nhân vật</div>
                        <div class="text-muted small">Đăng ký, đăng nhập, xem hồ sơ, đổi mật khẩu và kiểm tra nhân vật đang gắn với tài khoản.</div>
                        <div class="mt-3">
                            <a href="<?= $_Login ? '/Users/Profile' : '/Auth/Register' ?>" class="btn btn-sm btn-danger"><?= $_Login ? 'Xem tài khoản' : 'Tạo tài khoản' ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="status-card h-100">
                        <div class="fw-bold mb-1">Nạp và giao dịch</div>
                        <div class="text-muted small">Nạp thẻ, chuyển khoản, xem lịch sử nạp và đổi số dư sang thỏi vàng dùng trong game.</div>
                        <div class="mt-3">
                            <a href="/Users/Payment" class="btn btn-sm btn-primary">Đi tới nạp tiền</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="status-card h-100">
                        <div class="fw-bold mb-1">Bảng xếp hạng</div>
                        <div class="text-muted small">Đọc trực tiếp từ dữ liệu game hiện tại: sức mạnh, tổng nạp và các mốc nhân vật nổi bật.</div>
                        <div class="mt-3">
                            <a href="/Others/Top" class="btn btn-sm btn-outline-danger">Xem BXH</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="status-card h-100">
                        <div class="fw-bold mb-1">Tải client</div>
                        <div class="text-muted small">Tập trung toàn bộ link Android, iPhone, Windows, Java về đúng một chỗ để người chơi tải nhanh.</div>
                        <div class="mt-3">
                            <a href="/Others/Downloads" class="btn btn-sm btn-outline-success">Tải game</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="site-panel p-3 mb-3">
            <div class="h5 mb-3">Liên kết nhanh</div>
            <div class="d-grid gap-2">
                <a href="/Others/Downloads" class="btn btn-outline-primary">Tải game</a>
                <a href="/Others/Top" class="btn btn-outline-primary">Bảng xếp hạng</a>
                <?php if ($_Login) { ?>
                    <a href="/Users/Profile" class="btn btn-outline-primary">Trang tài khoản</a>
                    <a href="/Users/History" class="btn btn-outline-primary">Lịch sử giao dịch</a>
                    <a href="/Users/Gold" class="btn btn-outline-primary">Đổi thỏi vàng</a>
                <?php } else { ?>
                    <a href="/Auth/Login" class="btn btn-outline-primary">Đăng nhập</a>
                    <a href="/Auth/Register" class="btn btn-outline-primary">Đăng ký</a>
                <?php } ?>
            </div>
        </div>

        <div class="site-panel p-3">
            <div class="h5 mb-3">Tình trạng liên kết</div>
            <ul class="mb-0">
                <li>Đọc tài khoản từ bảng `account`</li>
                <li>Đọc nhân vật từ bảng `player`</li>
                <li>Top sức mạnh lấy từ `player.data_point`</li>
                <li>Top nạp lấy từ `account.tongnap`</li>
                <li>Lịch sử thẻ lấy từ `napthe`</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'Controllers/Footer.php'; ?>
