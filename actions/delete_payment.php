<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connect.php';

$payment_id = $_GET['id'];

$sql = "DELETE FROM member_payments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);

if ($stmt->execute()) {
    header("Location: ../payments.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
