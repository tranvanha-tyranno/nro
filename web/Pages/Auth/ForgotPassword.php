<?php include '../../Controllers/Header.php'; ?>

<div class="auth-wrap">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Lấy lại mật khẩu</h2>
                <p class="panel-subtitle">Nhập email đã gắn với tài khoản để nhận mật khẩu mới.</p>
            </div>
        </div>
        <div class="panel-body">
            <form class="form-grid" onsubmit="event.preventDefault(); sendEmail();">
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" class="input" type="email" name="email" placeholder="Nhập email của bạn" required>
                </div>
                <button class="btn btn-primary" type="submit">Gửi mật khẩu mới</button>
            </form>
        </div>
    </section>
</div>

<script>
function sendEmail() {
    const email = document.querySelector("[name=email]").value.trim();
    if (!email) {
        showCustomToast('Vui lòng nhập địa chỉ email.', 'error');
        return;
    }

    fetch('/Api/Email', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => showCustomToast(data.message, data.success ? 'success' : 'error'))
    .catch(() => showCustomToast('Có lỗi xảy ra. Vui lòng thử lại sau.', 'error'));
}
</script>

<?php include '../../Controllers/Footer.php'; ?>
