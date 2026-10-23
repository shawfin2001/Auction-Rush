<?php
include 'connect.php';

// Security: Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'header.php';
$user_id = $_SESSION['user_id'];

/**
 * SQL Logic:
 * We select the user's highest bid for each item and compare it 
 * to the current_price in the items table.
 */
$notif_query = "
    SELECT i.item_id, i.title, i.current_price, i.status, MAX(b.bid_amount) as my_max_bid
    FROM bids b
    JOIN items i ON b.item_id = i.item_id
    WHERE b.user_id = $user_id
    GROUP BY i.item_id
    ORDER BY i.status ASC, b.bid_time DESC";

$notifications = mysqli_query($conn, $notif_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Notifications | Auction Rush</title>
    <style>
        .notif-container { margin-top: 30px; }
        .alert-card {
            border-radius: 12px;
            border: none;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 6px solid;
        }
        .outbid { border-left-color: #e74c3c; background-color: #fdf2f2; }
        .winning { border-left-color: #2ecc71; background-color: #f2fdf5; }
        .won { border-left-color: #f1c40f; background-color: #fffdf2; }
    </style>
</head>
<body class="bg-light">

<div class="container notif-container">
    <h2 class="mb-4"><i class="fas fa-bell text-primary"></i> Your Alerts</h2>

    <?php if(mysqli_num_rows($notifications) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($notifications)): 
            $is_winning = ($row['my_max_bid'] == $row['current_price']);
            $is_closed = ($row['status'] == 'closed');
            
            // Determine Alert Type
            if ($is_closed && $is_winning) {
                $type = "won";
                $icon = "fa-trophy text-warning";
                $text = "Congratulations! You won the auction for <strong>" . htmlspecialchars($row['title']) . "</strong>.";
            } elseif ($is_winning) {
                $type = "winning";
                $icon = "fa-check-circle text-success";
                $text = "You are currently the highest bidder for <strong>" . htmlspecialchars($row['title']) . "</strong>.";
            } else {
                $type = "outbid";
                $icon = "fa-exclamation-triangle text-danger";
                $text = "You've been outbid on <strong>" . htmlspecialchars($row['title']) . "</strong>. Current price is $" . number_format($row['current_price'], 2) . ".";
            }
        ?>
            <div class="card alert-card <?php echo $type; ?> p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas <?php echo $icon; ?> fa-lg me-3"></i>
                        <span><?php echo $text; ?></span>
                    </div>
                    <a href="item_details.php?id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill">View Item</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">No bidding activity found. Start bidding to see alerts!</p>
            <a href="index.php" class="btn btn-primary rounded-pill">Explore Live Auctions</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>