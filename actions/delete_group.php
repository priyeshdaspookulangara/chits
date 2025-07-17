<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connect.php';

$group_id = $_GET['id'];

$sql = "DELETE FROM chit_groups WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);

if ($stmt->execute()) {
    header("Location: ../groups.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
