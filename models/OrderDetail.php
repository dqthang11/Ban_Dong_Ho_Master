<?php
class OrderDetail {
    private $conn;
    private $table_name = "order_details";
    
    // Properties
    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;
    
    // Constructor
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create order detail
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET order_id=:order_id, product_id=:product_id, 
                    quantity=:quantity, price=:price";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        
        // Bind values
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":price", $this->price);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Read by order
    public function readByOrder($order_id) {
        $query = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM " . $this->table_name . " od 
                LEFT JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_id);
        $stmt->execute();
        
        return $stmt;
    }
}