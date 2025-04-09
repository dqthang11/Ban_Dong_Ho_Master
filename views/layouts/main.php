<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Đồng Hồ Luxury</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <!-- Top Bar -->
        <div class="bg-dark text-white py-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <span><i class="fas fa-phone me-2"></i> Hotline: 0987 654 321</span>
                        <span class="ms-3"><i class="fas fa-envelope me-2"></i> Email: contact@dongholuxury.com</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="index.php?controller=user&action=profile" class="text-white me-3"><i class="fas fa-user me-1"></i> Tài khoản</a>
                            <a href="index.php?controller=user&action=logout" class="text-white"><i class="fas fa-sign-out-alt me-1"></i> Đăng xuất</a>
                        <?php else: ?>
                            <a href="index.php?controller=user&action=login" class="text-white me-3"><i class="fas fa-sign-in-alt me-1"></i> Đăng nhập</a>
                            <a href="index.php?controller=user&action=register" class="text-white"><i class="fas fa-user-plus me-1"></i> Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Header -->
        <div class="bg-white py-3 shadow-sm">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <a href="index.php" class="text-decoration-none">
                            <h1 class="mb-0 text-primary">Đồng Hồ Luxury</h1>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form action="index.php" method="GET" class="d-flex">
                            <input type="hidden" name="controller" value="product">
                            <input type="hidden" name="action" value="search">
                            <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="index.php?controller=cart" class="btn btn-outline-primary position-relative">
                            <i class="fas fa-shopping-cart"></i> Giỏ hàng
                            <?php if(getCartCount() > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo getCartCount(); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=product">Sản phẩm</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Danh mục
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach($categories as $category): ?>
                                    <li><a class="dropdown-item" href="index.php?controller=product&action=category&id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Giới thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Liên hệ</a>
                        </li>
                    </ul>
                    <?php if(isAdmin()): ?>
                        <a href="index.php?controller=admin" class="btn btn-light btn-sm">Trang quản trị</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <?php displayMessage(); ?>