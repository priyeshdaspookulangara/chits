<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

$sql = "SELECT p.*, m.name as member_name, g.name as group_name FROM member_payments p JOIN members m ON p.member_id = m.id JOIN chit_groups g ON p.chit_group_id = g.id";
$result = $conn->query($sql);
?>

<h1>Payments</h1>

<a href="actions/add_payment.php">Add Payment</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Member</th>
            <th>Group</th>
            <th>Month</th>
            <th>Amount Paid</th>
            <th>Actual Contribution</th>
            <th>Dividend Received</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['member_name']; ?></td>
                    <td><?php echo $row['group_name']; ?></td>
                    <td><?php echo $row['payment_month']; ?></td>
                    <td><?php echo $row['amount_paid']; ?></td>
                    <td><?php echo $row['actual_monthly_contribution']; ?></td>
                    <td><?php echo $row['dividend_received']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['payment_date']; ?></td>
                    <td>
                        <a href="actions/edit_payment.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="actions/delete_payment.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">No payments found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
