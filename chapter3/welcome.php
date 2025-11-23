<?php
// TODO 1: Khởi động session
session_start();

// TODO 2: Kiểm tra SESSION có tồn tại không
if (isset($_SESSION['logged_user'])) {

    // TODO 3: Lấy username từ session
    $loggedInUser = $_SESSION['logged_user'];

    // TODO 4: In ra lời chào
    echo "<h1>Chào mừng trở lại, $loggedInUser!</h1>";
    echo "<p>Bạn đã đăng nhập thành công.</p>";

    // TODO 5: Link đăng xuất
    echo '<a href="login.html">Đăng xuất (tạm thời)</a>';

} else {

    // TODO 6: Chưa đăng nhập → đưa về login
    header('Location: login.html');
    exit;
}
?>
