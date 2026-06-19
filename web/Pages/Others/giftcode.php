<?php
// Bao gồm các tệp cần thiết
include '../../Controllers/Header.php';
require_once '../../Controllers/Connections.php';
require_once '../../Controllers/Configs.php';
?>

<div class="card shadow-sm mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <!-- Hình ảnh minh họa -->
            <div class="post-image d-none d-sm-block me-3">
                <img src="../../Assets/Images/32075.png" alt="Game Image" class="img-fluid rounded">
            </div>

            <!-- Nội dung chi tiết -->
            <div class="post-detail flex-fill">
                <h5 class="fw-bold text-primary mb-3">
                    🎁 <span class="text-uppercase">Giftcode Open:</span> <span class="text-success">NRODAICHIEN, NRODAICHIEN2</span>, 
                    <span class="text-success">NRODAICHIEN3</span>, <span class="text-warning">NRODAICHIEN4</span>, 
                    <span class="text-danger">NRODAICHIEN5</span>,
                </h5>
                <div class="post-content">
                    <ul class="list-group">
                        <?php
                        // Danh sách giftcode
                        $giftcodes = [
                            "Khi tạo acc đã nhận trong hành trang:",
                            "n",
                            "- ✨ ",
                            "- 🐾 ",                        
                            "-  🐦‍🔥 ",                          
                            "  + 🥼1",
                            "  +  ",
							"  + 🛹 ",
                            
                        ];

                        // Hiển thị danh sách giftcode
                        foreach ($giftcodes as $index => $code) {
                            // Phân biệt dòng tiêu đề
                            if ($index === 0 || strpos($code, ":") !== false) {
                                echo "<li class='list-group-item bg-light fw-bold'>" . htmlspecialchars($code) . "</li>";
                            } else {
                                echo "<li class='list-group-item'>" . htmlspecialchars($code) . "</li>";
                            }
                        }
                        ?>
                    </ul>
                    <p class="mt-4 text-muted">
                        📢 <strong>Chú ý:</strong> Chúng tôi sẽ tiếp tục cập nhật thêm nhiều giftcode mới. 
                        <span class="text-primary">Chúc các bạn chơi game vui vẻ!</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Bao gồm Footer
include '../../Controllers/Footer.php';
?>
