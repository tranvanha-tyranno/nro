<?php include '../../Controllers/Header.php'; ?>

<?php
$rewards = [
    ['milestone' => '20.000đ', 'reward' => 'Danh hiệu Fan Cứng'],
    ['milestone' => '50.000đ', 'reward' => 'Thiên Kiếm'],
    ['milestone' => '100.000đ', 'reward' => 'Thú cưỡi Thanh Long'],
    ['milestone' => '200.000đ', 'reward' => 'Katana'],
    ['milestone' => '500.000đ', 'reward' => 'Đồ Long Đao'],
    ['milestone' => '1.000.000đ', 'reward' => 'Danh hiệu Đại Gia'],
];
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Mốc nạp</h2>
            <p class="panel-subtitle">Danh sách quà thưởng theo tổng nạp. Admin có thể chỉnh nội dung trực tiếp trong file này.</p>
        </div>
        <a class="btn btn-primary" href="<?= $_Login ? '/Users/Payment' : '/Auth/Login' ?>">Nạp tiền</a>
    </div>
    <div class="panel-body">
        <div class="reward-grid">
            <?php foreach ($rewards as $item) { ?>
                <div class="info-card">
                    <span>Mốc nạp</span>
                    <strong><?= htmlspecialchars($item['milestone'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <p class="help"><?= htmlspecialchars($item['reward'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include '../../Controllers/Footer.php'; ?>
