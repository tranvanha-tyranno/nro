<?php include '../../Controllers/Header.php'; ?>

<div class="panel-grid">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Nạp thẻ cào</h2>
                <p class="panel-subtitle">Nhập đúng nhà mạng, mệnh giá, mã thẻ và serial để hệ thống lưu yêu cầu xử lý.</p>
            </div>
            <span class="status-pill <?= (int) $_TrangThai === 1 ? 'status-ok' : 'status-off' ?>">
                <?= (int) $_TrangThai === 1 ? 'Đang mở' : 'Bảo trì' ?>
            </span>
        </div>
        <div class="panel-body stack">
            <form class="form-grid" onsubmit="event.preventDefault(); postCard();">
                <div class="form-grid two-col">
                    <div class="field">
                        <label for="telco">Nhà mạng</label>
                        <select class="select" id="telco" required>
                            <option value="">Chọn nhà mạng</option>
                            <option value="VIETTEL">Viettel</option>
                            <option value="VINAPHONE">Vinaphone</option>
                            <option value="MOBIFONE">Mobifone</option>
                            <option value="GATE">Gate</option>
                            <option value="ZING">Zing</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="amount">Mệnh giá</label>
                        <select class="select" id="amount" required>
                            <option value="">Chọn mệnh giá</option>
                            <option value="10000">10.000đ</option>
                            <option value="20000">20.000đ</option>
                            <option value="50000">50.000đ</option>
                            <option value="100000">100.000đ</option>
                            <option value="200000">200.000đ</option>
                            <option value="500000">500.000đ</option>
                            <option value="1000000">1.000.000đ</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="code">Mã thẻ</label>
                    <input class="input" id="code" autocomplete="off" required>
                </div>
                <div class="field">
                    <label for="serial">Serial</label>
                    <input class="input" id="serial" autocomplete="off" required>
                </div>
                <div class="action-row">
                    <button class="btn btn-primary" type="submit">Gửi thẻ</button>
                    <a href="/Users/History" class="btn btn-secondary">Xem lịch sử nạp</a>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Chuyển khoản ngân hàng</h2>
                <p class="panel-subtitle">Chuyển đúng nội dung để cron ngân hàng tự cộng tiền vào tài khoản.</p>
            </div>
        </div>
        <div class="panel-body stack">
            <div class="table-wrap">
                <table>
                    <tr><th>Ngân hàng</th><td><?= htmlspecialchars($_mbbank, ENT_QUOTES, 'UTF-8') ?></td></tr>
                    <tr><th>Số tài khoản</th><td><?= htmlspecialchars($stkmbbank_config, ENT_QUOTES, 'UTF-8') ?></td></tr>
                    <tr><th>Chủ tài khoản</th><td><?= htmlspecialchars($mbbank_name, ENT_QUOTES, 'UTF-8') ?></td></tr>
                    <tr><th>Nội dung</th><td><strong>naptien <?= (int) $_Id ?></strong></td></tr>
                </table>
            </div>
            <div class="callout callout-info">Sau khi giao dịch được cron xử lý, tiền sẽ cộng vào số dư web và tổng nạp.</div>
            <?php if (!empty($stkmbbank_config) && !empty($mbbank_name) && $stkmbbank_config !== '123456789') { ?>
                <div class="qr-box">
                    <img
                        src="https://img.vietqr.io/image/MBBANK-<?= rawurlencode($stkmbbank_config) ?>-compact2.png?amount=0&addInfo=<?= rawurlencode('naptien ' . $_Id) ?>&accountName=<?= rawurlencode($mbbank_name) ?>"
                        alt="QR ngân hàng"
                    >
                </div>
            <?php } else { ?>
                <div class="callout callout-danger">Thông tin ngân hàng vẫn đang dùng giá trị mẫu. Hãy cập nhật trong Configs.php trước khi nhận chuyển khoản thật.</div>
            <?php } ?>
        </div>
    </section>
</div>

<script>
function postCard() {
    fetch('/Api/Card', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            telco: document.getElementById('telco').value,
            amount: document.getElementById('amount').value,
            serial: document.getElementById('serial').value,
            code: document.getElementById('code').value
        })
    })
    .then(response => response.json())
    .then(data => showCustomToast(data.message, data.success ? 'success' : 'error'))
    .catch(() => showCustomToast('Không gửi được yêu cầu nạp thẻ.', 'error'));
}
</script>

<?php include '../../Controllers/Footer.php'; ?>
