<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

$sql = "SELECT * FROM chit_groups";
$result = $conn->query($sql);
?>

<h1>Chit Groups</h1>

<a href="actions/add_group.php">Add Group</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Total Chit Amount</th>
            <th>Duration (Months)</th>
            <th>Monthly Contribution</th>
            <th>Num Members</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['total_chit_amount']; ?></td>
                    <td><?php echo $row['duration_months']; ?></td>
                    <td><?php echo $row['monthly_contribution']; ?></td>
                    <td><?php echo $row['num_members']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="actions/edit_group.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="actions/delete_group.php?id=<?php echo $row['id']; ?>">Delete</a>
                        <a href="actions/manage_group_members.php?id=<?php echo $row['id']; ?>">Manage Members</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No groups found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
