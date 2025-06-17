<?php
// Process order form submission
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get database connection
        $database = new Database();
        $conn = $database->getConnection();
        
        // Sanitize and validate input
        $customer_name = trim($_POST['customer_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $cake_type = trim($_POST['cake_type'] ?? '');
        $cake_size = trim($_POST['cake_size'] ?? '');
        $preferred_date = trim($_POST['preferred_date'] ?? '');
        $occasion = trim($_POST['occasion'] ?? '');
        $delivery_method = trim($_POST['delivery_method'] ?? 'pickup');
        $message = trim($_POST['message'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($customer_name)) {
            $errors[] = 'Customer name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($cake_type)) {
            $errors[] = 'Cake type is required';
        }
        
        if (empty($preferred_date)) {
            $errors[] = 'Preferred date is required';
        } elseif (strtotime($preferred_date) < strtotime('today')) {
            $errors[] = 'Preferred date must be in the future';
        }
        
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $errors
            ]);
            exit;
        }
        
        // Prepare full message
        $full_message = "Order Details:\n";
        $full_message .= "Cake Type: $cake_type\n";
        if (!empty($cake_size)) {
            $full_message .= "Size: $cake_size\n";
        }
        if (!empty($occasion)) {
            $full_message .= "Occasion: $occasion\n";
        }
        $full_message .= "Delivery Method: $delivery_method\n";
        if (!empty($message)) {
            $full_message .= "Special Requests: $message\n";
        }
        
        // Insert into database
        $result = submitInquiry($conn, [
            'name' => $customer_name,
            'email' => $email,
            'phone' => $phone,
            'cake_type' => $cake_type,
            'preferred_date' => $preferred_date,
            'message' => $full_message
        ]);
        
        if ($result) {
            // Send email notification (optional - requires mail configuration)
            $subject = "New Cake Order from $customer_name";
            $email_body = "New order received:\n\n";
            $email_body .= "Customer: $customer_name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: $phone\n";
            $email_body .= "Preferred Date: $preferred_date\n\n";
            $email_body .= $full_message;
            
            // Uncomment the line below if you have mail configured
            // mail('hello@poofy.com', $subject, $email_body);
            
            echo json_encode([
                'success' => true,
                'message' => 'Order request submitted successfully! We will contact you within 24 hours.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit order. Please try again.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>

