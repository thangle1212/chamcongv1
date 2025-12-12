<?php
// employee/employee_dashboard.php
require_once 'employee_dashboard_logic.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Dashboard Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> /* CSS Đảm bảo giao diện đẹp */ </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h3><i class="fas fa-user-clock me-2"></i> Xin chào, <?php echo htmlspecialchars($employee_name); ?>!</h3>
    <a href="../authenticate.php?logout=true" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
</div>

<div class="container mt-4">
    <h1 class="mb-4">Lịch sử Chấm công Cá nhân</h1>

    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="card shadow card-attendance">
        <div class="card-header bg-primary text-white"><i class="fas fa-history me-2"></i> 50 Bản ghi Gần nhất</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead><tr><th>Thời gian</th><th>Trạng thái</th><th>Nguồn</th></tr></thead>
                    <tbody>
                        <?php if (empty($my_attendance)): ?>
                            <tr><td colspan="3" class="text-center">Bạn chưa có dữ liệu chấm công nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($my_attendance as $record): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($record['timestamp'])); ?></td>
                                    <td><span class="badge rounded-pill badge-<?php echo strtolower($record['check_type']); ?>"><?php echo $record['check_type']; ?></span></td>
                                    <td><span class="badge rounded-pill badge-<?php echo strtolower($record['source']); ?>"><?php echo $record['source']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>