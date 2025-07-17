<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php';
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $chit_group_id = $_POST['chit_group_id'];
    $payment_month = $_POST['payment_month'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];

    // Get group details
    $sql = "SELECT monthly_contribution FROM chit_groups WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chit_group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $monthly_contribution = $group['monthly_contribution'];

    // Get dividend for previous month
    $previous_month = $payment_month - 1;
    $sql = "SELECT dividend_per_member FROM auctions WHERE chit_group_id = ? AND auction_month = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $chit_group_id, $previous_month);
    $stmt->execute();
    $result = $stmt->get_result();
    $auction = $result->fetch_assoc();
    $dividend_received = $auction ? $auction['dividend_per_member'] : 0;

    $actual_monthly_contribution = $monthly_contribution - $dividend_received;
    $status = ($amount_paid >= $actual_monthly_contribution) ? 'paid' : 'due';

    $sql = "INSERT INTO member_payments (member_id, chit_group_id, payment_month, amount_paid, actual_monthly_contribution, dividend_received, status, payment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiddsss", $member_id, $chit_group_id, $payment_month, $amount_paid, $actual_monthly_contribution, $dividend_received, $status, $payment_date);

    if ($stmt->execute()) {
        header("Location: ../payments.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT id, name FROM members";
$members = $conn->query($sql);

$sql = "SELECT id, name FROM chit_groups";
$groups = $conn->query($sql);

?>

<h1>Add Payment</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="add_payment.php" method="post">
    <label for="member_id">Member:</label>
    <select id="member_id" name="member_id" required>
        <?php while($row = $members->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="chit_group_id">Chit Group:</label>
    <select id="chit_group_id" name="chit_group_id" required>
        <?php while($row = $groups->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="payment_month">Payment Month:</label>
    <input type="number" id="payment_month" name="payment_month" required>
    <br>
    <label for="amount_paid">Amount Paid:</label>
    <input type="number" id="amount_paid" name="amount_paid" step="0.01" required>
    <br>
    <label for="payment_date">Payment Date:</label>
    <input type="date" id="payment_date" name="payment_date" required>
    <br>
    <input type="submit" value="Add Payment">
</form>

<?php include '../includes/footer.php'; ?>
