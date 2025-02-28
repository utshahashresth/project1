<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return a default guest username
    echo json_encode(['username' => 'Guest']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Query to get the username
$query = "SELECT f_name FROM register WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Handle query preparation error
    echo json_encode(['error' => 'Database query preparation failed']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // If no user found, return 'Guest' username
    echo json_encode(['username' => 'Guest']);
    $stmt->close();
    exit;
}

$user_data = $result->fetch_assoc();
$stmt->close();

// Return the user's first name or 'Guest' if the first name is not available
$response = ['username' => $user_data['f_name'] ?? 'Guest'];

header('Content-Type: application/json');
echo json_encode($response);
?>
