<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
include 'includes/db_connect.php';

$sql = "SELECT a.*, g.name as group_name, m.name as winner_name FROM auctions a JOIN chit_groups g ON a.chit_group_id = g.id JOIN members m ON a.winning_member_id = m.id";
$result = $conn->query($sql);
?>

<h1>Auctions</h1>

<a href="actions/add_auction.php">Add Auction</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Group</th>
            <th>Month</th>
            <th>Date</th>
            <th>Winner</th>
            <th>Winning Bid</th>
            <th>Dividend per Member</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['group_name']; ?></td>
                    <td><?php echo $row['auction_month']; ?></td>
                    <td><?php echo $row['auction_date']; ?></td>
                    <td><?php echo $row['winner_name']; ?></td>
                    <td><?php echo $row['winning_bid_amount']; ?></td>
                    <td><?php echo $row['dividend_per_member']; ?></td>
                    <td>
                        <a href="actions/edit_auction.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="actions/delete_auction.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No auctions found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
