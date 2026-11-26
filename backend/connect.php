<?php
$config = require __DIR__ . '/config.php';
$db_server=$config['db_host'];
$db_user=$config['db_user'];
$db_password=$config['db_pass'];
$db_name=$config['db_name'];

$conn="";

try {
    $conn=mysqli_connect($db_server,$db_user,$db_password,$db_name);
} catch (mysqli_sql_exception) {
    echo"database couldnt be connected";
}

?>

