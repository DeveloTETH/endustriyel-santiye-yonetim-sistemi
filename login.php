<?php
session_start();
include("config.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT id, username, password, role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    $count = mysqli_num_rows($result);
    
    if($count == 1 && password_verify($password, $row['password'])) {
        $_SESSION['login_user'] = $username;
        $_SESSION['user_role'] = $row['role'];
        header("location: index.php");
    } else {
        $error = "Kullanıcı adı veya şifre geçersiz!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Şantiye Yönetim - Giriş</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Şantiye Yönetim Sistemi</h2>
        <form action="" method="post">
            <div class="form-group">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Şifre:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Giriş Yap">
            </div>
            <?php if(isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        </form>
    </div>
</body>
</html>