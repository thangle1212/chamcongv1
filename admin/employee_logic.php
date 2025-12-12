<?php
// admin/employee_logic.php
session_start();
require_once '../config.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") { header("location: ../login.php"); exit; }

$error = $success = "";
$employees = [];

// XỬ LÝ CRUD POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        try {
            // Xử lý Xóa
            if ($action == 'delete' && isset($_POST['id'])) {
                $pdo->beginTransaction();
                // Xóa bản ghi liên quan trong attendance trước
                $pdo->prepare("DELETE FROM attendance WHERE user_id = ?")->execute([$_POST['id']]);
                // Sau đó xóa user
                $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_POST['id']]);
                $pdo->commit();
                $success = "Xóa nhân viên thành công!";
            } 
            
            // Xử lý Thêm hoặc Sửa
            else if ($action == 'add' || $action == 'edit') {
                $name = trim($_POST['name']); 
                $code = trim($_POST['employee_code']); 
                $password = $_POST['password']; 
                $finger_id = !empty($_POST['finger_id']) ? (int)$_POST['finger_id'] : NULL; 
                $role_id = ($_POST['role'] == 'admin') ? 1 : 2; 

                // THÊM MỚI (ADD)
                if ($action == 'add') {
                    if (empty($password)) throw new Exception("Mật khẩu không được để trống.");
                    $sql = "INSERT INTO users (role_id, employee_code, name, password, finger_id) VALUES (?, ?, ?, ?, ?)";
                    $pdo->prepare($sql)->execute([$role_id, $code, $name, $password, $finger_id]);
                    $success = "Thêm nhân viên thành công!";
                } 
                
                // CẬP NHẬT (EDIT) - KHẮC PHỤC LỖI HY093 TẠI ĐÂY
                else if ($action == 'edit' && isset($_POST['id'])) {
                    $user_id = $_POST['id'];
                    
                    // Khởi tạo các phần SQL và tham số CỐ ĐỊNH
                    $sql_parts = ["role_id=?", "employee_code=?", "name=?", "finger_id=?"];
                    $params = [$role_id, $code, $name, $finger_id]; 

                    // Kiểm tra và thêm MẬT KHẨU (Nếu có)
                    if (!empty($password)) {
                        $sql_parts[] = "password=?";
                        $params[] = $password; 
                    }

                    // Xây dựng câu lệnh SQL cuối cùng
                    // Nối các trường UPDATE lại, sau đó thêm điều kiện WHERE
                    $sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id=?";
                    
                    // Thêm ID người dùng vào cuối cùng để khớp với WHERE id=?
                    $params[] = $user_id; 

                    // Thực thi (Số lượng dấu ? luôn bằng số lượng biến trong $params)
                    $pdo->prepare($sql)->execute($params);
                    $success = "Cập nhật nhân viên thành công!";
                }
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            if ($e->getCode() == '23000') $error = "Lỗi: Mã NV hoặc ID vân tay đã tồn tại!";
            else $error = "Lỗi xử lý DB: " . $e->getMessage();
        } catch (Exception $e) { $error = $e->getMessage(); }
    }
}

// LẤY DANH SÁCH NHÂN VIÊN
try {
    $sql = "SELECT u.id, u.employee_code, u.name, u.finger_id, r.name AS role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id DESC";
    $employees = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $error = "Lỗi truy vấn danh sách: " . $e->getMessage(); }
?>