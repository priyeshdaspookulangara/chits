<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php';
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $total_chit_amount = $_POST['total_chit_amount'];
    $duration_months = $_POST['duration_months'];
    $num_members = $_POST['num_members'];
    $foreman_commission_rate = $_POST['foreman_commission_rate'];
    $monthly_contribution = $total_chit_amount / $duration_months;

    $sql = "INSERT INTO chit_groups (name, total_chit_amount, duration_months, monthly_contribution, num_members, foreman_commission_rate) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiidd", $name, $total_chit_amount, $duration_months, $monthly_contribution, $num_members, $foreman_commission_rate);

    if ($stmt->execute()) {
        header("Location: ../groups.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h1>Add Group</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="add_group.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="total_chit_amount">Total Chit Amount:</label>
    <input type="number" id="total_chit_amount" name="total_chit_amount" required>
    <br>
    <label for="duration_months">Duration (Months):</label>
    <input type="number" id="duration_months" name="duration_months" required>
    <br>
    <label for="num_members">Number of Members:</label>
    <input type="number" id="num_members" name="num_members" required>
    <br>
    <label for="foreman_commission_rate">Foreman Commission Rate (%):</label>
    <input type="number" id="foreman_commission_rate" name="foreman_commission_rate" step="0.01" value="5.00" required>
    <br>
    <input type="submit" value="Add Group">
</form>

<?php include '../includes/footer.php'; ?>
