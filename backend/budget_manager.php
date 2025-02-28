<?php
// First, make sure no output occurs before the JSON response
// This helps prevent "Unexpected token '<'" errors
ob_start();

require 'connect.php'; // Ensure you have a database connection file

// Set content type header after any possible previous output
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Change to 0 to prevent PHP errors from breaking JSON

session_start();
if (!isset($_SESSION['user_id'])) {
    ob_end_clean(); // Clear any buffered output
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$category_id = $_POST['category'] ?? '';
$amount = $_POST['amount'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$period_type = $_POST['period_type'] ?? 'custom';
$period_value = $_POST['period_value'] ?? '';

// Validate required fields
if (empty($category_id) || empty($amount) || empty($start_date) || empty($end_date)) {
    ob_end_clean(); // Clear any buffered output
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    // First check if the table has the new columns
    $columnCheck = $conn->query("SHOW COLUMNS FROM budgets LIKE 'period_type'");
    
    // If the column doesn't exist, add it
    if ($columnCheck->num_rows === 0) {
        $conn->query("ALTER TABLE budgets ADD COLUMN period_type VARCHAR(10) DEFAULT 'custom', ADD COLUMN period_value VARCHAR(20)");
    }
    
    // Prepare and insert the budget with period information
    // Use a try/catch block to handle any database errors more gracefully
    if ($columnCheck->num_rows === 0) {
        // Old structure - use original query
        $stmt = $conn->prepare("INSERT INTO budgets (u_id, category_id, amount, start_date, end_date, spent_amount) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("iidss", $user_id, $category_id, $amount, $start_date, $end_date);
    } else {
        // New structure with period columns
        $stmt = $conn->prepare("INSERT INTO budgets (u_id, category_id, amount, start_date, end_date, spent_amount, period_type, period_value) VALUES (?, ?, ?, ?, ?, 0, ?, ?)");
        $stmt->bind_param("iidssss", $user_id, $category_id, $amount, $start_date, $end_date, $period_type, $period_value);
    }
    
    if ($stmt->execute()) {
        ob_end_clean(); // Clear any buffered output
        echo json_encode(['success' => true, 'message' => 'Budget added successfully.']);
    } else {
        throw new Exception($stmt->error);
    }
    
    // Close statement
    $stmt->close();
    
} catch (Exception $e) {
    ob_end_clean(); // Clear any buffered output
    error_log("Budget insert error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close connection
$conn->close();
?>