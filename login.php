<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #007bff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { background: white; padding: 2.5rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
    </style>
</head>
<body>
<div class="login-container">
    <h3 class="text-center mb-4"><i class="fas fa-fingerprint me-2"></i> TimeKeep Login</h3>
    
    <?php if(isset($_SESSION['error'])) { echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>'; unset($_SESSION['error']); } ?>
    
    <form action="authenticate.php" method="post">
        <div class="mb-3"><label for="employee_code" class="form-label">Mã NV/Email:</label><input type="text" class="form-control" name="employee_code" required autofocus></div>
        <div class="mb-3"><label for="password" class="form-label">Mật khẩu:</label><input type="password" class="form-control" name="password" required></div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>