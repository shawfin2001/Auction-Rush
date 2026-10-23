<?php
include 'connect.php';

// Session is already started in connect.php, so no need for session_start() here.

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// 1. Get Item ID from URL
$item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
$user_id = $_SESSION['user_id'];

// 2. Fetch Item and Current High Bid
$item_query = "SELECT * FROM items WHERE item_id=$item_id";
$item_result = mysqli_query($conn, $item_query);
$item = mysqli_fetch_assoc($item_result);

if (!$item) { 
    die("<div class='container mt-5 alert alert-danger'>Item not found. <a href='index.php'>Return to Auctions</a></div>"); 
}

// Logic to determine the real current price
$bid_result = mysqli_query($conn, "SELECT MAX(bid_amount) as max_bid FROM bids WHERE item_id=$item_id");
$max_bid_row = mysqli_fetch_assoc($bid_result);
$current_price = $max_bid_row['max_bid'] ?? $item['current_price'];

$error = '';
$success = '';

// 3. Handle Bid Submission
if(isset($_POST['bid'])){
    $bid_amount = (float)$_POST['bid_amount'];
    
    if ($item['seller_id'] == $user_id) {
        $error = "You cannot bid on your own item!";
    } elseif ($bid_amount > $current_price) {
        // Insert bid and update item price in one go
        $insert = "INSERT INTO bids (item_id, user_id, bid_amount) VALUES ($item_id, $user_id, $bid_amount)";
        $update = "UPDATE items SET current_price=$bid_amount WHERE item_id=$item_id";
        
        if (mysqli_query($conn, $insert) && mysqli_query($conn, $update)) {
            $success = "Bid placed successfully!";
            $current_price = $bid_amount; // Update local variable for display
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    } else {
        $error = "Your bid must be higher than $" . number_format($current_price, 2);
    }
}

// Include header only after logic to prevent header errors
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Bid | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .bid-container { max-width: 500px; margin: 50px auto; }
        .price-tag { font-size: 2rem; color: #28a745; font-weight: bold; }
        .item-preview img { width: 100%; border-radius: 12px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container bid-container">
    <div class="card shadow border-0 p-4">
        <h2 class="text-center mb-4">Place Your Bid</h2>
        
        <div class="item-preview text-center">
            <img src="uploads/<?php echo $item['image']; ?>" alt="Item Image">
            <h4><?php echo htmlspecialchars($item['title']); ?></h4>
            <p class="text-muted"><?php echo htmlspecialchars($item['description']); ?></p>
        </div>

        <div class="text-center mb-4">
            <span class="text-muted d-block small">Current Price</span>
            <span class="price-tag">$<?php echo number_format($current_price, 2); ?></span>
        </div>

        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

        <?php if($item['status'] == 'live'): ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Enter Bid Amount</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">$</span>
                        <input type="number" name="bid_amount" step="0.01" class="form-control" 
                               min="<?php echo $current_price + 0.01; ?>" required>
                    </div>
                </div>
                <button type="submit" name="bid" class="btn btn-success btn-lg w-100 rounded-pill">
                    <i class="fas fa-gavel"></i> Confirm Bid
                </button>
            </form>
        <?php else: ?>
            <div class="alert alert-secondary text-center">
                <i class="fas fa-lock"></i> This auction is not currently live.
            </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">← Back to Auctions</a>
        </div>
    </div>
</div>

</body>
</html>