<?php
// authenticate.php
session_start();
require_once 'config.php';

if (isset($_POST["employee_code"])) {
    $code = trim($_POST["employee_code"]);
    $password = $_POST["password"];

    $sql = "SELECT u.id, u.password, u.name, r.name AS role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.employee_code = :code";
            
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":code", $code, PDO::PARAM_STR);
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($password == $row['password']){
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["role"] = $row["role_name"];
                    $_SESSION["name"] = $row["name"];
                    
                    if ($row["role_name"] == 'admin') {
                        header("location: admin/admin_dashboard.php");
                    } else {
                        header("location: employee/employee_dashboard.php");
                    }
                    exit;
                }
            }
        }
    }
    $_SESSION['error'] = 'Mã nhân viên hoặc mật khẩu không đúng.';
    header("location: login.php");
    exit;
}

// Xử lý Đăng xuất
if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("location: login.php");
    exit;
}
?>