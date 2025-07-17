<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php';
include '../includes/db_connect.php';

$member_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $pan_card = $_POST['pan_card'];
    $aadhaar_card = $_POST['aadhaar_card'];
    $bank_account_details = $_POST['bank_account_details'];
    $nominee_name = $_POST['nominee_name'];
    $nominee_contact = $_POST['nominee_contact'];
    $introducer_member_id = $_POST['introducer_member_id'] ?: null;

    $sql = "UPDATE members SET name = ?, contact_number = ?, address = ?, pan_card = ?, aadhaar_card = ?, bank_account_details = ?, nominee_name = ?, nominee_contact = ?, introducer_member_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssii", $name, $contact_number, $address, $pan_card, $aadhaar_card, $bank_account_details, $nominee_name, $nominee_contact, $introducer_member_id, $member_id);

    if ($stmt->execute()) {
        header("Location: ../members.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

$sql = "SELECT id, name FROM members";
$members = $conn->query($sql);

?>

<h1>Edit Member</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="edit_member.php?id=<?php echo $member_id; ?>" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $member['name']; ?>" required>
    <br>
    <label for="contact_number">Contact Number:</label>
    <input type="text" id="contact_number" name="contact_number" value="<?php echo $member['contact_number']; ?>">
    <br>
    <label for="address">Address:</label>
    <textarea id="address" name="address"><?php echo $member['address']; ?></textarea>
    <br>
    <label for="pan_card">PAN Card:</label>
    <input type="text" id="pan_card" name="pan_card" value="<?php echo $member['pan_card']; ?>">
    <br>
    <label for="aadhaar_card">Aadhaar Card:</label>
    <input type="text" id="aadhaar_card" name="aadhaar_card" value="<?php echo $member['aadhaar_card']; ?>">
    <br>
    <label for="bank_account_details">Bank Account Details:</label>
    <textarea id="bank_account_details" name="bank_account_details"><?php echo $member['bank_account_details']; ?></textarea>
    <br>
    <label for="nominee_name">Nominee Name:</label>
    <input type="text" id="nominee_name" name="nominee_name" value="<?php echo $member['nominee_name']; ?>">
    <br>
    <label for="nominee_contact">Nominee Contact:</label>
    <input type="text" id="nominee_contact" name="nominee_contact" value="<?php echo $member['nominee_contact']; ?>">
    <br>
    <label for="introducer_member_id">Introducer:</label>
    <select id="introducer_member_id" name="introducer_member_id">
        <option value="">None</option>
        <?php while($row = $members->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $member['introducer_member_id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <input type="submit" value="Update Member">
</form>

<?php include '../includes/footer.php'; ?>
