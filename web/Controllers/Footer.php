<?php $canActivate = $_Login !== null && (int) $_Status === 0; ?>
<?php if ($canActivate) { ?>
<div class="site-panel p-3 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <div class="fw-bold">Kích hoạt tài khoản</div>
            <div class="text-muted">Mở khóa giao dịch web với phí 10.000đ từ số dư hiện có.</div>
        </div>
        <button class="btn btn-success" onclick="activateAccount()">Kích hoạt ngay</button>
    </div>
</div>
<?php } ?>

<footer class="text-center py-4">
    <div class="small text-muted">Chơi quá 180 phút mỗi ngày sẽ ảnh hưởng sức khỏe.</div>
</footer>
</div>

<script>
function showCustomToast(message, type) {
    const toast = document.getElementById('customToast');
    toast.textContent = message;
    toast.style.display = 'block';
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.padding = '12px 16px';
    toast.style.borderRadius = '8px';
    toast.style.color = '#fff';
    toast.style.background = type === 'success' ? '#198754' : '#dc3545';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 2500);
}

function activateAccount() {
    fetch('/Api/Active', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        showCustomToast(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            setTimeout(() => window.location.reload(), 800);
        }
    })
    .catch(() => showCustomToast('Không thể kích hoạt lúc này.', 'error'));
}
</script>
</body>
</html>
