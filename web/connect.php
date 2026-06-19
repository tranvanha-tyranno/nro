<?php
$ip_sv = getenv('DB_HOST') ?: "127.0.0.1";
$dbname_sv = getenv('DB_NAME') ?: "nro";
$user_sv = getenv('DB_USER') ?: "root";
$pass_sv = getenv('DB_PASSWORD') ?: "";
$web_port = getenv('WEB_PORT') ?: "80";
$_domain = getenv('APP_DOMAIN') ?: ("http://127.0.0.1" . ($web_port !== "80" ? ":" . $web_port : "") . "/");
//GMT +7
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Create connection
$conn = new mysqli($ip_sv, $user_sv, $pass_sv, $dbname_sv);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
