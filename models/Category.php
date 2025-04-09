<?php
class Category {
    private $conn;
    private $table_name = "categories";
    
    // Properties
    public $id;
    public $name;
    public $description;
    public $created_at;
    public $updated_at;
    public $status;
    
    // Constructor
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Read all categories
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 1 ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Read one category
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Create category
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET name=:name, description=:description, 
                    status=:status, created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Current timestamp
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Update category
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET name=:name, description=:description, 
                    status=:status, updated_at=:updated_at 
                WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Current timestamp
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete category
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
}