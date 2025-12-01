<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT id, username, password, highscore FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            echo json_encode([
                "status" => "success", 
                "user_id" => $row['id'],
                "username" => $row['username'],
                "highscore" => $row['highscore']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Sai mật khẩu!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Tài khoản không tồn tại!"]);
    }
}
?>