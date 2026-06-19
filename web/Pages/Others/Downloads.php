<?php
include '../../Controllers/Header.php';

$downloads = [
    [
        'title' => 'Android',
        'desc' => 'File APK cho điện thoại Android.',
        'href' => $_Android,
        'icon' => '/Assets/Images/taigamengay.png',
        'meta' => 'APK',
    ],
    [
        'title' => 'iPhone / iPad',
        'desc' => 'File IPA cho thiết bị iOS.',
        'href' => $_Iphone,
        'icon' => '/Assets/Images/trangchu1.png',
        'meta' => 'IPA',
    ],
    [
        'title' => 'Windows',
        'desc' => 'Bản chơi trên máy tính Windows.',
        'href' => $_Windows,
        'icon' => '/Assets/Images/active.png',
        'meta' => 'PC',
    ],
    [
        'title' => 'Java',
        'desc' => 'Bản JAR dùng cho môi trường Java.',
        'href' => $_Java2 ?: $_Java,
        'icon' => '/Assets/Images/logo.gif',
        'meta' => 'JAR',
    ],
];
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2 class="panel-title">Tải game</h2>
            <p class="panel-subtitle">Chọn đúng nền tảng của bạn. Các nút tải dùng link trong Controllers/Configs.php.</p>
        </div>
    </div>
    <div class="panel-body">
        <div class="download-grid">
            <?php foreach ($downloads as $item) { ?>
                <article class="download-card">
                    <img class="card-icon" src="<?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                    <div>
                        <div class="download-meta">
                            <span class="badge-soft"><?= htmlspecialchars($item['meta'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= htmlspecialchars($item['desc'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <?php if (!empty($item['href']) && $item['href'] !== 'Nhập link' && $item['href'] !== 'Nhập link tải jar') { ?>
                        <a class="btn btn-primary" href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>">Tải ngay</a>
                    <?php } else { ?>
                        <button class="btn btn-secondary" type="button" disabled>Chưa có link</button>
                    <?php } ?>
                </article>
            <?php } ?>
        </div>
    </div>
</section>

<?php include '../../Controllers/Footer.php'; ?>
