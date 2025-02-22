<?php
include("../backend/connect.php");

$type = $_GET['type'] ?? '';
if ($type === 'income_categories' || $type === 'expense_categories') {
    $query = "SELECT category_name FROM $type";
    $result = mysqli_query($conn, $query);
    
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category_name'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($categories);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid category type']);
}
?>