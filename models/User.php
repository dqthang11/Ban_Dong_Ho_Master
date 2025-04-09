<?php
class User {
    private $conn;
    private $table_name = "users";
    
    // Properties
    public $id;
    public $username;
    public $password;
    public $email;
    public $fullname;
    public $phone;
    public $address;
    public $role; // 'admin' or 'customer'
    public $created_at;
    public $updated_at;
    public $status;
    
    // Constructor
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET username=:username, password=:password, email=:email, 
                    fullname=:fullname, phone=:phone, address=:address, 
                    role=:role, status=:status, 
                    created_at=:created_at, updated_at=:updated_at";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Current timestamp
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Read one user
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->fullname = $row['fullname'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Read by username
    public function readByUsername() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->email = $row['email'];
            $this->fullname = $row['fullname'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Read by email
    public function readByEmail() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->fullname = $row['fullname'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->status = $row['status'];
            return true;
        }
        
        return false;
    }
    
    // Read all users (for admin)
    public function readAll($limit = 10, $page = 1) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Update user
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET email=:email, fullname=:fullname, 
                    phone=:phone, address=:address, 
                    status=:status, updated_at=:updated_at";
        
        // Add password only if it was changed
        if(!empty($this->password)) {
            $query .= ", password=:password";
        }
        
        $query .= " WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->status = htmlspecialchars(strip_tags($this->status));
        
        // Current timestamp
        $this->updated_at = date('Y-m-d H:i:s');
        
        // Bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":updated_at", $this->updated_at);
        
        // Hash password if it was changed
        if(!empty($this->password)) {
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $password_hash);
        }
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete user
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
    
    // Check if given password is correct
    public function checkPassword($password) {
        return password_verify($password, $this->password);
    }
    
    // Check if username exists
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
    
    // Check if email exists
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
}