<?php
$canActivate = $_Login !== null && (int) $_Status === 0;
?>

<?php if ($_Login !== null) { ?>
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Tài khoản hiện tại</h2>
                <p class="panel-subtitle">Các thông tin hay dùng được đặt ở cuối trang để thao tác nhanh.</p>
            </div>
            <?php if ($canActivate) { ?>
                <button class="btn btn-primary" type="button" onclick="activateAccount()">Kích hoạt tài khoản</button>
            <?php } ?>
        </div>
        <div class="panel-body">
            <div class="summary-grid">
                <div class="stat-card">
                    <label>Tài khoản</label>
                    <strong><?= htmlspecialchars($_Username, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="stat-card">
                    <label>Nhân vật</label>
                    <strong><?= htmlspecialchars($_PlayerName ?: 'Chưa tạo', ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="stat-card">
                    <label>Số dư</label>
                    <strong><?= webFormatCurrency($_BalanceVnd) ?></strong>
                </div>
                <div class="stat-card">
                    <label>Tổng nạp</label>
                    <strong><?= webFormatCurrency($_TCoins) ?></strong>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<div class="footer-note">
    <div>Chơi quá 180 phút mỗi ngày sẽ ảnh hưởng sức khỏe.</div>
    <?php if (!empty($_Zalo) && strpos($_Zalo, 'http') === 0) { ?>
        <div style="margin-top:10px;">
            <a class="btn btn-secondary btn-sm" href="<?= htmlspecialchars($_Zalo, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noreferrer">Nhóm hỗ trợ Zalo</a>
        </div>
    <?php } ?>
</div>
</div>

<script>
function showCustomToast(message, type) {
    const toast = document.getElementById('customToast');
    if (!toast) {
        alert(message);
        return;
    }

    toast.textContent = message || 'Có lỗi xảy ra.';
    toast.className = type === 'success' ? 'success' : 'error';
    window.clearTimeout(window.__toastTimer);
    window.__toastTimer = window.setTimeout(function () {
        toast.className = 'hidden';
    }, 2800);
}

function activateAccount() {
    fetch('/Api/Active', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({})
    })
    .then((response) => response.json())
    .then((data) => {
        showCustomToast(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            setTimeout(() => window.location.reload(), 900);
        }
    })
    .catch(() => showCustomToast('Không thể kích hoạt lúc này.', 'error'));
}
</script>
</body>
</html>
