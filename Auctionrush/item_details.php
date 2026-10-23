<?php
// connect.php already handles session_start()
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/** * PARAMETER SYNC: Captures 'id' to match the Organizer Dashboard 
 * and Gallery links
 */
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";

// 1. Handle Bid Submission Logic
if (isset($_POST['place_bid'])) {
    $bid_amount = (float)$_POST['bid_amount'];
    $post_item_id = (int)$_POST['item_id'];

    // Re-verify latest price and status before accepting bid
    $check_sql = "SELECT current_price, seller_id, status FROM items WHERE item_id = $post_item_id";
    $check_res = mysqli_query($conn, $check_sql);
    $item_data = mysqli_fetch_assoc($check_res);

    if ($item_data) {
        if ($item_data['status'] !== 'live') {
            $message = "<div class='alert alert-danger'>🔒 This auction is not currently live.</div>";
        } elseif ($item_data['seller_id'] == $user_id) {
            $message = "<div class='alert alert-warning'>⚠️ You cannot bid on your own item.</div>";
        } elseif ($bid_amount <= $item_data['current_price']) {
            $message = "<div class='alert alert-danger'>💰 Bid must be higher than $" . number_format($item_data['current_price'], 2) . "</div>";
        } else {
            // TRANSACTION: Update bid history and current item price
            $insert_bid = "INSERT INTO bids (item_id, user_id, bid_amount) VALUES ($post_item_id, $user_id, $bid_amount)";
            $update_item = "UPDATE items SET current_price = $bid_amount WHERE item_id = $post_item_id";
            
            if (mysqli_query($conn, $insert_bid) && mysqli_query($conn, $update_item)) {
                $message = "<div class='alert alert-success'>🏆 Success! You are now the highest bidder.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Database Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// 2. Fetch Item and Seller Info
$query = "SELECT i.*, u.username FROM items i 
          JOIN users u ON i.seller_id = u.user_id 
          WHERE i.item_id = $item_id";
$item_res = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($item_res);

if (!$item) {
    die("<div class='container mt-5'><h3>Item not found. <a href='Dashboard.php text-decoration-none'>Return to Dashboard</a></h3></div>");
}

// 3. Fetch Recent Bids and Highlight Highest
$history_sql = "SELECT b.bid_amount, b.bid_time, u.username 
                FROM bids b 
                JOIN users u ON b.user_id = u.user_id 
                WHERE b.item_id = $item_id 
                ORDER BY b.bid_amount DESC LIMIT 5";
$history_res = mysqli_query($conn, $history_sql);

$max_bid_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(bid_amount) as max_bid FROM bids WHERE item_id=$item_id"));
$highest_bid = $max_bid_row['max_bid'];

// Include header only after logic to prevent "Headers already sent" errors
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($item['title']); ?> | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .status-badge { font-size: 0.85rem; padding: 4px 10px; border-radius: 50px; color: #fff; font-weight: 500; }
        .status-live { background: #2ecc71; }
        .status-upcoming { background: #f1c40f; color: #000; }
        .status-closed { background: #e74c3c; }
        .highest-bid { background: #ffeaa7; font-weight: 600; }
        .item-image-container img { border-radius: 12px; max-height: 500px; width: 100%; object-fit: contain; background: white; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 overflow-hidden mb-4">
                <div class="item-image-container">
                    <img src="uploads/<?php echo $item['image']; ?>" class="img-fluid" alt="Product Image">
                </div>
                <div class="card-body">
                    <h4 class="fw-bold"><i class="fas fa-info-circle text-primary"></i> Description</h4>
                    <p class="text-muted" style="white-space: pre-wrap;"><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow p-4 mb-4 border-0">
                <?php echo $message; ?>

                <h1 class="fw-bold h2 mb-1"><?php echo htmlspecialchars($item['title']); ?></h1>
                <p class="text-muted mb-4">Seller: <strong><?php echo htmlspecialchars($item['username']); ?></strong></p>

                <div class="p-3 mb-4 rounded bg-light border-start border-4 border-success">
                    <span class="small text-uppercase text-muted fw-bold">Current Price</span>
                    <h2 class="text-success fw-bold mb-1">$<?php echo number_format($item['current_price'], 2); ?></h2>
                    <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                        <?php echo ucfirst($item['status']); ?>
                    </span>
                </div>

                <?php if ($item['status'] == 'live'): ?>
                    <form method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Bid Amount</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">$</span>
                                <input type="number" name="bid_amount" step="0.01" class="form-control" 
                                       min="<?php echo $item['current_price'] + 0.01; ?>" 
                                       placeholder="Min $<?php echo number_format($item['current_price'] + 0.01, 2); ?>" required>
                            </div>
                        </div>
                        <button type="submit" name="place_bid" class="btn btn-primary btn-lg w-100 shadow-sm">
                            <i class="fas fa-gavel"></i> Confirm Bid
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-secondary text-center fw-bold py-4">
                        <i class="fas fa-lock mb-2 d-block fa-2x"></i>
                        THIS AUCTION HAS ENDED
                    </div>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm p-4 border-0">
                <h5 class="fw-bold mb-3"><i class="fas fa-history text-secondary"></i> Recent Bids</h5>
                <table class="table table-sm align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>User</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($history_res) > 0): ?>
                            <?php while($h = mysqli_fetch_assoc($history_res)): ?>
                                <tr class="<?php echo ($h['bid_amount'] == $highest_bid) ? 'highest-bid' : ''; ?>">
                                    <td>
                                        <?php echo htmlspecialchars($h['username']); ?>
                                        <?php if($h['bid_amount'] == $highest_bid): ?>
                                            <i class="fas fa-crown text-warning ms-1"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold">$<?php echo number_format($h['bid_amount'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-center text-muted py-3">No bids yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="py-5"></footer>
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header bg-white">
            <h4 class="mb-0"><i class="fas fa-comments"></i> Customer Reviews</h4>
        </div>
        <div class="card-body">
            <?php
            // Use $item_id which is already defined at the top of item_details.php
            $feed_query = "SELECT f.*, u.username, f.created_at as time 
                           FROM feedback f 
                           JOIN users u ON f.user_id = u.user_id 
                           WHERE f.item_id = '$item_id' 
                           ORDER BY f.created_at DESC";
            $feed_res = mysqli_query($conn, $feed_query);

            if(mysqli_num_rows($feed_res) > 0) {
                while($rev = mysqli_fetch_assoc($feed_res)) {
                    // Generate stars
                    $stars = "";
                    for($i=1; $i<=5; $i++) {
                        $stars .= ($i <= $rev['rating']) ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-secondary"></i>';
                    }
                    ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold"><?php echo htmlspecialchars($rev['username']); ?></h5>
                            <small class="text-muted"><?php echo date('M d, Y', strtotime($rev['time'])); ?></small>
                        </div>
                        <div class="mb-2"><?php echo $stars; ?></div>
                        <p class="mb-0"><?php echo htmlspecialchars($rev['comment']); ?></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-muted text-center py-3">No reviews yet. Be the first to rate this item!</p>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>