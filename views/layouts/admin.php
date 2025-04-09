<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo isset($page_title) ? $page_title : 'Quản trị'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Watch Shop Admin</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : ''; ?>">
                    <a href="index.php?controller=admin"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển</a>
                </li>
                <li class="<?php echo (isset($_GET['action']) && ($_GET['action'] == 'products' || $_GET['action'] == 'createProduct' || $_GET['action'] == 'editProduct')) ? 'active' : ''; ?>">
                    <a href="#productSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-box"></i> Sản phẩm
                    </a>
                    <ul class="collapse list-unstyled <?php echo (isset($_GET['action']) && ($_GET['action'] == 'products' || $_GET['action'] == 'createProduct' || $_GET['action'] == 'editProduct')) ? 'show' : ''; ?>" id="productSubmenu">
                        <li>
                            <a href="index.php?controller=admin&action=products">Danh sách sản phẩm</a>
                        </li>
                        <li>
                            <a href="index.php?controller=admin&action=createProduct">Thêm sản phẩm mới</a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo (isset($_GET['action']) && ($_GET['action'] == 'categories' || $_GET['action'] == 'createCategory' || $_GET['action'] == 'editCategory')) ? 'active' : ''; ?>">
                    <a href="#categorySubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-list"></i> Danh mục
                    </a>
                    <ul class="collapse list-unstyled <?php echo (isset($_GET['action']) && ($_GET['action'] == 'categories' || $_GET['action'] == 'createCategory' || $_GET['action'] == 'editCategory')) ? 'show' : ''; ?>" id="categorySubmenu">
                        <li>
                            <a href="index.php?controller=admin&action=categories">Danh sách danh mục</a>
                        </li>
                        <li>
                            <a href="index.php?controller=admin&action=createCategory">Thêm danh mục mới</a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo (isset($_GET['action']) && $_GET['action'] == 'orders') ? 'active' : ''; ?>">
                    <a href="index.php?controller=admin&action=orders"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
                </li>
                <li class="<?php echo (isset($_GET['action']) && $_GET['action'] == 'users') ? 'active' : ''; ?>">
                    <a href="index.php?controller=admin&action=users"><i class="fas fa-users"></i> Người dùng</a>
                </li>
                <li>
                    <a href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                    <div class="ml-auto">
                        <span class="mr-3">Xin chào, <?php echo $_SESSION['username']; ?></span>
                        <a href="index.php" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-eye"></i> Xem trang web
                        </a>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid">
                <!-- Content will be injected here -->