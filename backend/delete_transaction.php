<?php
// Include database connection
include 'connect.php';

// Check if the 'id' is provided via GET request
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Check if the transaction exists in the 'income' table
    $sql = "SELECT id FROM income WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Transaction found in the 'income' table, delete it
            $delete_sql = "DELETE FROM income WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $transaction_id);
            if ($delete_stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully from income.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete from income table.']);
            }
            $delete_stmt->close();
        } else {
            // Transaction not found in 'income', check in 'expense' table
            $sql = "SELECT id FROM expense WHERE id = ?";
            $stmt->prepare($sql);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Transaction found in the 'expense' table, delete it
                $delete_sql = "DELETE FROM expense WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $transaction_id);
                if ($delete_stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully from expense.']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete from expense table.']);
                }
                $delete_stmt->close();
            } else {
                // Transaction not found in either table
                echo json_encode(['success' => false, 'error' => 'Transaction not found in income or expense table.']);
            }
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL query.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // If the 'id' parameter is not provided
    echo json_encode(['success' => false, 'error' => 'Transaction ID is required.']);
}
?>
