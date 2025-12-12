-- Tạo Database
CREATE DATABASE IF NOT EXISTS attendance_app_project;
USE attendance_app_project;

-- 1. Bảng roles (Phân quyền)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Chèn dữ liệu vai trò ban đầu
INSERT INTO roles (id, name) VALUES 
(1, 'admin'), -- ID 1: Quản trị viên
(2, 'employee'); -- ID 2: Nhân viên

-- 2. Bảng users (Tài khoản)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    finger_id INT UNIQUE, -- NULL nếu chưa đăng ký vân tay
    employee_code VARCHAR(20) UNIQUE NOT NULL, -- Dùng để đăng nhập
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Lưu mật khẩu đã hash (nên dùng)
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Chèn dữ liệu mẫu

-- Thêm 1 Admin (role_id = 1)
-- Lưu ý: Trong thực tế, '123456' phải được mã hóa (hashed)
INSERT INTO users (role_id, employee_code, name, password) VALUES 
(1, 'AD001', 'Admin Management', '123456'); 

-- Thêm 2 Nhân viên (role_id = 2)
INSERT INTO users (role_id, employee_code, name, password, finger_id) VALUES 
(2, 'NV101', 'Nguyen Van A', '111111', 10), -- finger_id = 10 (cho ESP32)
(2, 'NV102', 'Le Thi B', '222222', 25); -- finger_id = 25 (cho ESP32)


-- 3. Bảng attendance (Chấm công)
CREATE TABLE attendance (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    timestamp DATETIME NOT NULL,
    check_type ENUM('IN', 'OUT') NOT NULL, 
    source ENUM('DEVICE', 'MANUAL') NOT NULL, 
    recorded_by_user_id INT, -- ID của Admin đã chấm công thủ công (chỉ có giá trị nếu source='MANUAL')
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(id) -- Khóa ngoại tự tham chiếu (self-reference)
);

-- Chèn dữ liệu chấm công mẫu
-- Chấm công từ thiết bị (DEVICE)
INSERT INTO attendance (user_id, timestamp, check_type, source) VALUES 
(2, '2025-12-12 08:00:00', 'IN', 'DEVICE'), -- NV101 vào
(3, '2025-12-12 08:05:00', 'IN', 'DEVICE'), -- NV102 vào
(2, '2025-12-12 17:30:00', 'OUT', 'DEVICE'); -- NV101 ra

-- Chấm công thủ công (MANUAL) do Admin (user_id 1) thực hiện
INSERT INTO attendance (user_id, timestamp, check_type, source, recorded_by_user_id) VALUES 
(3, '2025-12-12 17:40:00', 'OUT', 'MANUAL', 1); -- NV102 ra thủ công
