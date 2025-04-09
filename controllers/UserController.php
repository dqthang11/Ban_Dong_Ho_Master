<?php
class UserController {
    // Display login form
    public function login() {
        // Check if user is already logged in
        if(isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        
        // Process login form
        if(isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Create user object
            $user = new User();
            $user->username = $username;
            
            // Check if username exists
            if($user->readByUsername()) {
                // Check password
                if($user->checkPassword($password)) {
                    // Set session variables
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['user_role'] = $user->role;
                    
                    // Redirect based on role
                    if($user->role == 'admin') {
                        header('Location: index.php?controller=admin');
                    } else {
                        header('Location: index.php');
                    }
                    exit;
                } else {
                    $error_message = "Sai mật khẩu!";
                }
            } else {
                $error_message = "Tài khoản không tồn tại!";
            }
        }
        
        // Create category object
        $category = new Category();
        $categories = $category->readAll();
        
        // Page title
        $page_title = "Đăng nhập";
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/user/login.php';
        include 'views/layouts/footer.php';
    }
    
    // Display register form
    public function register() {
        // Check if user is already logged in
        if(isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        
        // Process register form
        if(isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            
            // Validate form data
            $errors = [];
            
            if(empty($username)) {
                $errors[] = "Tên đăng nhập không được để trống!";
            }
            
            if(empty($password)) {
                $errors[] = "Mật khẩu không được để trống!";
            }
            
            if($password != $confirm_password) {
                $errors[] = "Xác nhận mật khẩu không khớp!";
            }
            
            if(empty($email)) {
                $errors[] = "Email không được để trống!";
            }
            
            if(empty($fullname)) {
                $errors[] = "Họ tên không được để trống!";
            }
            
            // If no errors, create user
            if(empty($errors)) {
                // Create user object
                $user = new User();
                $user->username = $username;
                $user->email = $email;
                
                // Check if username exists
                if($user->usernameExists()) {
                    $errors[] = "Tên đăng nhập đã tồn tại!";
                }
                
                // Check if email exists
                elseif($user->emailExists()) {
                    $errors[] = "Email đã tồn tại!";
                }
                
                else {
                    // Set user properties
                    $user->password = $password;
                    $user->fullname = $fullname;
                    $user->phone = $phone;
                    $user->address = $address;
                    $user->role = 'customer';
                    $user->status = 1;
                    
                    // Create user
                    if($user->create()) {
                        // Set success message
                        $_SESSION['success_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                        
                        // Redirect to login page
                        header('Location: index.php?controller=user&action=login');
                        exit;
                    } else {
                        $errors[] = "Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.";
                    }
                }
            }
        }
        
        // Create category object
        $category = new Category();
        $categories = $category->readAll();
        
        // Page title
        $page_title = "Đăng ký";
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/user/register.php';
        include 'views/layouts/footer.php';
    }
    
    // User logout
    public function logout() {
        // Unset session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        
        // Redirect to home page
        header('Location: index.php');
        exit;
    }
    
    // Display user profile
    public function profile() {
        // Check if user is logged in
        if(!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Create user object
        $user = new User();
        $user->id = $_SESSION['user_id'];
        $user->readOne();
        
        // Process profile update form
        if(isset($_POST['update_profile'])) {
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validate form data
            $errors = [];
            
            if(empty($email)) {
                $errors[] = "Email không được để trống!";
            }
            
            if(empty($fullname)) {
                $errors[] = "Họ tên không được để trống!";
            }
            
            // If changing password
            if(!empty($current_password)) {
                if(!$user->checkPassword($current_password)) {
                    $errors[] = "Mật khẩu hiện tại không đúng!";
                }
                
                if(empty($new_password)) {
                    $errors[] = "Mật khẩu mới không được để trống!";
                }
                
                if($new_password != $confirm_password) {
                    $errors[] = "Xác nhận mật khẩu mới không khớp!";
                }
            }
            
            // If no errors, update user
            if(empty($errors)) {
                // Set user properties
                $user->email = $email;
                $user->fullname = $fullname;
                $user->phone = $phone;
                $user->address = $address;
                
                // Set new password if provided
                if(!empty($new_password)) {
                    $user->password = $new_password;
                }
                
                // Update user
                if($user->update()) {
                    // Set success message
                    $_SESSION['success_message'] = "Cập nhật thông tin thành công!";
                    
                    // Redirect to profile page
                    header('Location: index.php?controller=user&action=profile');
                    exit;
                } else {
                    $errors[] = "Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại.";
                }
            }
        }
        
        // Get user orders
        $order = new Order();
        $orders = $order->readByUser($_SESSION['user_id']);
        
        // Create category object
        $category = new Category();
        $categories = $category->readAll();
        
        // Page title
        $page_title = "Thông tin tài khoản";
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/user/profile.php';
        include 'views/layouts/footer.php';
    }
}