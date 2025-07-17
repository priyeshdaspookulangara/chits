<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php';
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chit_group_id = $_POST['chit_group_id'];
    $auction_month = $_POST['auction_month'];
    $auction_date = $_POST['auction_date'];
    $winning_member_id = $_POST['winning_member_id'];
    $winning_bid_amount = $_POST['winning_bid_amount'];

    // Get group details
    $sql = "SELECT total_chit_amount, num_members, monthly_contribution FROM chit_groups WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chit_group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();

    $gross_chit_amount = $group['monthly_contribution'] * $group['num_members'];
    $discount_offered = $gross_chit_amount - $winning_bid_amount;
    $foreman_commission = $group['total_chit_amount'] * 0.05;
    $net_auction_profit = $discount_offered - $foreman_commission;
    $dividend_per_member = $net_auction_profit / $group['num_members'];

    $sql = "INSERT INTO auctions (chit_group_id, auction_month, auction_date, winning_member_id, winning_bid_amount, discount_offered, foreman_commission, net_auction_profit, dividend_per_member) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisiddddd", $chit_group_id, $auction_month, $auction_date, $winning_member_id, $winning_bid_amount, $discount_offered, $foreman_commission, $net_auction_profit, $dividend_per_member);

    if ($stmt->execute()) {
        header("Location: ../auctions.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT id, name FROM chit_groups";
$groups = $conn->query($sql);

$sql = "SELECT id, name FROM members";
$members = $conn->query($sql);

?>

<h1>Add Auction</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="add_auction.php" method="post">
    <label for="chit_group_id">Chit Group:</label>
    <select id="chit_group_id" name="chit_group_id" required>
        <?php while($row = $groups->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="auction_month">Auction Month:</label>
    <input type="number" id="auction_month" name="auction_month" required>
    <br>
    <label for="auction_date">Auction Date:</label>
    <input type="date" id="auction_date" name="auction_date" required>
    <br>
    <label for="winning_member_id">Winning Member:</label>
    <select id="winning_member_id" name="winning_member_id" required>
        <?php while($row = $members->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="winning_bid_amount">Winning Bid Amount:</label>
    <input type="number" id="winning_bid_amount" name="winning_bid_amount" step="0.01" required>
    <br>
    <input type="submit" value="Add Auction">
</form>

<?php include '../includes/footer.php'; ?>
