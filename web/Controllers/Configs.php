<?php
#Nguyen Duc Kien - NgocRong9

$_Logo = 'logo123.gif'; // Thay tên + đuôi của Logo vào đây
$_Domain = 'nrodaichien.site';
$_Title = 'NRO - Trang Chủ';
// $_ServerName = 'NgọcRồng';
$_Description = 'Web đăng kí tải game';
$_Keyword = 'Nro, Nro, Nro Lậu, Ngọc Rồng, Ngọc Rồng Online, Chú Bé Rồng';
$_ForgotEmail = 'Email'; // Gmail Chạy Quên Mật Khẩu
$_ForgotPass = 'Password'; // Mật Khẩu Gmail Chạy Quên Mật Khẩu

#Tăng Giá Trị Đổi
$_GiaTri = '1'; // Nạp x1 -> x2 -> x3 (Thẻ Cào)
$_GiaTriAtm = '1'; // Chuyển Khoản x1 -> x2 -> x3
$_ThoiVang = '1';

$_TrangThai = '1'; // Hoạt Động = 1, Bảo Trì = 0 (Trạng Thái Nạp Tiền)
$_FixWeb = '0'; // Bảo Trì = 1, Không Bảo Trì = 0
$_AuthLog = '0'; // Bảo Trì = 1, Không Bảo Trì = 0

#Hỗ Trợ
$_Fanpage = 'Link Fanpage';
$_Zalo = 'https://zalo.me/g/hejpen904';
$_ZaloX1 = 'https://zalo.me/g/hejpen904';
//$_ZaloX2 = 'Box 2';
//$_ZaloX3 = 'Box 3';
//$_ZaloX4 = 'Box 4';


#---------------#
#Downloads
$_Android = '/download/NroDaiChien1.apk';
$_Android2 = '/download/NRODC2.apk';
$_Iphone = '/download/NRODaiChien1.ipa';
$_Iphone2 = '/download/NRODC2.ipa';
$_IphoneTF = 'Nhập link';
$_Windows = '/download/NRODaiChien1.rar';
$_Windows2 = '/download/NRODAICHIEN2.rar';
#-----------------------------------------#
$_Java = 'Nhập link tải jar';
$_Java2 = '/download/Nrodc.jar';


#Card
$Partner_Key = '123456789abcd';
$Partner_Id = '98765432';
$_ApiCard = 'https://thesieure.com/'; // Link Đại Lý Thẻ

#Atm - Mbbank
$userloginmbbank_config = 'Tài khoản'; // Tài khoản đăng nhập Mbbank của bạn tại https://online.mbbank.com.vn
$passmbbank_config = 'matkhau'; // Mật khẩu đăng nhập Mbbank của bạn tại https://online.mbbank.com.vn
$deviceIdCommon_goc_config = 'v76lfd4u-mbib-0000-0000-2024102913265406'; // Thay cái thông số mà bạn lấy được từ F12 vào đây
$stkmbbank_config = '123456789'; // Số tài khoản Mbbank
$mbbank_name = 'Nhập tên'; // Tên Tài khoản Mbbank
$_mbbank = 'Ngân Hàng MBBANK'; // Ngân hàng quân đội Mbbank
$_Token = '';
if (function_exists('webTableExists') && webTableExists($conn, 'cpanel')) {
    try {
        $_Token = ($conn->query("SELECT token FROM cpanel LIMIT 1")->fetchColumn()) ?: '';
    } catch (Throwable $e) {
        $_Token = '';
    }
}
function CreateToken()
{
    return md5(uniqid(rand(), true));
}

#Chặn truy cập vào xem Json, Dữ Liệu ở Api
function isLocalhost()
{
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}


