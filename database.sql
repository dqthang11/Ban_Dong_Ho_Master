-- Create database
CREATE DATABASE IF NOT EXISTS watch_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE watch_shop;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    status TINYINT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    status TINYINT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(15,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    status TINYINT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(15,2) NOT NULL,
    shipping_address TEXT,
    shipping_phone VARCHAR(20),
    shipping_email VARCHAR(100),
    shipping_name VARCHAR(100),
    payment_method VARCHAR(50) NOT NULL,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create order details table
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, fullname, role, status, created_at, updated_at)
VALUES ('admin', '$2y$10$dKQmzQ8mhAJbEJ59xGZoEeZEL0XQp/nOoNVtLw1tqTYtOsahsJ/hC', 'admin@example.com', 'Administrator', 'admin', 1, NOW(), NOW());

-- Insert sample categories
INSERT INTO categories (name, description, status, created_at, updated_at) VALUES
('Đồng hồ nam', 'Các loại đồng hồ dành cho nam giới', 1, NOW(), NOW()),
('Đồng hồ nữ', 'Các loại đồng hồ dành cho nữ giới', 1, NOW(), NOW()),
('Đồng hồ cao cấp', 'Các loại đồng hồ cao cấp, sang trọng', 1, NOW(), NOW()),
('Đồng hồ thể thao', 'Các loại đồng hồ phù hợp cho hoạt động thể thao', 1, NOW(), NOW()),
('Phụ kiện đồng hồ', 'Các loại phụ kiện dành cho đồng hồ', 1, NOW(), NOW());

-- Insert sample products
INSERT INTO products (name, description, price, image, category_id, status, created_at, updated_at) VALUES
('Rolex Submariner', 'Đồng hồ Rolex Submariner với thiết kế sang trọng, chống nước 300m, mặt đen cổ điển.', 250000000.00, 'rolex-submariner.jpg', 3, 1, NOW(), NOW()),
('Casio G-Shock GA-2100', 'Đồng hồ G-Shock với thiết kế mỏng nhẹ, chống va đập và chống nước.', 3500000.00, 'casio-gshock.jpg', 4, 1, NOW(), NOW()),
('Seiko 5 Automatic', 'Đồng hồ cơ tự động Seiko 5 với bộ máy cơ khí bền bỉ, mặt kính hardlex.', 5200000.00, 'seiko-5.jpg', 1, 1, NOW(), NOW()),
('Citizen Eco-Drive', 'Đồng hồ năng lượng ánh sáng, không cần thay pin, chống nước 100m.', 4800000.00, 'citizen-eco.jpg', 1, 1, NOW(), NOW()),
('Daniel Wellington Classic', 'Đồng hồ nữ với thiết kế thanh lịch, dây da mỏng, mặt trắng tinh tế.', 3200000.00, 'dw-classic.jpg', 2, 1, NOW(), NOW()),
('Fossil Hybrid Smartwatch', 'Đồng hồ thông minh lai kết hợp thiết kế truyền thống và công nghệ hiện đại.', 4500000.00, 'fossil-hybrid.jpg', 2, 1, NOW(), NOW()),
('Omega Seamaster', 'Đồng hồ chống nước chuyên nghiệp, thiết kế thể thao sang trọng.', 120000000.00, 'omega-seamaster.jpg', 3, 1, NOW(), NOW()),
('Timex Weekender', 'Đồng hồ dây vải NATO đơn giản, năng động, phù hợp mọi hoạt động.', 1500000.00, 'timex-weekender.jpg', 4, 1, NOW(), NOW()),
('Dây đồng hồ da cao cấp', 'Dây đồng hồ làm từ da bò thật, nhiều kích cỡ, phù hợp nhiều loại đồng hồ.', 850000.00, 'leather-strap.jpg', 5, 1, NOW(), NOW()),
('Hộp đựng đồng hồ gỗ', 'Hộp đựng đồng hồ bằng gỗ tự nhiên, 10 ngăn, có khóa bảo vệ.', 1200000.00, 'wooden-box.jpg', 5, 1, NOW(), NOW());