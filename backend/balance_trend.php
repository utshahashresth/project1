<?php
// Database connection
include 'connect.php';

// Fetch daily income
$incomeQuery = "
    SELECT DATE(date) as date, SUM(amount) as total_income 
    FROM income 
    GROUP BY DATE(date) 
    ORDER BY date DESC 
    LIMIT 7";
$incomeResult = $conn->query($incomeQuery);

// Fetch daily expenses
$expenseQuery = "
    SELECT DATE(date) as date, SUM(amount) as total_expense 
    FROM expense 
    GROUP BY DATE(date) 
    ORDER BY date DESC 
    LIMIT 7";
$expenseResult = $conn->query($expenseQuery);

// Process results
$incomeData = [];
while ($row = $incomeResult->fetch_assoc()) {
    $incomeData[$row['date']] = $row['total_income'];
}

$expenseData = [];
while ($row = $expenseResult->fetch_assoc()) {
    $expenseData[$row['date']] = $row['total_expense'];
}

// Calculate daily balance trend
$balanceHistory = [];
$runningBalance = 0;

$allDates = array_unique(array_merge(array_keys($incomeData), array_keys($expenseData)));
sort($allDates); // Sort by date (ascending)

foreach ($allDates as $date) {
    $dailyIncome = $incomeData[$date] ?? 0;
    $dailyExpense = $expenseData[$date] ?? 0;
    $runningBalance += ($dailyIncome - $dailyExpense);

    $balanceHistory[] = [
        'date' => $date,
        'balance' => $runningBalance
    ];
}

// Return JSON response
echo json_encode($balanceHistory);
?>
