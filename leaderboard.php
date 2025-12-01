<?php
include 'db.php';

$response = [
    "top5" => [],
    "user_rank" => null
];

// 1. Lấy danh sách Top 10
$sql = "SELECT username, highscore FROM users ORDER BY highscore DESC LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response['top5'][] = $row;
    }
}

// 2. Tính hạng của người dùng hiện tại (nếu có user_id gửi lên)
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Lấy điểm của user hiện tại
    $score_sql = "SELECT highscore FROM users WHERE id = ?";
    $stmt = $conn->prepare($score_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $score_result = $stmt->get_result();

    if ($my_row = $score_result->fetch_assoc()) {
        $my_score = $my_row['highscore'];

        // Đếm số người có điểm cao hơn mình
        $rank_sql = "SELECT COUNT(*) as rank_above FROM users WHERE highscore > ?";
        $stmt2 = $conn->prepare($rank_sql);
        $stmt2->bind_param("i", $my_score);
        $stmt2->execute();
        $rank_result = $stmt2->get_result();
        $rank_row = $rank_result->fetch_assoc();

        // Thứ hạng = Số người cao hơn + 1
        $response['user_rank'] = [
            "rank" => $rank_row['rank_above'] + 1,
            "highscore" => $my_score
        ];
    }
}

echo json_encode($response);
?>