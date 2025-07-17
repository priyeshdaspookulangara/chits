<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

$sql = "SELECT m.*, i.name as introducer_name FROM members m LEFT JOIN members i ON m.introducer_member_id = i.id";
$result = $conn->query($sql);
?>

<h1>Members</h1>

<a href="actions/add_member.php">Add Member</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact Number</th>
            <th>PAN Card</th>
            <th>Aadhaar Card</th>
            <th>Introducer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['contact_number']; ?></td>
                    <td><?php echo $row['pan_card']; ?></td>
                    <td><?php echo $row['aadhaar_card']; ?></td>
                    <td><?php echo $row['introducer_name']; ?></td>
                    <td>
                        <a href="actions/edit_member.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="actions/delete_member.php?id=<?php echo $row['id']; ?>">Delete</a>
                        <a href="member_ledger.php?id=<?php echo $row['id']; ?>">Ledger</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No members found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
