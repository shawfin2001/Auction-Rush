<?php
include 'connect.php';
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Get the Item ID
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
$msg = '';
$error = '';

// 3. Fetch Item Details (to show title)
$item_query = mysqli_query($conn, "SELECT title, image FROM items WHERE item_id = '$item_id'");
$item = mysqli_fetch_assoc($item_query);

if (!$item) {
    die("Item not found. <a href='index.php'>Go back</a>");
}

// 4. Handle Feedback Submission
if (isset($_POST['submit_feedback'])) {
    $user_id = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Check if user already reviewed this item
    $check = mysqli_query($conn, "SELECT * FROM feedback WHERE user_id='$user_id' AND item_id='$item_id'");
    
    if (mysqli_num_rows($check) > 0) {
        $error = "You have already provided feedback for this item.";
    } else {
        // Insert Feedback
        $insert = "INSERT INTO feedback (item_id, user_id, rating, comment) VALUES ('$item_id', '$user_id', '$rating', '$comment')";
        if (mysqli_query($conn, $insert)) {
            $msg = "Feedback submitted successfully! <a href='item_details.php?id=$item_id'>View Item</a>";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Feedback | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .star-rating { direction: rtl; display: inline-block; font-size: 2rem; }
        .star-rating input { display: none; }
        .star-rating label { color: #ddd; cursor: pointer; transition: color 0.2s; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #ffc107; }
        .item-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-body p-4 text-center">
                
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="item-img mb-3">
                <h3 class="mb-2">Rate: <?php echo htmlspecialchars($item['title']); ?></h3>
                <p class="text-muted">How was your experience?</p>

                <?php if($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
                <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Your Review</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Write your review here..." required></textarea>
                    </div>

                    <button type="submit" name="submit_feedback" class="btn btn-primary w-100">Submit Feedback</button>
                    <a href="history.php" class="btn btn-link mt-2 text-decoration-none text-muted">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>