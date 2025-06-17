<?php
// Admin API for managing data
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../config.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    switch ($action) {
        case 'dashboard':
            getDashboardStats($conn);
            break;
            
        case 'orders':
            if ($method === 'GET') {
                getOrders($conn);
            } elseif ($method === 'PUT') {
                updateOrderStatus($conn);
            } elseif ($method === 'DELETE') {
                deleteOrder($conn);
            }
            break;
            
        case 'cakes':
            if ($method === 'GET') {
                getCakes($conn);
            } elseif ($method === 'POST') {
                addCake($conn);
            } elseif ($method === 'PUT') {
                updateCake($conn);
            } elseif ($method === 'DELETE') {
                deleteCake($conn);
            }
            break;
            
        case 'categories':
            getCategories($conn);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

function getDashboardStats($conn) {
    $stats = [];
    
    // Total orders
    $query = "SELECT COUNT(*) as total FROM inquiries";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['total_orders'] = $stmt->fetch()['total'];
    
    // New orders
    $query = "SELECT COUNT(*) as total FROM inquiries WHERE status = 'new'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['new_orders'] = $stmt->fetch()['total'];
    
    // Completed orders
    $query = "SELECT COUNT(*) as total FROM inquiries WHERE status = 'completed'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['completed_orders'] = $stmt->fetch()['total'];
    
    // Total cakes
    $query = "SELECT COUNT(*) as total FROM cakes WHERE is_available = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['total_cakes'] = $stmt->fetch()['total'];
    
    // Recent orders
    $query = "SELECT i.*, DATE_FORMAT(i.created_at, '%Y-%m-%d') as order_date 
              FROM inquiries i 
              ORDER BY i.created_at DESC 
              LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['recent_orders'] = $stmt->fetchAll();
    
    echo json_encode($stats);
}

function getOrders($conn) {
    $query = "SELECT i.*, DATE_FORMAT(i.created_at, '%Y-%m-%d') as order_date,
                     DATE_FORMAT(i.preferred_date, '%Y-%m-%d') as delivery_date
              FROM inquiries i 
              ORDER BY i.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll();
    
    echo json_encode($orders);
}

function updateOrderStatus($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    $status = $input['status'] ?? '';
    
    $query = "UPDATE inquiries SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$status, $id]);
    
    echo json_encode(['success' => $result]);
}

function deleteOrder($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    
    $query = "DELETE FROM inquiries WHERE id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}

function getCakes($conn) {
    $query = "SELECT c.*, cat.name as category_name 
              FROM cakes c 
              LEFT JOIN categories cat ON c.category_id = cat.id 
              ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $cakes = $stmt->fetchAll();
    
    echo json_encode($cakes);
}

function addCake($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = "INSERT INTO cakes (category_id, name, description, flavor, price_small, price_medium, price_large, is_featured) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([
        $input['category_id'],
        $input['name'],
        $input['description'],
        $input['flavor'],
        $input['price_small'],
        $input['price_medium'],
        $input['price_large'],
        $input['is_featured'] ? 1 : 0
    ]);
    
    echo json_encode(['success' => $result, 'id' => $conn->lastInsertId()]);
}

function updateCake($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    
    $query = "UPDATE cakes SET 
                category_id = ?, name = ?, description = ?, flavor = ?, 
                price_small = ?, price_medium = ?, price_large = ?, is_featured = ?,
                updated_at = CURRENT_TIMESTAMP
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([
        $input['category_id'],
        $input['name'],
        $input['description'],
        $input['flavor'],
        $input['price_small'],
        $input['price_medium'],
        $input['price_large'],
        $input['is_featured'] ? 1 : 0,
        $id
    ]);
    
    echo json_encode(['success' => $result]);
}

function deleteCake($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    
    // Soft delete - mark as unavailable instead of actually deleting
    $query = "UPDATE cakes SET is_available = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}

function getCategories($conn) {
    $categories = getAllCategories($conn);
    echo json_encode($categories);
}
?>

