<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Mã hóa mật khẩu
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Đăng ký thành công! Hãy đăng nhập."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tên đăng nhập đã tồn tại!"]);
    }
}
?>