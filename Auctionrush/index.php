<?php
include 'connect.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'header.php';

$items = mysqli_query($conn, "SELECT * FROM items ORDER BY status DESC, created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Auction Items | Auction Rush</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #f8f9fa; }
    .auction-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px 25px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-left: 8px solid;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .auction-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .auction-card h4 { color: #0d6efd; margin-bottom: 10px; }
    .auction-card p { margin-bottom: 8px; }
    .btn-bid {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        color: #fff;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-bid:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: #fff;
    }
    .btn-disabled {
        background: #6c757d;
        color: #fff;
        padding: 8px 18px;
        border-radius: 50px;
        border: none;
        cursor: not-allowed;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">All Auction Items</h2>
    <div class="row">
    <?php while($row = mysqli_fetch_assoc($items)):
        $bid_result = mysqli_query($conn, "SELECT MAX(bid_amount) as max_bid FROM bids WHERE item_id={$row['item_id']}");
        $max_bid_row = mysqli_fetch_assoc($bid_result);
        $current_price = $max_bid_row['max_bid'] ?? $row['current_price'];
        $status_color = $row['status'] == 'live' ? '#2ecc71' : ($row['status']=='upcoming' ? '#f1c40f' : '#e74c3c');
    ?>
        <div class="col-md-6 col-lg-4">
            <div class="auction-card" style="border-left-color: <?php echo $status_color; ?>;">
                <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Current Price:</strong> $<?php echo number_format($current_price,2); ?></p>
                <p>Status: <span style="color:<?php echo $status_color; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                <?php if($row['status']=='live'): ?>
                    <a href="bid.php?item_id=<?php echo $row['item_id']; ?>" class="btn-bid mt-2 d-inline-block"><i class="fas fa-gavel"></i> Place Bid</a>
                <?php else: ?>
                    <button class="btn-disabled mt-2 d-inline-block"><i class="fas fa-lock"></i> Not Live</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
