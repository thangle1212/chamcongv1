<?php
// admin/manual_checkin_logic.php
session_start();
require_once '../config.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") { header("location: ../login.php"); exit; }

$error = $success = "";
$all_employees = [];

// 1. LẤY DANH SÁCH NHÂN VIÊN
try {
    $sql = "SELECT id, name, employee_code FROM users"; 
    $all_employees = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $error = "Lỗi lấy danh sách nhân viên: " . $e->getMessage(); }

// 2. XỬ LÝ CHẤM CÔNG THỦ CÔNG KHI CÓ POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'manual_checkin') {
    $user_id = (int)$_POST['user_id'];
    $timestamp = $_POST['timestamp']; 
    $check_type = $_POST['check_type'];
    $admin_id = $_SESSION['id'];

    if (empty($user_id) || empty($timestamp) || empty($check_type)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else {
        try {
            $sql = "INSERT INTO attendance (user_id, timestamp, check_type, source, recorded_by_user_id) 
                    VALUES (?, ?, ?, 'MANUAL', ?)";
            $pdo->prepare($sql)->execute([$user_id, $timestamp, $check_type, $admin_id]);
            $success = "Chấm công thủ công thành công!";
        } catch (PDOException $e) { $error = "Lỗi ghi dữ liệu: " . $e->getMessage(); }
    }
}
?>