<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php';
include '../includes/db_connect.php';

$group_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $total_chit_amount = $_POST['total_chit_amount'];
    $duration_months = $_POST['duration_months'];
    $num_members = $_POST['num_members'];
    $status = $_POST['status'];
    $foreman_commission_rate = $_POST['foreman_commission_rate'];
    $monthly_contribution = $total_chit_amount / $duration_months;

    $sql = "UPDATE chit_groups SET name = ?, total_chit_amount = ?, duration_months = ?, monthly_contribution = ?, num_members = ?, status = ?, foreman_commission_rate = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiidsdi", $name, $total_chit_amount, $duration_months, $monthly_contribution, $num_members, $status, $foreman_commission_rate, $group_id);

    if ($stmt->execute()) {
        header("Location: ../groups.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM chit_groups WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();
$group = $result->fetch_assoc();

?>

<h1>Edit Group</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="edit_group.php?id=<?php echo $group_id; ?>" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $group['name']; ?>" required>
    <br>
    <label for="total_chit_amount">Total Chit Amount:</label>
    <input type="number" id="total_chit_amount" name="total_chit_amount" value="<?php echo $group['total_chit_amount']; ?>" required>
    <br>
    <label for="duration_months">Duration (Months):</label>
    <input type="number" id="duration_months" name="duration_months" value="<?php echo $group['duration_months']; ?>" required>
    <br>
    <label for="num_members">Number of Members:</label>
    <input type="number" id="num_members" name="num_members" value="<?php echo $group['num_members']; ?>" required>
    <br>
    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="active" <?php if ($group['status'] == 'active') echo 'selected'; ?>>Active</option>
        <option value="completed" <?php if ($group['status'] == 'completed') echo 'selected'; ?>>Completed</option>
    </select>
    <br>
    <label for="foreman_commission_rate">Foreman Commission Rate (%):</label>
    <input type="number" id="foreman_commission_rate" name="foreman_commission_rate" step="0.01" value="<?php echo $group['foreman_commission_rate']; ?>" required>
    <br>
    <input type="submit" value="Update Group">
</form>

<?php include '../includes/footer.php'; ?>
