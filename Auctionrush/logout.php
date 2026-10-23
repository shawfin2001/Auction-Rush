<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logging Out - Auctionrush</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: Arial, sans-serif;
    }
    .logout-card {
        background: #fff;
        padding: 30px 25px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        text-align: center;
    }
    .logout-card h3 {
        color: #0d6efd;
        margin-bottom: 15px;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 15px;
    }
</style>
<meta http-equiv="refresh" content="2;url=login.php">
</head>
<body>
<div class="logout-card">
    <div class="spinner-border text-primary" role="status"></div>
    <h3>Logging out...</h3>
    <p>You will be redirected to the login page shortly.</p>
</div>
</body>
</html>
