<?php
// Database configuration for Poofy Cake Business Website
class Database {
    private $host = 'localhost';
    private $db_name = 'poofy_cakes';
    private $username = 'root'; // Change this for production
    private $password = ''; // Change this for production
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Helper functions for common database operations
function getAllCakes($conn) {
    $query = "SELECT c.*, cat.name as category_name 
              FROM cakes c 
              LEFT JOIN categories cat ON c.category_id = cat.id 
              WHERE c.is_available = 1 
              ORDER BY c.is_featured DESC, c.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCakesByCategory($conn, $category_id) {
    $query = "SELECT c.*, cat.name as category_name 
              FROM cakes c 
              LEFT JOIN categories cat ON c.category_id = cat.id 
              WHERE c.category_id = ? AND c.is_available = 1 
              ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$category_id]);
    return $stmt->fetchAll();
}

function getFeaturedCakes($conn, $limit = 3) {
    $query = "SELECT c.*, cat.name as category_name 
              FROM cakes c 
              LEFT JOIN categories cat ON c.category_id = cat.id 
              WHERE c.is_featured = 1 AND c.is_available = 1 
              ORDER BY c.created_at DESC 
              LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getAllCategories($conn) {
    $query = "SELECT * FROM categories ORDER BY name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

function submitInquiry($conn, $data) {
    $query = "INSERT INTO inquiries (customer_name, email, phone, cake_type, preferred_date, message) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['cake_type'],
        $data['preferred_date'],
        $data['message']
    ]);
}

function getGalleryImages($conn, $limit = null) {
    $query = "SELECT * FROM gallery ORDER BY is_featured DESC, created_at DESC";
    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>

