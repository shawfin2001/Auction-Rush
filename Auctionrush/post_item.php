<?php
include 'connect.php'; // Includes session_start() and $conn

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

if (isset($_POST['post_item'])) {
    $seller_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    
    // Image handling logic
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $image_name = time() . "_" . basename($_FILES['image']['name']);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // SQL matching your established schema
        $sql = "INSERT INTO items (seller_id, title, description, image, starting_price, current_price, status) 
                VALUES ('$seller_id', '$title', '$desc', '$image_name', '$price', '$price', 'upcoming')";
        
        if (mysqli_query($conn, $sql)) { 
            header("Location: dashboard.php"); 
            exit;
        }
    }
}

include 'header.php'; // Standardized navigation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post New Auction | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .post-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            max-width: 650px;
            margin: 50px auto;
            padding: 35px;
        }
        .header-icon { color: #28a745; margin-right: 10px; }
        .btn-post {
            background: linear-gradient(90deg, #28a745, #218838);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-post:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<div class="container">
    <div class="post-card">
        <h3 class="text-center mb-4">
            <i class="fas fa-gavel header-icon"></i>Post New Auction
        </h3>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Item Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Vintage Gold Watch" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the item's condition, history, etc." required></textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Starting Price ($)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Item Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small class="text-muted">Upload a clear photo to attract more bidders.</small>
            </div>
            
            <button type="submit" name="post_item" class="btn btn-post btn-success w-100 text-white">
                <i class="fas fa-paper-plane me-2"></i>List Item for Auction
            </button>
        </form>
        
        <div class="text-center mt-3">
            <a href="dashboard.php" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left"></i> Cancel and Return
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>