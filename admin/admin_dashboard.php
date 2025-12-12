<?php
// admin/admin_dashboard.php
session_start();
require_once '../config.php'; 

if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") { header("location: ../login.php"); exit; }

$today_attendance = [];
try {
    $sql = "SELECT u.name, a.timestamp, a.check_type, a.source 
            FROM attendance a JOIN users u ON a.user_id = u.id
            WHERE DATE(a.timestamp) = CURRENT_DATE() ORDER BY a.timestamp DESC";
    $today_attendance = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $error_message = "Lỗi truy vấn: " . $e->getMessage(); }

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> /* CSS Đảm bảo giao diện đẹp */ </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">
        <h4 class="text-white mb-4">TimeKeep Admin</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_employees.php"><i class="fas fa-users me-2"></i> Quản lý Nhân viên</a></li>
            <li class="nav-item"><a class="nav-link" href="manual_checkin.php"><i class="fas fa-clock me-2"></i> Chấm công Thủ công</a></li>
            <li class="nav-item"><a class="nav-link" href="../authenticate.php?logout=true"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="content flex-grow-1 p-4">
        <h1 class="mb-4">Chào mừng, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <?php if (isset($error_message)): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>
        <div class="card shadow">
            <div class="card-header"><i class="fas fa-list-alt me-2"></i> Chấm công Ngày hôm nay (<?php echo date('d/m/Y'); ?>)</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead><tr><th>Tên NV</th><th>Thời gian</th><th>Trạng thái</th><th>Nguồn</th></tr></thead>
                    <tbody>
                        <?php if (empty($today_attendance)): ?><tr><td colspan="4" class="text-center">Chưa có dữ liệu chấm công.</td></tr>
                        <?php else: foreach ($today_attendance as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td><?php echo date('H:i:s', strtotime($record['timestamp'])); ?></td>
                                <td><span class="badge rounded-pill badge-<?php echo strtolower($record['check_type']); ?>"><?php echo $record['check_type']; ?></span></td>
                                <td><span class="badge rounded-pill badge-<?php echo strtolower($record['source']); ?>"><?php echo $record['source']; ?></span></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>