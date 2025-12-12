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
            if ($action == 'delete' && isset($_POST['id'])) {
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM attendance WHERE user_id = ?")->execute([$_POST['id']]);
                $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_POST['id']]);
                $pdo->commit();
                $success = "Xóa nhân viên thành công!";
            } else if ($action == 'add' || $action == 'edit') {
                $name = trim($_POST['name']); $code = trim($_POST['employee_code']); $password = $_POST['password']; 
                $finger_id = !empty($_POST['finger_id']) ? (int)$_POST['finger_id'] : NULL; $role_id = ($_POST['role'] == 'admin') ? 1 : 2; 

                if ($action == 'add') {
                    if (empty($password)) throw new Exception("Mật khẩu không được để trống.");
                    $sql = "INSERT INTO users (role_id, employee_code, name, password, finger_id) VALUES (?, ?, ?, ?, ?)";
                    $pdo->prepare($sql)->execute([$role_id, $code, $name, $password, $finger_id]);
                    $success = "Thêm nhân viên thành công!";
                } else if ($action == 'edit' && isset($_POST['id'])) {
                    $user_id = $_POST['id']; $params = [$role_id, $code, $name, $finger_id, $user_id];
                    $sql = "UPDATE users SET role_id=?, employee_code=?, name=?, finger_id=? WHERE id=?";
                    if (!empty($password)) { $sql = "UPDATE users SET role_id=?, employee_code=?, name=?, password=?, finger_id=? WHERE id=?"; array_splice($params, 3, 0, [$password]); $params[] = $user_id; }
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