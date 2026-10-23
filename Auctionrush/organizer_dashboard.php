<?php
include 'connect.php'; // Ensure session_start() is inside connect.php

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'header.php';
$user_id = $_SESSION['user_id'];

// Capture search term
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";

// Updated Query with Search Logic
$query = "SELECT * FROM items WHERE seller_id = $user_id";
if ($search != "") {
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}
$query .= " ORDER BY created_at DESC";
$my_items = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Organizer Dashboard | Auction Rush</title>
    <style>
        .manage-container { margin-top: 30px; background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .item-thumbnail { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .btn-edit { background: #3498db; color: #fff; border-radius: 50px; padding: 5px 15px; text-decoration: none; font-size: 0.9rem; }
        .btn-delete { background: #e74c3c; color: #fff; border-radius: 50px; padding: 5px 15px; text-decoration: none; font-size: 0.9rem; border: none; }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="manage-container">
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert <?php echo ($_GET['msg'] == 'deleted' ? 'alert-danger' : 'alert-success'); ?> alert-dismissible fade show">
                <?php 
                    if($_GET['msg'] == 'updated') echo "Item updated successfully!";
                    if($_GET['msg'] == 'deleted') echo "Item removed successfully!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3> My Auction Listings</h3>
            <form method="GET" action="organizer_dashboard.php" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search my items..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Current Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($my_items) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($my_items)): ?>
                        <tr>
                            <td><img src="uploads/<?php echo $row['image']; ?>" class="item-thumbnail"></td>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><span class="badge bg-info text-dark"><?php echo ucfirst($row['status']); ?></span></td>
                            <td class="text-success fw-bold">$<?php echo number_format($row['current_price'], 2); ?></td>
                            <td class="text-center">
                                <a href="edit_item.php?id=<?php echo $row['item_id']; ?>" class="btn-edit me-2">Edit</a>
                                <a href="delete.php?id=<?php echo $row['item_id']; ?>" class="btn-delete" onclick="return confirm('Delete this item?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No items found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>