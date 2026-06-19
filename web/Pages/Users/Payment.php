<?php include '../../Controllers/Header.php'; ?>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Nạp thẻ cào</div>
            <form onsubmit="event.preventDefault(); postCard();">
                <div class="mb-3">
                    <label class="form-label">Nhà mạng</label>
                    <select class="form-select" id="telco" required>
                        <option value="">Chọn nhà mạng</option>
                        <option value="VIETTEL">Viettel</option>
                        <option value="VINAPHONE">Vinaphone</option>
                        <option value="MOBIFONE">Mobifone</option>
                        <option value="GATE">Gate</option>
                        <option value="ZING">Zing</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mệnh giá</label>
                    <select class="form-select" id="amount" required>
                        <option value="">Chọn mệnh giá</option>
                        <option value="10000">10.000</option>
                        <option value="20000">20.000</option>
                        <option value="50000">50.000</option>
                        <option value="100000">100.000</option>
                        <option value="200000">200.000</option>
                        <option value="500000">500.000</option>
                        <option value="1000000">1.000.000</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mã thẻ</label>
                    <input class="form-control" id="code" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Serial</label>
                    <input class="form-control" id="serial" required>
                </div>
                <button class="btn btn-primary" type="submit">Gửi thẻ</button>
            </form>
            <div class="mt-3">
                <a href="/Users/History" class="btn btn-outline-secondary btn-sm">Xem lịch sử nạp</a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="site-panel p-3 h-100">
            <div class="h5 mb-3">Chuyển khoản ngân hàng</div>
            <table class="table">
                <tr><th>Ngân hàng</th><td><?= htmlspecialchars($_mbbank, ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><th>Số tài khoản</th><td><?= htmlspecialchars($stkmbbank_config, ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><th>Chủ tài khoản</th><td><?= htmlspecialchars($mbbank_name, ENT_QUOTES, 'UTF-8') ?></td></tr>
                <tr><th>Nội dung</th><td><strong>naptien <?= (int) $_Id ?></strong></td></tr>
            </table>
            <div class="small text-muted mb-3">Chuyển đúng nội dung để cron ngân hàng tự cộng tiền vào `account.vnd` và `account.tongnap`.</div>
            <?php if (!empty($stkmbbank_config) && !empty($mbbank_name) && $stkmbbank_config !== '123456789') { ?>
                <img
                    src="https://img.vietqr.io/image/MBBANK-<?= rawurlencode($stkmbbank_config) ?>-compact2.png?amount=0&addInfo=<?= rawurlencode('naptien ' . $_Id) ?>&accountName=<?= rawurlencode($mbbank_name) ?>"
                    alt="QR ngân hàng"
                    style="max-width: 280px; width: 100%; border-radius: 8px;"
                >
            <?php } ?>
        </div>
    </div>
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
