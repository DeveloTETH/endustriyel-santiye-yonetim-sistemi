<?php
// Veritabanı Bağlantısı
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'santiye_yonetim');

// Bağlantı oluşturma
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Bağlantı kontrolü
if($conn === false){
    die("HATA: Bağlantı kurulamadı. " . mysqli_connect_error());
}
?>