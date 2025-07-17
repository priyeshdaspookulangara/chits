<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/db_connect.php';

$member_id = $_GET['id'];

$sql = "DELETE FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);

if ($stmt->execute()) {
    header("Location: ../members.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
