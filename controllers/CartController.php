<?php
class CartController {
    // Display cart
    public function index() {
        // Create category object
        $category = new Category();
        $categories = $category->readAll();
        
        // Page title
        $page_title = "Giỏ hàng";
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/cart/index.php';
        include 'views/layouts/footer.php';
    }
    
    // Add to cart
    public function add() {
        if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Create product object
            $product = new Product();
            $product->id = $product_id;
            $product->readOne();
            
            // Initialize cart session if not exists
            if(!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Add product to cart
            if(isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity
                ];
            }
            
            // Redirect to cart page
            header('Location: index.php?controller=cart');
        } else {
            // Redirect to home page
            header('Location: index.php');
        }
    }
    
    // Update cart
    public function update() {
        if(isset($_POST['update_cart']) && isset($_POST['quantity'])) {
            $quantities = $_POST['quantity'];
            
            foreach($quantities as $product_id => $quantity) {
                if($quantity > 0) {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$product_id]);
                }
            }
            
            // Redirect to cart page
            header('Location: index.php?controller=cart');
        } else {
            // Redirect to home page
            header('Location: index.php');
        }
    }
    
    // Remove from cart
    public function remove() {
        if(isset($_GET['id'])) {
            $product_id = $_GET['id'];
            
            if(isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
            }
            
            // Redirect to cart page
            header('Location: index.php?controller=cart');
        } else {
            // Redirect to home page
            header('Location: index.php');
        }
    }
    
    // Clear cart
    public function clear() {
        unset($_SESSION['cart']);
        
        // Redirect to cart page
        header('Location: index.php?controller=cart');
    }
    
    // Checkout page
    public function checkout() {
        // Check if cart is empty
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: index.php?controller=cart');
            exit;
        }
        
        // Check if user is logged in
        $user_info = [];
        if(isset($_SESSION['user_id'])) {
            $user = new User();
            $user->id = $_SESSION['user_id'];
            $user->readOne();
            
            $user_info = [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address
            ];
        }
        
        // Create category object
        $category = new Category();
        $categories = $category->readAll();
        
        // Page title
        $page_title = "Thanh toán";
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/cart/checkout.php';
        include 'views/layouts/footer.php';
    }
    
    // Process order
    public function placeOrder() {
        if(isset($_POST['place_order'])) {
            // Check if cart is empty
            if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: index.php?controller=cart');
                exit;
            }
            
            // Get form data
            $shipping_name = $_POST['shipping_name'];
            $shipping_email = $_POST['shipping_email'];
            $shipping_phone = $_POST['shipping_phone'];
            $shipping_address = $_POST['shipping_address'];
            $payment_method = $_POST['payment_method'];
            
            // Calculate total amount
            $total_amount = 0;
            foreach($_SESSION['cart'] as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            
            // Create order
            $order = new Order();
            $order->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $order->total_amount = $total_amount;
            $order->shipping_name = $shipping_name;
            $order->shipping_email = $shipping_email;
            $order->shipping_phone = $shipping_phone;
            $order->shipping_address = $shipping_address;
            $order->payment_method = $payment_method;
            $order->order_status = 'pending';
            
            // Save order
            if($order->create()) {
                // Save order details
                foreach($_SESSION['cart'] as $item) {
                    $order_detail = new OrderDetail();
                    $order_detail->order_id = $order->id;
                    $order_detail->product_id = $item['id'];
                    $order_detail->quantity = $item['quantity'];
                    $order_detail->price = $item['price'];
                    $order_detail->create();
                }
                
                // Clear cart
                unset($_SESSION['cart']);
                
                // Set success message
                $_SESSION['success_message'] = "Đặt hàng thành công! Mã đơn hàng của bạn là: " . $order->id;
                
                // Redirect to order success page
                header('Location: index.php?controller=cart&action=success&id=' . $order->id);
                exit;
            } else {
                // Set error message
                $_SESSION['error_message'] = "Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.";
                
                // Redirect to checkout page
                header('Location: index.php?controller=cart&action=checkout');
                exit;
            }
        } else {
            // Redirect to home page
            header('Location: index.php');
        }
    }
    
    // Order success page
    public function success() {
        // Get order ID
        $order_id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // Create order object
        $order = new Order();
        $order->id = $order_id;
        
        // Get order details
        if($order->readOne()) {
            $order_details = $order->getOrderDetails();
            
            // Create category object
            $category = new Category();
            $categories = $category->readAll();
            
            // Page title
            $page_title = "Đặt hàng thành công";
            
            // Include view
            include 'views/layouts/main.php';
            include 'views/cart/success.php';
            include 'views/layouts/footer.php';
        } else {
            // Redirect to home page
            header('Location: index.php');
        }
    }
}