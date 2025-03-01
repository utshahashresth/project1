<?php
include("connect.php");

try {
    // Check required fields
    if (empty($_POST['email']) || empty($_POST['pword'])) {
        throw new Exception("Email and password are required");
    }
    
    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['pword']; // Don't escape password before verification
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if user exists and get stored password
    $query = "SELECT id, password FROM register WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        throw new Exception("Email is not registered");
    }
    
    // Verify password (adjust based on how you store passwords)
    // If using password_hash() for storage:
    if (password_verify($password, $row['password'])) {
        // Password is correct - start session
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $email;
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful'
        ]);
        exit;
    } 
    // For plain text passwords (not recommended but included for compatibility):
    // else if ($password === $row['password']) {
    //     session_start();
    //     $_SESSION['user_id'] = $row['id'];
    //     $_SESSION['email'] = $email;
    //     
    //     echo json_encode([
    //         'success' => true,
    //         'message' => 'Login successful'
    //     ]);
    //     exit;
    // }
    else {
        // Password is incorrect
        throw new Exception("Incorrect password");
    }
    
} catch (Exception $e) {
    // Return error as JSON
    header('Content-Type: application/json');
    http_response_code(401);
    
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>