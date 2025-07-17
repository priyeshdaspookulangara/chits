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
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $pan_card = $_POST['pan_card'];
    $aadhaar_card = $_POST['aadhaar_card'];
    $bank_account_details = $_POST['bank_account_details'];
    $nominee_name = $_POST['nominee_name'];
    $nominee_contact = $_POST['nominee_contact'];
    $introducer_member_id = $_POST['introducer_member_id'] ?: null;

    $sql = "INSERT INTO members (name, contact_number, address, pan_card, aadhaar_card, bank_account_details, nominee_name, nominee_contact, introducer_member_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $name, $contact_number, $address, $pan_card, $aadhaar_card, $bank_account_details, $nominee_name, $nominee_contact, $introducer_member_id);

    if ($stmt->execute()) {
        header("Location: ../members.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT id, name FROM members";
$members = $conn->query($sql);

?>

<h1>Add Member</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="add_member.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="contact_number">Contact Number:</label>
    <input type="text" id="contact_number" name="contact_number">
    <br>
    <label for="address">Address:</label>
    <textarea id="address" name="address"></textarea>
    <br>
    <label for="pan_card">PAN Card:</label>
    <input type="text" id="pan_card" name="pan_card">
    <br>
    <label for="aadhaar_card">Aadhaar Card:</label>
    <input type="text" id="aadhaar_card" name="aadhaar_card">
    <br>
    <label for="bank_account_details">Bank Account Details:</label>
    <textarea id="bank_account_details" name="bank_account_details"></textarea>
    <br>
    <label for="nominee_name">Nominee Name:</label>
    <input type="text" id="nominee_name" name="nominee_name">
    <br>
    <label for="nominee_contact">Nominee Contact:</label>
    <input type="text" id="nominee_contact" name="nominee_contact">
    <br>
    <label for="introducer_member_id">Introducer:</label>
    <select id="introducer_member_id" name="introducer_member_id">
        <option value="">None</option>
        <?php while($row = $members->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <input type="submit" value="Add Member">
</form>

<?php include '../includes/footer.php'; ?>
