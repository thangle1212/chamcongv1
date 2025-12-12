<?php
// admin/manage_employees.php
require_once 'employee_logic.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Quản lý Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> /* CSS Tái sử dụng */ </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3"><h4 class="text-white mb-4">TimeKeep Admin</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="fas fa-chart-line me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="manage_employees.php"><i class="fas fa-users me-2"></i> Quản lý NV</a></li>
            <li class="nav-item"><a class="nav-link" href="manual_checkin.php"><i class="fas fa-clock me-2"></i> Chấm công Thủ công</a></li>
            <li class="nav-item"><a class="nav-link" href="../authenticate.php?logout=true"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
        </ul>
    </div>
    <div class="content flex-grow-1 p-4">
        <h1 class="mb-4">Quản lý Tài khoản Nhân viên</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
        
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fas fa-plus me-2"></i> Thêm Nhân viên mới</button>
        <div class="card shadow">
            <div class="card-header">Danh sách Nhân viên</div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead><tr><th>Mã NV</th><th>Tên Nhân viên</th><th>ID Vân tay</th><th>Quyền</th><th>Thao tác</th></tr></thead>
                    <tbody>
                        <?php if (empty($employees)): ?><tr><td colspan="5" class="text-center">Chưa có nhân viên nào.</td></tr>
                        <?php else: foreach ($employees as $emp): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($emp['employee_code']); ?></td><td><?php echo htmlspecialchars($emp['name']); ?></td>
                                <td><?php echo htmlspecialchars($emp['finger_id'] ?? 'Chưa ĐK'); ?></td>
                                <td><span class="badge bg-<?php echo ($emp['role_name'] == 'admin' ? 'danger' : 'success'); ?>"><?php echo ucfirst($emp['role_name']); ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                            data-id="<?php echo $emp['id']; ?>" data-name="<?php echo htmlspecialchars($emp['name']); ?>"
                                            data-code="<?php echo htmlspecialchars($emp['employee_code']); ?>" data-finger="<?php echo htmlspecialchars($emp['finger_id']); ?>"
                                            data-role="<?php echo htmlspecialchars($emp['role_name']); ?>"><i class="fas fa-edit"></i> Sửa</button>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Xóa <?php echo htmlspecialchars($emp['name']); ?>?');">
                                        <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Nhân viên</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="mb-3"><label class="form-label">Tên Nhân viên</label><input type="text" class="form-control" name="name" required></div>
                <div class="mb-3"><label class="form-label">Mã Nhân viên</label><input type="text" class="form-control" name="employee_code" required></div>
                <div class="mb-3"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" name="password" required></div>
                <div class="mb-3"><label class="form-label">ID Vân tay</label><input type="number" class="form-control" name="finger_id" min="1" max="127"></div>
                <div class="mb-3"><label class="form-label">Vai trò</label><select class="form-select" name="role"><option value="employee">Nhân viên</option><option value="admin">Quản trị viên</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button><button type="submit" class="btn btn-primary">Lưu</button></div>
        </form>
    </div></div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Sửa Thông tin Nhân viên</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" id="edit-form">
            <div class="modal-body">
                <input type="hidden" name="action" value="edit"><input type="hidden" name="id" id="edit-id">
                <div class="mb-3"><label class="form-label">Tên NV</label><input type="text" class="form-control" name="name" id="edit-name" required></div>
                <div class="mb-3"><label class="form-label">Mã NV</label><input type="text" class="form-control" name="employee_code" id="edit-code" required></div>
                <div class="mb-3"><label class="form-label">Mật khẩu mới (Bỏ trống)</label><input type="password" class="form-control" name="password" id="edit-password"></div>
                <div class="mb-3"><label class="form-label">ID Vân tay</label><input type="number" class="form-control" name="finger_id" id="edit-finger" min="1" max="127"></div>
                <div class="mb-3"><label class="form-label">Vai trò</label><select class="form-select" name="role" id="edit-role"><option value="employee">Nhân viên</option><option value="admin">Quản trị viên</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button><button type="submit" class="btn btn-warning">Cập nhật</button></div>
        </form>
    </div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-code').value = this.dataset.code;
            document.getElementById('edit-finger').value = this.dataset.finger;
            document.getElementById('edit-role').value = this.dataset.role;
            document.getElementById('edit-password').value = '';
        });
    });
</script>
</body>
</html>