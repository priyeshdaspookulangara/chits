<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connect.php';

$auction_id = $_GET['id'];

$sql = "DELETE FROM auctions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $auction_id);

if ($stmt->execute()) {
    header("Location: ../auctions.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
