<?php
include '../../Controllers/Connections.php';
include '../../Controllers/Sessions.php';
include '../../Controllers/Configs.php';

if (isset($_Username)) {
    $itemsPerPage = 5; // Số lượng bảng hiển thị trên mỗi trang
    $stmt = $conn->prepare("SELECT * FROM `napthe` WHERE user_nap = :username ORDER BY created_at DESC LIMIT $itemsPerPage");
    $stmt->bindParam(":username", $_Username);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="white-space: nowrap; text-align: center;">ID</th>
                <th style="white-space: nowrap; text-align: center;">Trạng Thái</th>
                <th style="white-space: nowrap; text-align: center;">Loại Thẻ</th>
                <th style="white-space: nowrap; text-align: center;">Mã Thẻ</th>
                <th style="white-space: nowrap; text-align: center;">Seri</th>
                <th style="white-space: nowrap; text-align: center;">Mệnh Giá</th>
                <th style="white-space: nowrap; text-align: center;">Thời Gian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($result) > 0) {
                echo '<div class="table-responsive">';
                foreach ($result as $row) {
                    $status = match ($row['status']) {
                        1 => 'Thẻ đúng',
                        2 => 'Thẻ sai',
                        3 => 'Thẻ lỗi',
                        default => 'Chờ Duyệt'
                    };
                    $formattedDate = date('H:i d/m/Y', strtotime($row['created_at']));
                    $formattedAmount = number_format($row['amount']) . 'đ';

                    ?>
                    <tr class="border-solid border border-transparent border-b-orange-300">
                        <td class="px-6 py-4 font-medium break-words" style="text-align: center;">
                            <span class="text-red-600 font-semibold text-base"><?= $row['id'] ?></span>
                        </td>
                        <td class="px-6 py-4 break-words" style="text-align: center; white-space: nowrap;">
                            <?php
                            switch ($row['status']) {
                                case 1:
                                    echo '<span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 10px;">Thẻ đúng</span>';
                                    break;
                                case 2:
                                    echo '<span style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 10px;">Thẻ sai</span>';
                                    break;
                                case 3:
                                    echo '<span style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 10px;">Thẻ lỗi</span>';
                                    break;
                                default:
                                    echo '<span style="background-color: #ffc107; color: #212529; padding: 5px 10px; border-radius: 10px;">Chờ Duyệt</span>';
                            }
                            ?>
                        </td>

                        <td class="px-6 py-4 break-words" style="text-align: center;"><?= $row['telco'] ?></td>
                        <td class="px-6 py-4 break-words" style="text-align: center;"><?= $row['code'] ?></td>
                        <td class="px-6 py-4 break-words" style="text-align: center;"><?= $row['serial'] ?></td>
                        <td class="px-6 py-4 break-words" style="text-align: center;"><?= $formattedAmount ?></td>
                        <td class="px-6 py-4 break-words" style="text-align: center; white-space: nowrap;">
                            <?= $formattedDate ?>
                        </td>

                    </tr>
                    <?php
                }
                echo '</tbody></table></div>';
            } else {
                echo '<div class="text-center">Không có dữ liệu lịch sử nạp thẻ.</div>';
            }
} else {
    echo '<div class="text-center">Bạn cần đăng nhập để xem lịch sử nạp thẻ.</div>';
}
?>