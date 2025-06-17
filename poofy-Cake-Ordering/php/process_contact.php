<?php
// Process contact form submission
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
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? 'General Inquiry');
        $message = trim($_POST['message'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($message)) {
            $errors[] = 'Message is required';
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
        $full_message = "Contact Form Submission:\n\n";
        $full_message .= "Subject: $subject\n";
        if (!empty($phone)) {
            $full_message .= "Phone: $phone\n";
        }
        $full_message .= "Message: $message\n";
        
        // Insert into database
        $result = submitInquiry($conn, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'cake_type' => $subject,
            'preferred_date' => date('Y-m-d'),
            'message' => $full_message
        ]);
        
        if ($result) {
            // Send email notification (optional - requires mail configuration)
            $email_subject = "New Contact Form Message from $name";
            $email_body = "New message received:\n\n";
            $email_body .= "Name: $name\n";
            $email_body .= "Email: $email\n";
            if (!empty($phone)) {
                $email_body .= "Phone: $phone\n";
            }
            $email_body .= "Subject: $subject\n\n";
            $email_body .= "Message:\n$message";
            
            // Uncomment the line below if you have mail configured
            // mail('hello@poofy.com', $email_subject, $email_body);
            
            echo json_encode([
                'success' => true,
                'message' => 'Message sent successfully! We will get back to you as soon as possible.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
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

