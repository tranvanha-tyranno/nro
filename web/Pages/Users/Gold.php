<?php
include_once '../../Controllers/Header.php';

$goldRate = max(1, (int) ($_ThoiVangRate ?? 1));
$rechargeData = [
    ['amount' => 10000, 'gold' => 50],
    ['amount' => 20000, 'gold' => 100],
    ['amount' => 30000, 'gold' => 150],
    ['amount' => 50000, 'gold' => 250],
    ['amount' => 100000, 'gold' => 500],
    ['amount' => 200000, 'gold' => 1000],
    ['amount' => 500000, 'gold' => 2500],
    ['amount' => 1000000, 'gold' => 5000],
    ['amount' => 2000000, 'gold' => 10000],
];
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Đổi thỏi vàng</h2>
            <p class="panel-subtitle">Chọn mốc muốn đổi. Hệ thống sẽ kiểm tra số dư và cộng thỏi vàng theo bảng cố định trên server.</p>
        </div>
        <div class="badge-soft">Khuyến mãi x<?= $goldRate ?></div>
    </div>
    <div class="panel-body stack">
        <div class="summary-grid">
            <div class="stat-card">
                <label>Số dư hiện tại</label>
                <strong><?= webFormatCurrency($_BalanceVnd) ?></strong>
            </div>
            <div class="stat-card">
                <label>Thỏi vàng đang có</label>
                <strong><?= webFormatNumber($_ThoiVang) ?></strong>
            </div>
        </div>

        <div id="selectedAmountMessage" class="callout callout-info">Hãy chọn một mốc đổi bên dưới.</div>
        <div id="rechargeMethods" class="rate-grid">
            <?php foreach ($rechargeData as $item) {
                $finalGold = $item['gold'] * $goldRate;
            ?>
                <button class="rate-card" type="button" data-amount="<?= (int) $item['amount'] ?>">
                    <span class="amount"><?= webFormatCurrency($item['amount']) ?></span>
                    <strong>Nhận <?= webFormatNumber($finalGold) ?> thỏi</strong>
                    <span>Quy đổi từ số dư web sang tài nguyên game.</span>
                </button>
            <?php } ?>
        </div>

        <div class="action-row">
            <button id="Quydoi" type="button" class="btn btn-primary">Xác nhận đổi</button>
            <a class="btn btn-secondary" href="/Users/Payment">Nạp thêm số dư</a>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let selectedAmount = null;

    document.querySelectorAll(".rate-card").forEach(function (button) {
        button.addEventListener("click", function () {
            selectedAmount = Number(button.dataset.amount);
            document.querySelectorAll(".rate-card").forEach((item) => item.classList.remove("active"));
            button.classList.add("active");
            document.getElementById("selectedAmountMessage").textContent = "Bạn đang chọn mốc " + formatAmount(selectedAmount) + "đ.";
        });
    });

    document.getElementById("Quydoi").addEventListener("click", function () {
        if (!selectedAmount) {
            showCustomToast("Vui lòng chọn một mốc đổi.", "error");
            return;
        }

        fetch('/Api/Gold', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ vnd_amount: selectedAmount })
        })
        .then(response => response.json())
        .then(data => {
            showCustomToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                setTimeout(() => window.location.reload(), 900);
            }
        })
        .catch(() => showCustomToast('Có lỗi xảy ra. Vui lòng thử lại sau.', 'error'));
    });

    function formatAmount(amount) {
        return amount.toLocaleString('vi-VN');
    }
});
</script>

<?php include_once '../../Controllers/Footer.php'; ?>
