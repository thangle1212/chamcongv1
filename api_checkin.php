<?php
// api_checkin.php
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $finger_id = isset($_POST['finger_id']) ? (int)$_POST['finger_id'] : 0;
    $check_type = isset($_POST['check_type']) && in_array(strtoupper($_POST['check_type']), ['IN', 'OUT']) ? strtoupper($_POST['check_type']) : 'IN';

    if ($finger_id == 0) { http_response_code(400); die(json_encode(['status' => 'error', 'message' => 'Missing finger_id'])); }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE finger_id = ?");
        $stmt->execute([$finger_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) { http_response_code(404); die(json_encode(['status' => 'error', 'message' => 'Finger ID not registered'])); }

        $user_id = $user['id'];
        
        $sql = "INSERT INTO attendance (user_id, timestamp, check_type, source) VALUES (?, NOW(), ?, 'DEVICE')";
        $pdo->prepare($sql)->execute([$user_id, $check_type]);

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Check-in recorded', 'user_id' => $user_id]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>