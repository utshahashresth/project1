<?php
// File: backend/get_balance.php
session_start();
include_once 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$period = isset($_GET['period']) ? $_GET['period'] : 'current_month';

// Determine date range based on period
$startDate = '';
$endDate = '';

if ($period === 'current_month') {
    $startDate = date('Y-m-01'); // First day of current month
    $endDate = date('Y-m-t');    // Last day of current month
} elseif ($period === 'previous_month') {
    $startDate = date('Y-m-01', strtotime('-1 month')); // First day of previous month
    $endDate = date('Y-m-t', strtotime('-1 month'));    // Last day of previous month
}

// Get total income for the period
$incomeQuery = "SELECT COALESCE(SUM(amount), 0) as total_income 
                FROM income 
                WHERE u_id = ? 
                AND date BETWEEN ? AND ?";
$incomeStmt = $conn->prepare($incomeQuery);
$incomeStmt->bind_param("iss", $user_id, $startDate, $endDate);
$incomeStmt->execute();
$incomeResult = $incomeStmt->get_result();
$incomeData = $incomeResult->fetch_assoc();
$totalIncome = $incomeData['total_income'];

// Get total expenses for the period
$expenseQuery = "SELECT COALESCE(SUM(amount), 0) as total_expense 
                 FROM expense 
                 WHERE u_id = ? 
                 AND date BETWEEN ? AND ?";
$expenseStmt = $conn->prepare($expenseQuery);
$expenseStmt->bind_param("iss", $user_id, $startDate, $endDate);
$expenseStmt->execute();
$expenseResult = $expenseStmt->get_result();
$expenseData = $expenseResult->fetch_assoc();
$totalExpense = $expenseData['total_expense'];

// Calculate balance
$balance = $totalIncome - $totalExpense;

// Get monthly data for the chart (last 6 months)
$monthlyData = [];
for ($i = 5; $i >= 0; $i--) {
    $monthStart = date('Y-m-01', strtotime("-$i month"));
    $monthEnd = date('Y-m-t', strtotime("-$i month"));
    $monthName = date('M', strtotime("-$i month"));
    
    // Get income
    $incomeStmt->bind_param("iss", $user_id, $monthStart, $monthEnd);
    $incomeStmt->execute();
    $incomeResult = $incomeStmt->get_result();
    $incomeData = $incomeResult->fetch_assoc();
    $monthIncome = $incomeData['total_income'];
    
    // Get expense
    $expenseStmt->bind_param("iss", $user_id, $monthStart, $monthEnd);
    $expenseStmt->execute();
    $expenseResult = $expenseStmt->get_result();
    $expenseData = $expenseResult->fetch_assoc();
    $monthExpense = $expenseData['total_expense'];
    
    // Calculate month balance
    $monthBalance = $monthIncome - $monthExpense;
    
    $monthlyData[] = [
        'month' => $monthName,
        'balance' => $monthBalance
    ];
}

// Return the data as JSON
echo json_encode([
    'balance' => $balance,
    'income' => $totalIncome,
    'expense' => $totalExpense,
    'period' => $period,
    'monthlyData' => $monthlyData
]);

$incomeStmt->close();
$expenseStmt->close();
$conn->close();
?>