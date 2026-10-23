<?php
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Fetch image name before deleting the database row
$check = mysqli_query($conn, "SELECT image FROM items WHERE item_id = $item_id AND seller_id = $user_id");

if ($row = mysqli_fetch_assoc($check)) {
    // 2. Delete the physical image file from the 'uploads/' folder
    if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) {
        unlink("uploads/" . $row['image']);
    }
    
    // 3. Delete the item row (Foreign Keys handle bid cleanup)
    mysqli_query($conn, "DELETE FROM items WHERE item_id = $item_id AND seller_id = $user_id");
}

header("Location: organizer_dashboard.php?msg=deleted"); // Redirect with deletion code
exit;