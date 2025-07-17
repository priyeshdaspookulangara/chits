<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

// Get total members
$sql = "SELECT COUNT(*) as total_members FROM members";
$result = $conn->query($sql);
$total_members = $result->fetch_assoc()['total_members'];

// Get total groups
$sql = "SELECT COUNT(*) as total_groups FROM chit_groups";
$result = $conn->query($sql);
$total_groups = $result->fetch_assoc()['total_groups'];

// Get active groups
$sql = "SELECT COUNT(*) as active_groups FROM chit_groups WHERE status = 'active'";
$result = $conn->query($sql);
$active_groups = $result->fetch_assoc()['active_groups'];

?>

<h1>Dashboard</h1>

<p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

<div class="dashboard-stats">
    <div>
        <h2>Total Members</h2>
        <p><?php echo $total_members; ?></p>
    </div>
    <div>
        <h2>Total Groups</h2>
        <p><?php echo $total_groups; ?></p>
    </div>
    <div>
        <h2>Active Groups</h2>
        <p><?php echo $active_groups; ?></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
