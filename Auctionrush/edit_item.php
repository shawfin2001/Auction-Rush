<?php
include 'connect.php';

// 1. Security: Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

// 2. Fetch Item & Verify Ownership
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    
    // IMPORTANT: We check if seller_id MATCHES the logged-in user
    $query = "SELECT * FROM items WHERE item_id = $item_id AND seller_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $item = mysqli_fetch_assoc($result);
    } else {
        // If query fails, it means the item doesn't exist OR the user isn't the owner
        die("<div class='container mt-5 alert alert-danger'>⛔ Access Denied: You are not the seller of this item. <a href='index.php'>Go Home</a></div>");
    }
} else {
    header("Location: index.php");
    exit;
}

// 3. Handle Update Logic
if (isset($_POST['update_item'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = $_POST['status']; // Values: 'upcoming', 'live', 'closed'

    $update_sql = "UPDATE items SET 
                   title = '$title', 
                   description = '$description', 
                   status = '$status' 
                   WHERE item_id = $item_id";

    if (mysqli_query($conn, $update_sql)) {
        $msg = "✅ Item updated successfully! Redirecting...";
        // Refresh the data so the form shows new values
        $item['title'] = $title;
        $item['description'] = $description;
        $item['status'] = $status;
        
        // Optional: Redirect back to item page after 1 second
        header("refresh:1;url=manage_items.php?id=$item_id");
    } else {
        $error = "Database Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Item | AuctionRush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Item</h4>
                    <a href="manage_items.php?id=<?php echo $item_id; ?>" class="btn btn-sm btn-outline-light">Back to Item</a>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($msg): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Item Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($item['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Auction Status</label>
                            <div class="p-3 bg-light border rounded">
                                <select name="status" class="form-select border-primary fw-bold text-uppercase">
                                    <option value="upcoming" <?php if($item['status']=='upcoming') echo 'selected'; ?>>🟡 Upcoming (Hidden/Preview)</option>
                                    <option value="live" <?php if($item['status']=='live') echo 'selected'; ?>>🟢 Live (Active Bidding)</option>
                                    <option value="closed" <?php if($item['status']=='closed') echo 'selected'; ?>>🔴 Closed (Sold/Ended)</option>
                                </select>
                                <div class="form-text mt-2 text-muted">
                                    <small>
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Live:</strong> Users can bid immediately. <br>
                                        <strong>Closed:</strong> Bidding stops. Highest bidder wins.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Current Price (Cannot be edited)</label>
                            <input type="text" class="form-control bg-secondary text-white" 
                                   value="$<?php echo number_format($item['current_price'], 2); ?>" disabled>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_item" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="manage_items.php?id=<?php echo $item_id; ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>