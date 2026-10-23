<?php
include 'connect.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$item_id = (int)$_GET['item_id'];
$item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM items WHERE item_id=$item_id"));
$bids = mysqli_query($conn, "SELECT b.*, u.username FROM bids b JOIN users u ON b.user_id=u.user_id WHERE b.item_id=$item_id ORDER BY b.bid_amount DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($item['title']); ?> - Auctionrush</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #f8f9fa; }
    .item-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        padding: 25px;
        margin: 40px auto;
        max-width: 800px;
    }
    .item-card h2 { color: #0d6efd; margin-bottom: 15px; }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 500;
        color: #fff;
        font-size: 0.9rem;
    }
    .status-live { background-color: #2ecc71; }
    .status-upcoming { background-color: #f1c40f; color: #000; }
    .status-closed { background-color: #e74c3c; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 10px;
        overflow: hidden;
    }
    table th, table td {
        padding: 12px 15px;
        text-align: left;
    }
    table th {
        background-color: #3498db;
        color: #fff;
    }
    table tr:nth-child(even) { background-color: #f2f2f2; }
    table tr:hover { background-color: #d1ecf1; }
    .btn-dashboard {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: linear-gradient(90deg, #0d6efd, #084298);
        color: #fff;
        border-radius: 50px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: #fff;
    }
</style>
</head>
<body>
<div class="container">
    <div class="item-card">
        <h2><i class="fas fa-gavel"></i> <?php echo htmlspecialchars($item['title']); ?></h2>
        <p><?php echo htmlspecialchars($item['description']); ?></p>
        <p><strong>Current Price:</strong> $<?php echo number_format($item['current_price'],2); ?></p>
        <p>
            <strong>Status:</strong>
            <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                <?php echo ucfirst($item['status']); ?>
            </span>
        </p>

        <h4 class="mt-4"><i class="fas fa-users"></i> Bids</h4>
        <?php if(mysqli_num_rows($bids) == 0): ?>
            <p>No bids yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Bid Amount</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($bid = mysqli_fetch_assoc($bids)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bid['username']); ?></td>
                            <td>$<?php echo number_format($bid['bid_amount'],2); ?></td>
                            <td><?php echo $bid['bid_time']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a href="organizer_dashboard.php" class="btn-dashboard"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
