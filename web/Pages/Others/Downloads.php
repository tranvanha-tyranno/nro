<?php
include '../../Controllers/Header.php';
?>

    
        
            <div class="card">
                <div class="card-body">
                    <div class="row">

                    <div class="row">
    <!-- iOS tf Download -->
  <!--  <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">iOS TESTFLIGHT(full)</h5>
                <p class="card-text">File IPA cho iPhone/iPad.</p>
                <a href="<?= $_IphoneTF ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>-->
    <!-- iOS Download -->
    <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">FILE .IPA</h5>
                <p class="card-text">File IPA cho iPhone/iPad.</p>
                <a href="<?= $_Iphone ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>
    <!-- iOS Download -->
  <!-- <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">FILE .IPA 2 </h5>
              <p class="card-text">File IPA cho iPhone/iPad.</p>
                <a href="<?= $_Iphone2 ?>" class="btn btn-danger mt-1">Tải Ngay</a>
 -->           </div>
        </div>
    </div>
    <!-- Android Download -->
    <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">Android Bản 1</h5>
                <p class="card-text">Tải về cho điện thoại Android của bạn.</p>
                <a href="<?= $_Android ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>

    <!-- Android Download -->
  <!--  <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">Android 2 (BẢN Dự phòng)</h5>
                <p class="card-text">Tải về cho điện thoại Android của bạn.</p>
                <a href="<?= $_Android2 ?>" class="btn btn-danger mt-1">Tải Ngay</a>
 -->           </div>
        </div>
    </div>

    <!-- PC Download -->
    <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">WINDOWS 1</h5>
                <p class="card-text">Tải về cho máy tính để bàn.</p>
                <a href="<?= $_Windows ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>
    <!-- PC Download -->
   <!-- <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">WINDOWS 2</h5>
                <p class="card-text">Tải về cho máy tính để bàn.</p>
                <a href="<?= $_Windows2 ?>" class="btn btn-danger mt-1">Tải Ngay</a>
    -->        </div>
        </div>
    </div>
    <!-- PC Download -->
  <!--  <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">Bản jar(driver)</h5>
                <p class="card-text">Tải về cho máy tính để bàn.</p>
                <a href="<?= $_Java ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>-->
    <!-- PC Download -->
  <!--  <div class="col-md-4 mt-1">
        <div class="card download-bg suggestion text-center">
            <div class="card-body">
                <h5 class="card-title">Bản jar(tải trực tiếp)</h5>
                <p class="card-text">Tải về cho máy tính để bàn.</p>
                <a href="<?= $_Java2 ?>" class="btn btn-danger mt-1">Tải Ngay</a>
            </div>
        </div>
    </div>-->
<!--<div class="modal fade" id="java" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="my-2">
                    <a href="<?= $_JavaX1 ?>"
                        class="btn btn-menu btn-danger w-100 fw-semibold my-1">JAVA X1</a>
                    <a href="<?= $_JavaX3 ?>"
                        class="btn btn-menu btn-danger w-100 fw-semibold my-1">JAVA X3</a>
                    <a href="<?= $_JavaX5 ?>"
                        class="btn btn-menu btn-danger w-100 fw-semibold my-1">JAVA X5</a>
                    <a href="<?= $_JavaX10 ?>"
                        class="btn btn-menu btn-danger w-100 fw-semibold my-1">JAVA X10</a>
                    <a href="<?= $_JavaX20 ?>"
                        class="btn btn-menu btn-danger w-100 fw-semibold my-1">JAVA X20</a>
                </div>
            </div>
        </div>
    </div>
</div>-->

<script>
    $(document).ready(function () {

        $('[data-bs-toggle="modal"]').click(function (e) {
            e.preventDefault(); // Ngăn chặn liên kết mở trang web khác

            var targetModal = $(this).attr('data-bs-target');

            $(targetModal).modal('show');
        });

        $('.modal .btn-close').click(function () {

            $(this).closest('.modal').modal('hide');
        });
    });
</script>
<?php
include '../../Controllers/Footer.php';
?>