<?php
include 'connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

if (isset($_POST['post_item'])) {
    $seller_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    
    $image = time() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

    $sql = "INSERT INTO items (seller_id, title, description, image, current_price, status) 
            VALUES ('$seller_id', '$title', '$desc', '$image', '$price', 'active')";
    
    if (mysqli_query($conn, $sql)) { 
        header("Location: Dashboard.php"); 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Item | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #fdfbfb, #ebedee);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .btn-success {
            background: linear-gradient(90deg, #28a745, #1e7e34);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);
        }
        .header-icon {
            color: #28a745;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <div class="card p-4">
        <h3 class="mb-4">
            <i class="fas fa-gavel header-icon"></i>
            Post New Auction
        </h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Item Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter item title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Enter description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Starting Price</label>
                <input type="number" name="price" step="0.01" class="form-control" placeholder="Enter starting price" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Upload Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" name="post_item" class="btn btn-success w-100">
                <i class="fas fa-paper-plane"></i> Post Item
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
