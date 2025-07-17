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
    if (isset($_POST['add_member'])) {
        $member_id = $_POST['member_id'];
        $sql = "INSERT INTO group_members (chit_group_id, member_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $group_id, $member_id);
        $stmt->execute();
    } elseif (isset($_POST['remove_member'])) {
        $member_id = $_POST['member_id'];
        $sql = "DELETE FROM group_members WHERE chit_group_id = ? AND member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $group_id, $member_id);
        $stmt->execute();
    }
}

$sql = "SELECT * FROM chit_groups WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();
$group = $result->fetch_assoc();

$sql = "SELECT m.id, m.name FROM members m JOIN group_members gm ON m.id = gm.member_id WHERE gm.chit_group_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$group_members = $stmt->get_result();

$sql = "SELECT id, name FROM members WHERE id NOT IN (SELECT member_id FROM group_members WHERE chit_group_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$available_members = $stmt->get_result();

?>

<h1>Manage Members for <?php echo $group['name']; ?></h1>

<h2>Add Member</h2>
<form action="manage_group_members.php?id=<?php echo $group_id; ?>" method="post">
    <select name="member_id">
        <?php while($row = $available_members->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" name="add_member" value="Add Member">
</form>

<h2>Current Members</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $group_members->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td>
                    <form action="manage_group_members.php?id=<?php echo $group_id; ?>" method="post" style="display:inline;">
                        <input type="hidden" name="member_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="remove_member" value="Remove">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
