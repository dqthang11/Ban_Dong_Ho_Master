<?php
class Product {
    private $conn;
    private $table_name = "products";

    // Properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;
    public $created_at;
    public $updated_at;
    public $status;

    // Constructor
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Read all products
    public function readAll($limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Read one product
    public function readOne() {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ? 
                LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->image = $row['image'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Read products by category
    public function readByCategory($category_id, $limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id AND p.status = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Create product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET name=:name, description=:description, 
                    price=:price, image=:image, 
                    category_id=:category_id, status=:status, 
                    created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Current timestamp
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET name=:name, description=:description, 
                    price=:price, 
                    category_id=:category_id, status=:status, 
                    updated_at=:updated_at";
        
        // Add image only if it's not empty
        if(!empty($this->image)) {
            $query .= ", image=:image";
        }
        
        $query .= " WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Current timestamp
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Add image only if it's not empty
        if(!empty($this->image)) {
            $this->image = htmlspecialchars(strip_tags($this->image));
            $stmt->bindParam(":image", $this->image);
        }
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Search products
    public function search($keywords, $limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? 
                AND p.status = 1 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $limit, PDO::PARAM_INT);
        $stmt->bindParam(5, $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt;
    }
    
    // Count total products
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}