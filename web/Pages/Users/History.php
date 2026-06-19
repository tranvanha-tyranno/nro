<?php include '../../Controllers/Header.php'; ?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Lịch sử giao dịch</h2>
            <p class="panel-subtitle">Theo dõi thẻ cào và giao dịch ngân hàng gần nhất của tài khoản.</p>
        </div>
        <a class="btn btn-secondary" href="/Users/Payment">Nạp thêm</a>
    </div>
    <div class="panel-body stack">
        <div>
            <h3 class="panel-title">Lịch sử nạp thẻ</h3>
            <div id="napthe-history" class="table-wrap mt-3">
                <div class="callout callout-info">Đang tải lịch sử nạp thẻ...</div>
            </div>
        </div>

        <div>
            <h3 class="panel-title">Lịch sử giao dịch ATM</h3>
            <div id="atm-lichsu" class="table-wrap mt-3">
                <div class="callout callout-info">Đang tải lịch sử ATM...</div>
            </div>
        </div>
    </div>
</section>

<script>
function loadHistory(targetId, url) {
    fetch(url, { headers: { 'X-Requested-With': 'fetch' } })
        .then((response) => response.text())
        .then((html) => {
            document.getElementById(targetId).innerHTML = html;
        })
        .catch(() => {
            document.getElementById(targetId).innerHTML = '<div class="callout callout-danger">Không thể tải dữ liệu.</div>';
        });
}

document.addEventListener('DOMContentLoaded', function () {
    loadHistory('napthe-history', '/Api/CardHistory');
    loadHistory('atm-lichsu', '/Api/AtmHistory');
});
</script>

<?php include '../../Controllers/Footer.php'; ?>
