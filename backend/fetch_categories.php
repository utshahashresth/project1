<?php
header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');
require '../backend/connect.php'; // Ensure this file correctly connects to the database

// Initialize arrays to hold categories
$incomeCategories = [];
$expenseCategories = [];

// Fetch income categories
$sqlIncome = "SELECT id, category_name FROM income_categories";
$resultIncome = $conn->query($sqlIncome);

if ($resultIncome) {
    while ($row = $resultIncome->fetch_assoc()) {
        $incomeCategories[] = $row;
    }
} else {
    echo json_encode(["error" => "Failed to fetch income categories"]);
    exit; // Exit if there's an error
}

// Fetch expense categories
$sqlExpense = "SELECT id, category_name FROM expense_categories";
$resultExpense = $conn->query($sqlExpense);

if ($resultExpense) {
    while ($row = $resultExpense->fetch_assoc()) {
        $expenseCategories[] = $row;
    }
} else {
    echo json_encode(["error" => "Failed to fetch expense categories"]);
    exit; // Exit if there's an error
}

// Return the combined categories in a single JSON response
echo json_encode([
    "income_categories" => $incomeCategories,
    "expense_categories" => $expenseCategories
]);

mysqli_close($conn);
?>
