<?php
session_start();
include 'connection.php'; 
if (!isset($_SESSION['user_id'])) {
    echo 0;
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE u_id = $user_id";
$result = mysqli_query($conn, $sql);
$row1 = mysqli_fetch_assoc($result);
echo $row1['total_expenses'] ?? 0;
mysqli_close($conn);
?>
