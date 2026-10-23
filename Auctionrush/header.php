<?php
if(!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .navbar-custom {
        background: linear-gradient(90deg, #0d6efd, #084298);
        padding: 10px 20px;
        border-radius: 0 0 15px 15px;
    }
    .navbar-custom a {
        color: #fff;
        font-weight: 500;
        margin-right: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .navbar-custom a:hover {
        color: #ffc107;
        transform: translateY(-2px);
    }
    .navbar-brand {
        font-size: 1.5rem;
        font-weight: bold;
        color: #fff;
    }
    .navbar-toggler {
        border-color: #fff;
    }
    .navbar-toggler-icon {
        color: #fff;
    }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><i class="fas fa-gavel"></i> Auctionrush</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link" href="index.php"><i class="fas fa-bolt"></i> Live Auctions</a>
        <a class="nav-link" href="history.php"><i class="fas fa-history"></i> Bid History</a>
        <a class="nav-link" href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
        <a class="nav-link" href="post_item.php"><i class="fas fa-plus-circle"></i> Post Item</a>
        <a class="nav-link" href="organizer_dashboard.php"><i class="fas fa-edit"></i> Manage Items</a>
        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
