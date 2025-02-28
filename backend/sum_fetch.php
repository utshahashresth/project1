<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the date range from the GET parameter, default to 'month'
$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : 'month';
$whereClause = "";

switch ($dateRange) {
    case 'week':
        // Filter for the current week (Monday to Sunday)
        $whereClause = " AND YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'month':
        // Filter for the current month
        $whereClause = " AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')";
        break;
    case 'quarter':
        // Filter for the current quarter and year
        $whereClause = " AND QUARTER(date) = QUARTER(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
        break;
    case 'year':
        // Filter for the current year
        $whereClause = " AND YEAR(date) = YEAR(CURDATE())";
        break;
    default:
        // Fallback to current month if an unknown value is provided
        $whereClause = " AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')";
        break;
}

// Prepare and execute the query to get total expenses
$expense_query = "SELECT SUM(amount) AS total_expense FROM expense WHERE u_id = ? $whereClause";
$stmt = $conn->prepare($expense_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$expense_result = $stmt->get_result();
$expense_data = $expense_result->fetch_assoc();
$stmt->close();

// Prepare and execute the query to get total income
$income_query = "SELECT SUM(amount) AS total_income FROM income WHERE u_id = ? $whereClause";
$stmt = $conn->prepare($income_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$income_result = $stmt->get_result();
$income_data = $income_result->fetch_assoc();
$stmt->close();

// Calculate total savings as 30% of total income
$total_income = $income_data['total_income'] ?? 0;
$total_savings = $total_income * 0.30; // Calculate 30% of total income

// Prepare the response
$response = [
    'total_expense' => $expense_data['total_expense'] ?? 0,
    'total_savings' => $total_savings,
    'total_income' => $total_income,
    
];

// Set the content type to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
mysqli_close($conn);
?>
