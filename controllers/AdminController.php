<?php
class AdminController {
    // Constructor - Check admin authentication
    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
    }
    
    // Admin dashboard
    public function index() {
        // Get statistics
        $product = new Product();
        $total_products = $product->countAll();
        
        $category = new Category();
        $stmt_categories = $category->readAll();
        $total_categories = $stmt_categories->rowCount();
        
        $order = new Order();
        $total_orders = $order->countAll();
        $pending_orders = $order->countByStatus('pending');
        
        $user = new User();
        $stmt_users = $user->readAll();
        $total_users = $stmt_users->rowCount();
        
        // Page title
        $page_title = "Bảng điều khiển";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/dashboard.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Products management
    public function products() {
        // Get parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        // Create product object
        $product = new Product();
        
        // Get products
        $stmt = $product->readAll($limit, $page);
        
        // Get total products
        $total_products = $product->countAll();
        $total_pages = ceil($total_products / $limit);
        
        // Page title
        $page_title = "Quản lý sản phẩm";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/products/index.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Create product form
    public function createProduct() {
        // Get categories
        $category = new Category();
        $categories = $category->readAll();
        
        // Process form submission
        if(isset($_POST['create_product'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $status = $_POST['status'];
            
            // Upload image
            $image = '';
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "assets/images/products/";
                $filename = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $filename;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    // Check file size
                    if ($_FILES["image"]["size"] <= 5000000) {
                        // Allow certain file formats
                        if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                            // Generate unique filename
                            $filename = uniqid() . "." . $imageFileType;
                            $target_file = $target_dir . $filename;
                            
                            // Upload file
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                $image = $filename;
                            } else {
                                $error_message = "Có lỗi xảy ra khi tải ảnh lên.";
                            }
                        } else {
                            $error_message = "Chỉ chấp nhận file JPG, JPEG, PNG & GIF.";
                        }
                    } else {
                        $error_message = "File ảnh quá lớn.";
                    }
                } else {
                    $error_message = "File không phải là ảnh.";
                }
            }
            
            // If no errors, create product
            if(!isset($error_message)) {
                // Create product object
                $product = new Product();
                
                // Set product properties
                $product->name = $name;
                $product->description = $description;
                $product->price = $price;
                $product->image = $image;
                $product->category_id = $category_id;
                $product->status = $status;
                
                // Create product
                if($product->create()) {
                    // Set success message
                    $_SESSION['success_message'] = "Sản phẩm đã được tạo thành công.";
                    
                    // Redirect to products page
                    header('Location: index.php?controller=admin&action=products');
                    exit;
                } else {
                    $error_message = "Có lỗi xảy ra khi tạo sản phẩm.";
                }
            }
        }
        
        // Page title
        $page_title = "Thêm sản phẩm mới";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/products/create.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Edit product form
    public function editProduct() {
        // Get product ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        
        // Create product object
        $product = new Product();
        $product->id = $id;
        $product->readOne();
        
        // Get categories
        $category = new Category();
        $categories = $category->readAll();
        
        // Process form submission
        if(isset($_POST['update_product'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $status = $_POST['status'];
            
            // Upload image if new one is selected
            $image = $product->image;
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "assets/images/products/";
                $filename = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $filename;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    // Check file size
                    if ($_FILES["image"]["size"] <= 5000000) {
                        // Allow certain file formats
                        if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                            // Generate unique filename
                            $filename = uniqid() . "." . $imageFileType;
                            $target_file = $target_dir . $filename;
                            
                            // Upload file
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                $image = $filename;
                                
                                // Delete old image if exists
                                if(!empty($product->image) && file_exists("assets/images/products/" . $product->image)) {
                                    unlink("assets/images/products/" . $product->image);
                                }
                            } else {
                                $error_message = "Có lỗi xảy ra khi tải ảnh lên.";
                            }
                        } else {
                            $error_message = "Chỉ chấp nhận file JPG, JPEG, PNG & GIF.";
                        }
                    } else {
                        $error_message = "File ảnh quá lớn.";
                    }
                } else {
                    $error_message = "File không phải là ảnh.";
                }
            }
            
            // If no errors, update product
            if(!isset($error_message)) {
                // Set product properties
                $product->name = $name;
                $product->description = $description;
                $product->price = $price;
                $product->image = $image;
                $product->category_id = $category_id;
                $product->status = $status;
                
                // Update product
                if($product->update()) {
                    // Set success message
                    $_SESSION['success_message'] = "Sản phẩm đã được cập nhật thành công.";
                    
                    // Redirect to products page
                    header('Location: index.php?controller=admin&action=products');
                    exit;
                } else {
                    $error_message = "Có lỗi xảy ra khi cập nhật sản phẩm.";
                }
            }
        }
        
        // Page title
        $page_title = "Chỉnh sửa sản phẩm";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/products/edit.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Delete product
    public function deleteProduct() {
        // Get product ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        
        // Create product object
        $product = new Product();
        $product->id = $id;
        
        // Get product details to delete image
        $product->readOne();
        
        // Delete product
        if($product->delete()) {
            // Delete product image if exists
            if(!empty($product->image) && file_exists("assets/images/products/" . $product->image)) {
                unlink("assets/images/products/" . $product->image);
            }
            
            // Set success message
            $_SESSION['success_message'] = "Sản phẩm đã được xóa thành công.";
        } else {
            // Set error message
            $_SESSION['error_message'] = "Không thể xóa sản phẩm.";
        }
        
        // Redirect to products page
        header('Location: index.php?controller=admin&action=products');
        exit;
    }
    
    // Categories management
    public function categories() {
        // Create category object
        $category = new Category();
        
        // Get categories
        $stmt = $category->readAll();
        
        // Page title
        $page_title = "Quản lý danh mục";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/categories/index.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Create category form
    public function createCategory() {
        // Process form submission
        if(isset($_POST['create_category'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            
            // Create category object
            $category = new Category();
            
            // Set category properties
            $category->name = $name;
            $category->description = $description;
            $category->status = $status;
            
            // Create category
            if($category->create()) {
                // Set success message
                $_SESSION['success_message'] = "Danh mục đã được tạo thành công.";
                
                // Redirect to categories page
                header('Location: index.php?controller=admin&action=categories');
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi tạo danh mục.";
            }
        }
        
        // Page title
        $page_title = "Thêm danh mục mới";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/categories/create.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Edit category form
    public function editCategory() {
        // Get category ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        
        // Create category object
        $category = new Category();
        $category->id = $id;
        $category->readOne();
        
        // Process form submission
        if(isset($_POST['update_category'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            
            // Set category properties
            $category->name = $name;
            $category->description = $description;
            $category->status = $status;
            
            // Update category
            if($category->update()) {
                // Set success message
                $_SESSION['success_message'] = "Danh mục đã được cập nhật thành công.";
                
                // Redirect to categories page
                header('Location: index.php?controller=admin&action=categories');
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật danh mục.";
            }
        }
        
        // Page title
        $page_title = "Chỉnh sửa danh mục";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/categories/edit.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // Delete category
    public function deleteCategory() {
        // Get category ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        
        // Create category object
        $category = new Category();
        $category->id = $id;
        
        // Delete category
        if($category->delete()) {
            // Set success message
            $_SESSION['success_message'] = "Danh mục đã được xóa thành công.";
        } else {
            // Set error message
            $_SESSION['error_message'] = "Không thể xóa danh mục.";
        }
        
        // Redirect to categories page
        header('Location: index.php?controller=admin&action=categories');
        exit;
    }
    
    // Orders management
    public function orders() {
        // Get parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        
        // Create order object
        $order = new Order();
        
        // Get orders
        $stmt = $order->readAll($limit, $page);
        
        // Get total orders
        $total_orders = $order->countAll();
        $total_pages = ceil($total_orders / $limit);
        
        // Page title
        $page_title = "Quản lý đơn hàng";
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/orders/index.php';
        include 'views/layouts/admin_footer.php';
    }
    
    // View order details
    public function viewOrder() {
        // Get order ID
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        
        // Create order object
        $order = new Order();
        $order->id = $id;
        
        // Get order details
        $order->readOne();
        $order_details = $order->getOrderDetails();
        
        // Process form submission (update order status)
        if(isset($_POST['update_status'])) {
            $order_status = $_POST['order_status'];
            
            // Set order properties
            $order->order_status = $order_status;
            
            // Update order status
            if($order->updateStatus()) {
                // Set success message
                $_SESSION['success_message'] = "Trạng thái đơn hàng đã được cập nhật thành công.";
                
                // Refresh page
                header('Location: index.php?controller=admin&action=viewOrder&id=' . $id);
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.";
            }
        }
        
        // Page title
        $page_title = "Chi tiết đơn hàng #" . $id;
        
        // Include view
        include 'views/layouts/admin.php';
        include 'views/admin/orders/view.php';
        include 'views/layouts/admin_footer.php';
    }
}