<?php
// admin/manual_checkin.php
require_once 'manual_checkin_logic.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Chấm công Thủ công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> /* CSS Tái sử dụng */ </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3"><h4 class="text-white mb-4">TimeKeep Admin</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_employees.php"><i class="fas fa-users me-2"></i> Quản lý Nhân viên</a></li>
            <li class="nav-item"><a class="nav-link active" href="manual_checkin.php"><i class="fas fa-clock me-2"></i> Chấm công Thủ công</a></li>
            <li class="nav-item"><a class="nav-link" href="../authenticate.php?logout=true"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="content flex-grow-1 p-4">
        <h1 class="mb-4">Chấm công Thủ công</h1>

        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        
        <div class="card shadow">
            <div class="card-header"><i class="fas fa-keyboard me-2"></i> Nhập Thông tin Chấm công</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="manual_checkin">
                    
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Chọn Nhân viên</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">-- Chọn nhân viên --</option>
                            <?php foreach ($all_employees as $emp): ?>
                                <option value="<?php echo $emp['id']; ?>"><?php echo htmlspecialchars($emp['employee_code'] . ' - ' . $emp['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="timestamp" class="form-label">Thời gian Chấm công</label>
                        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="check_type" class="form-label">Trạng thái (Vào/Ra)</label>
                        <select class="form-select" id="check_type" name="check_type" required>
                            <option value="IN">VÀO (Check-in)</option>
                            <option value="OUT">RA (Check-out)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i> Ghi nhận Chấm công</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>