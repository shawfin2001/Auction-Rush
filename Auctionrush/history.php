<?php
include 'connect.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'header.php';

$user_id = $_SESSION['user_id'];
$bids = mysqli_query($conn, "
    SELECT b.bid_amount, b.bid_time, i.title, i.status, i.current_price, i.item_id
    FROM bids b
    JOIN items i ON b.item_id=i.item_id
    WHERE b.user_id=$user_id
    ORDER BY b.bid_time DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bid History | Auction Rush</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #f8f9fa; }
    .bid-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        padding: 20px 25px;
        margin-bottom: 20px;
        border-left: 8px solid;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .bid-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .bid-card h4 {
        margin-bottom: 10px;
        color: #0d6efd;
    }
    .badge-status {
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.9rem;
        color: #fff;
    }
    .winner { background: #28a745; }
    .outbid { background: #dc3545; }
    .upcoming { background: #f1c40f; color: #000; }
    .live { background: #2ecc71; }
    .bid-info p { margin-bottom: 5px; }
    .btn-feedback {
        background: linear-gradient(90deg, #0d6efd, #084298);
        color: #fff;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-feedback:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: #fff;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Your Bid History</h2>

    <?php while($row = mysqli_fetch_assoc($bids)):
        $status = $row['status'];
        $status_color = $status=='live' ? '#2ecc71' : ($status=='upcoming' ? '#f1c40f' : '#e74c3c');
        $winning = ($row['bid_amount']==$row['current_price'] && $status=='closed') ? 'Winner!' : 'Outbid';
        $badge_class = ($winning=='Winner!') ? 'winner' : (($status=='live') ? 'live' : (($status=='upcoming') ? 'upcoming' : 'outbid'));
    ?>
    <div class="bid-card" style="border-left-color: <?php echo $status_color; ?>;">
        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
        <div class="bid-info">
            <p><strong>Bid Amount:</strong> $<?php echo number_format($row['bid_amount'],2); ?></p>
            <p><strong>Current Price:</strong> $<?php echo number_format($row['current_price'],2); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($status); ?> <span class="badge-status <?php echo $badge_class; ?>"><?php echo $winning; ?></span></p>
            <p><strong>Time:</strong> <?php echo $row['bid_time']; ?></p>
        </div>
        <a href="feedback.php?item_id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-warning">
            <i class="fas fa-star"></i> Rate Item
        </a>
        <?php if($status=='closed' && $winning=='Winner!'): ?>
            <a href="feedback.php?item_id=<?php echo $row['item_id']; ?>" class="btn-feedback mt-2 d-inline-block"><i class="fas fa-star"></i> Leave Feedback</a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
