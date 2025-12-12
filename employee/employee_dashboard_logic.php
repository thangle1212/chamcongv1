<?php
// employee/employee_dashboard_logic.php
session_start();
require_once '../config.php'; 
if (!isset($_SESSION["loggedin"])) { header("location: ../login.php"); exit; }

$user_id = $_SESSION['id'];
$employee_name = $_SESSION['name']; 
$my_attendance = [];
$error = '';

// LẤY LỊCH SỬ CHẤM CÔNG CÁ NHÂN
try {
    $sql = "SELECT timestamp, check_type, source FROM attendance 
            WHERE user_id = ? ORDER BY timestamp DESC LIMIT 50";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $my_attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $error = "Lỗi truy vấn lịch sử chấm công: " . $e->getMessage(); }
?>