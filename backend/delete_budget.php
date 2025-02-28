<?php
// Include database connection
include 'connect.php';

// Start session to check if the user is logged in
session_start();

// Check if the user is logged in by checking the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit;
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'id' is provided in the JSON payload
if (isset($data['id']) && !empty($data['id'])) {
    $budget_id = $data['id'];
    $user_id = $_SESSION['user_id'];  // Get the logged-in user ID

    // Check if the budget belongs to the logged-in user
    $sql = "SELECT id FROM budgets WHERE id = ? AND u_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $budget_id, $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Budget found, proceed to delete it
            $delete_sql = "DELETE FROM budgets WHERE id = ? AND u_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $budget_id, $user_id);

            if ($delete_stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Budget deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete the budget.']);
            }

            $delete_stmt->close();
        } else {
            // Budget not found or does not belong to the current user
            echo json_encode(['success' => false, 'error' => 'Budget not found or you do not have permission to delete it.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL query.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // If the 'id' parameter is not provided
    echo json_encode(['success' => false, 'error' => 'Budget ID is required.']);
}
?>
