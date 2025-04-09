<?php
class Order {
    private $conn;
    private $table_name = "orders";
    
    // Properties
    public $id;
    public $user_id;
    public $total_amount;
    public $shipping_address;
    public $shipping_phone;
    public $shipping_email;
    public $shipping_name;
    public $payment_method;
    public $order_status; // 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
    public $created_at;
    public $updated_at;
    
    // Constructor
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create order
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET user_id=:user_id, total_amount=:total_amount, 
                    shipping_address=:shipping_address, shipping_phone=:shipping_phone, 
                    shipping_email=:shipping_email, shipping_name=:shipping_name, 
                    payment_method=:payment_method, order_status=:order_status, 
                    created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));
        $this->shipping_phone = htmlspecialchars(strip_tags($this->shipping_phone));
        $this->shipping_email = htmlspecialchars(strip_tags($this->shipping_email));
        $this->shipping_name = htmlspecialchars(strip_tags($this->shipping_name));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));
        
        // Current timestamp
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":shipping_address", $this->shipping_address);
        $stmt->bindParam(":shipping_phone", $this->shipping_phone);
        $stmt->bindParam(":shipping_email", $this->shipping_email);
        $stmt->bindParam(":shipping_name", $this->shipping_name);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":order_status", $this->order_status);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            // Get last inserted ID
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    // Read one order
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->user_id = $row['user_id'];
            $this->total_amount = $row['total_amount'];
            $this->shipping_address = $row['shipping_address'];
            $this->shipping_phone = $row['shipping_phone'];
            $this->shipping_email = $row['shipping_email'];
            $this->shipping_name = $row['shipping_name'];
            $this->payment_method = $row['payment_method'];
            $this->order_status = $row['order_status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        
        return false;
    }
    
    // Read all orders
    public function readAll($limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT o.*, u.username as username 
                FROM " . $this->table_name . " o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Read orders by user
    public function readByUser($user_id, $limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Update order status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " 
                SET order_status=:order_status, updated_at=:updated_at 
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));
        
        // Current timestamp
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":order_status", $this->order_status);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Get order details
    public function getOrderDetails() {
        $query = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM order_details od 
                LEFT JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Count total orders
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Count orders by status
    public function countByStatus($status) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE order_status = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}