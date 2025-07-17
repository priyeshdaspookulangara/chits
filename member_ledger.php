<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

$member_id = $_GET['id'];

$sql = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

$sql = "SELECT p.*, g.name as group_name FROM member_payments p JOIN chit_groups g ON p.chit_group_id = g.id WHERE p.member_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$payments = $stmt->get_result();
?>

<h1>Payment Ledger for <?php echo $member['name']; ?></h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Group</th>
            <th>Month</th>
            <th>Amount Paid</th>
            <th>Actual Contribution</th>
            <th>Dividend Received</th>
            <th>Status</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($payments->num_rows > 0): ?>
            <?php while($row = $payments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['group_name']; ?></td>
                    <td><?php echo $row['payment_month']; ?></td>
                    <td><?php echo $row['amount_paid']; ?></td>
                    <td><?php echo $row['actual_monthly_contribution']; ?></td>
                    <td><?php echo $row['dividend_received']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['payment_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No payments found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
