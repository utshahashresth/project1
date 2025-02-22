<?php
session_start();
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

require_once("connect.php");

// Function to send JSON response
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

// Ensure user is authenticated
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    sendJsonResponse([
        "status" => "error",
        "message" => "Unauthorized access"
    ], 401);
}

$user_id = (int)$_SESSION['user_id'];
$limit = 4;  // Fixed limit of 4 transactions per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Optional filters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

try {
    $params = [$user_id, $user_id];
    $types = "ii";
    $conditions = [];

    // Date filter
    if ($startDate && $endDate) {
        $conditions[] = "date BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }

    // Type filter
    if ($type === 'income') {
        $query = "SELECT 'income' as type, i.id, i.u_id, i.amount, c.category_name AS category, i.date, i.notes 
                  FROM income i
                  JOIN income_categories c ON i.category_id = c.id
                  WHERE i.u_id = ?";
    } elseif ($type === 'expense') {
        $query = "SELECT 'expense' as type, e.id, e.u_id, e.amount, c.category_name AS category, e.date, e.notes 
                  FROM expense e
                  JOIN expense_categories c ON e.category_id = c.id
                  WHERE e.u_id = ?";
    } else {
        $query = "(SELECT 'income' as type, i.id, i.u_id, i.amount, c.category_name AS category, i.date, i.notes 
                   FROM income i
                   JOIN income_categories c ON i.category_id = c.id
                   WHERE i.u_id = ?)
                   UNION ALL
                   (SELECT 'expense' as type, e.id, e.u_id, e.amount, c.category_name AS category, e.date, e.notes 
                   FROM expense e
                   JOIN expense_categories c ON e.category_id = c.id
                   WHERE e.u_id = ?)";
    }

    // Apply filters
    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Count total records
    $countQuery = "SELECT COUNT(*) FROM ($query) as total_count";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($totalRecords);
    $stmt->fetch();
    $stmt->close();

    // Fetch paginated results
    $query .= " ORDER BY date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $row['date'] = date('Y-m-d H:i:s', strtotime($row['date']));
        $row['amount'] = number_format((float)$row['amount'], 2, '.', '');

        if ($row['type'] === 'expense') {
            $row['amount'] = '-' . $row['amount'];
        }

        $transactions[] = $row;
    }

    // Calculate summary statistics
    $summary = [
        'total_income' => 0,
        'total_expenses' => 0,
        'net_amount' => 0
    ];

    foreach ($transactions as $transaction) {
        $amount = (float)$transaction['amount'];
        if ($transaction['type'] === 'income') {
            $summary['total_income'] += $amount;
        } else {
            $summary['total_expenses'] += abs($amount);
        }
    }

    $summary['net_amount'] = $summary['total_income'] - $summary['total_expenses'];
    $totalPages = ceil($totalRecords / $limit);

    sendJsonResponse([
        "status" => "success",
        "data" => [
            "transactions" => $transactions,
            "summary" => $summary,
            "pagination" => [
                "current_page" => $page,
                "total_pages" => $totalPages,
                "total_records" => $totalRecords,
                "limit" => $limit
            ]
        ]
    ]);

} catch (Exception $e) {
    error_log("Transaction History Error: " . $e->getMessage());
    sendJsonResponse([
        "status" => "error",
        "message" => "An error occurred while fetching transactions"
    ], 500);

} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>
