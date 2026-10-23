<?php
include 'connect.php';
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Auction Rush</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        background: #f8f9fa;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .dashboard-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .dashboard-card h3 {
        margin-bottom: 15px;
        font-size: 1.4rem;
        color: #0d6efd;
    }
    .dashboard-card p {
        color: #6c757d;
        margin-bottom: 20px;
    }
    .dashboard-card a {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 50px;
        background: linear-gradient(90deg, #0d6efd, #084298);
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .dashboard-card a:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .dashboard-card i {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #0d6efd;
    }
    .welcome-text {
        margin-top: 30px;
    }
</style>
</head>
<body>
<div class="container">
    <div class="welcome-text text-center">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-muted">Use the options below to navigate through Auction Rush.</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <i class="fas fa-bolt"></i>
            <h3>Live Auctions</h3>
            <p>View all live items and place bids.</p>
            <a href="index.php"><i class="fas fa-arrow-right"></i> Go</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-history"></i>
            <h3>Bid History</h3>
            <p>See all your bids and outbid/winning status.</p>
            <a href="history.php"><i class="fas fa-arrow-right"></i> Go</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
            <p>Check your winning bids.</p>
            <a href="notifications.php"><i class="fas fa-arrow-right"></i> Go</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-plus-circle"></i>
            <h3>Post New Item</h3>
            <p>For organizers: post a new auction item.</p>
            <a href="post_item.php"><i class="fas fa-arrow-right"></i> Go</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-edit"></i>
            <h3>Manage Items</h3>
            <p>Edit or delete your auction items.</p>
            <a href="organizer_dashboard.php"><i class="fas fa-arrow-right"></i> Go</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
