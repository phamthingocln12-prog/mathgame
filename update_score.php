<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_score = $_POST['score'];

    // Kiểm tra điểm cũ
    $check_sql = "SELECT highscore FROM users WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($new_score > $row['highscore']) {
        $update_sql = "UPDATE users SET highscore = ? WHERE id = ?";
        $stmt2 = $conn->prepare($update_sql);
        $stmt2->bind_param("ii", $new_score, $user_id);
        $stmt2->execute();
        echo json_encode(["status" => "success", "new_high" => true]);
    } else {
        echo json_encode(["status" => "success", "new_high" => false]);
    }
}
?>