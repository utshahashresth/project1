<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming user is logged in and we have their u_id
    $u_id = $_SESSION['user_id'] ?? 1; // Replace with actual session handling

    // Get current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Calculate total expenses for the current month
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM expense 
        WHERE MONTH(date) = ? 
        AND YEAR(date) = ? 
        AND u_id = ? 
        AND deleted_at IS NULL
    ");
    $stmt->execute([$currentMonth, $currentYear, $u_id]);
    $totalExpenses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get monthly income
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(amount), 0) as total 
        FROM income 
        WHERE MONTH(date) = ? 
        AND YEAR(date) = ? 
        AND u_id = ? 
        AND deleted_at IS NULL
    ");
    $stmt->execute([$currentMonth, $currentYear, $u_id]);
    $monthlyIncome = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Calculate monthly savings
    $monthlySavings = $monthlyIncome - $totalExpenses;

    // Get largest expense with proper category name
    $stmt = $conn->prepare("
        SELECT e.amount, ec.category_name AS category
        FROM expense e
        JOIN expense_categories ec ON e.category_id = ec.id
        WHERE MONTH(e.date) = ? 
        AND YEAR(e.date) = ? 
        AND e.u_id = ?
        AND e.deleted_at IS NULL 
        ORDER BY e.amount DESC 
        LIMIT 1
    ");
    $stmt->execute([$currentMonth, $currentYear, $u_id]);
    $largestExpense = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get last 6 months of expenses and income
    $months = [];
    $monthlyExpenses = [];
    $monthlyIncomes = [];

    for ($i = 5; $i >= 0; $i--) {
        $date = date('Y-m', strtotime("-$i months"));
        $month = date('M', strtotime("-$i months"));
        $year = date('Y', strtotime("-$i months"));
        $monthNum = date('m', strtotime("-$i months"));

        $months[] = $month;

        // Get expenses for the month
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM expense 
            WHERE MONTH(date) = ? 
            AND YEAR(date) = ? 
            AND u_id = ? 
            AND deleted_at IS NULL
        ");
        $stmt->execute([$monthNum, $year, $u_id]);
        $monthlyExpenses[] = floatval($stmt->fetch(PDO::FETCH_ASSOC)['total']);

        // Get income for the month
        $stmt = $conn->prepare("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM income 
            WHERE MONTH(date) = ? 
            AND YEAR(date) = ? 
            AND u_id = ? 
            AND deleted_at IS NULL
        ");
        $stmt->execute([$monthNum, $year, $u_id]);
        $monthlyIncomes[] = floatval($stmt->fetch(PDO::FETCH_ASSOC)['total']);
    }

    // Get expenses grouped by category
    $stmt = $conn->prepare("
        SELECT ec.category_name AS category, COALESCE(SUM(e.amount), 0) as total 
        FROM expense e
        JOIN expense_categories ec ON e.category_id = ec.id
        WHERE MONTH(e.date) = ? 
        AND YEAR(e.date) = ? 
        AND e.u_id = ?
        AND e.deleted_at IS NULL 
        GROUP BY ec.category_name
    ");
    $stmt->execute([$currentMonth, $currentYear, $u_id]);
    $categoryData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $expenseCategories = [];
    $categoryAmounts = [];
    foreach ($categoryData as $data) {
        $expenseCategories[] = $data['category'];
        $categoryAmounts[] = floatval($data['total']);
    }

    // Calculate budget status (assuming 70% of income as budget)
    $budgetAmount = $monthlyIncome * 0.7;  // 70% of income as budget
    $budgetStatus = $budgetAmount > 0 ? 
        round(($budgetAmount - $totalExpenses) / $budgetAmount * 100) : 0;

    // Prepare response
    $response = [
        'success' => true,
        'total_expenses' => $totalExpenses,
        'monthly_savings' => $monthlySavings,
        'largest_expense' => [
            'amount' => $largestExpense['amount'] ?? 0,
            'category' => $largestExpense['category'] ?? 'N/A'
        ],
        'budget_status' => $budgetStatus,
        'months' => $months,
        'monthly_expenses' => $monthlyExpenses,
        'monthly_income' => $monthlyIncomes,
        'expense_categories' => $expenseCategories,
        'category_amounts' => $categoryAmounts
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => "Connection failed: " . $e->getMessage()
    ]);
}

$conn = null;
?>
