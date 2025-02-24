<?php
session_start();
error_log('Session status: ' . session_status());
error_log('Session ID: ' . session_id());
error_log('User ID in session: ' . ($_SESSION['user_id'] ?? 'not set'));

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connect.php");

// Get JSON data
$json_data = file_get_contents('php://input');
error_log('Received data: ' . $json_data); // Debug log
$data = json_decode($json_data, true);

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check database connection
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs from JSON data
    $category = mysqli_real_escape_string($conn, $data['category']);
    $amount = floatval($data['amount']);
    $date = mysqli_real_escape_string($conn, $data['date']);
    $note = mysqli_real_escape_string($conn, $data['description'] ?? '');

    // Validate input
    if (empty($category) || $amount <= 0 || empty($date)) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
        exit;
    }

    // Check if category exists in income_categories
    $query = "SELECT id FROM expense_categories WHERE category_name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $category_id = $row['id'];
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid category']);
        exit;
    }

    // Insert into income table with correct category_id
    try {
        $query = "INSERT INTO expense (u_id, category_id, amount, date, notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iidss", $user_id, $category_id, $amount, $date, $note);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_error($conn));
        }

        // Return success response
        echo json_encode(['success' => true]);

        // Close statement
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'user_id' => $user_id
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

mysqli_close($conn);
?>