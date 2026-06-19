<?php include '../../Controllers/Header.php'; ?>

<div class="auth-wrap">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Đổi mật khẩu</h2>
                <p class="panel-subtitle">Mật khẩu mới chỉ dùng chữ, số và dấu gạch dưới để khớp luật tài khoản hiện tại.</p>
            </div>
        </div>
        <div class="panel-body">
            <form class="form-grid" onsubmit="event.preventDefault(); ChangePass();">
                <div class="field">
                    <label for="current_password">Mật khẩu cũ</label>
                    <input id="current_password" class="input" type="password" name="current_password" placeholder="Mật khẩu hiện tại" minlength="3" required>
                </div>
                <div class="form-grid two-col">
                    <div class="field">
                        <label for="newpassword">Mật khẩu mới</label>
                        <input id="newpassword" class="input" type="password" name="newpassword" placeholder="Mật khẩu mới" minlength="3" required>
                    </div>
                    <div class="field">
                        <label for="newpassword_confirm">Xác nhận</label>
                        <input id="newpassword_confirm" class="input" type="password" name="newpassword_confirm" placeholder="Nhập lại mật khẩu mới" minlength="3" required>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Đổi mật khẩu</button>
            </form>
        </div>
    </section>
</div>

<script>
function ChangePass() {
    const current_password = document.querySelector("[name=current_password]").value;
    const newpassword = document.querySelector("[name=newpassword]").value;
    const newpassword_confirm = document.querySelector("[name=newpassword_confirm]").value;

    if (!passwordCheck(newpassword, newpassword_confirm)) {
        return;
    }

    fetch('/Api/Password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            current_password: current_password,
            newpassword: newpassword,
            newpassword_confirm: newpassword_confirm
        })
    })
    .then(response => response.json())
    .then(data => showCustomToast(data.message, data.success ? 'success' : 'error'))
    .catch(() => showCustomToast('Có lỗi xảy ra. Vui lòng thử lại sau.', 'error'));
}

function passwordCheck(newpassword, newpassword_confirm) {
    if (!/^[a-zA-Z0-9_]+$/.test(newpassword)) {
        showCustomToast('Mật khẩu mới chỉ được dùng chữ, số và dấu gạch dưới.', 'error');
        return false;
    }

    if (newpassword !== newpassword_confirm) {
        showCustomToast('Mật khẩu mới không khớp.', 'error');
        return false;
    }

    return true;
}
</script>

<?php include '../../Controllers/Footer.php'; ?>
