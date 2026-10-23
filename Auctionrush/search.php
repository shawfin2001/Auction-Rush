<?php
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'header.php';

$search_query = "";
$results = null;

// Process Search
if (isset($_GET['q'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['q']);
    
    // SQL to search title or description for the keyword
    $sql = "SELECT * FROM items 
            WHERE (title LIKE '%$search_query%' OR description LIKE '%$search_query%') 
            AND status != 'closed' 
            ORDER BY created_at DESC";
            
    $results = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Auctions | Auction Rush</title>
    <style>
        .search-box { margin-top: 30px; margin-bottom: 40px; }
        .item-card { 
            background: #fff; border-radius: 15px; overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s;
        }
        .item-card:hover { transform: translateY(-5px); }
        .item-card img { height: 200px; object-fit: cover; width: 100%; }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="search-box">
        <form action="search.php" method="GET" class="d-flex shadow-sm rounded-pill bg-white p-2">
            <input type="text" name="q" class="form-control border-0 rounded-pill px-4" 
                   placeholder="Search for items (e.g. 'watch', 'antique')..." 
                   value="<?php echo htmlspecialchars($search_query); ?>" required>
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <?php if ($results): ?>
        <h4 class="mb-4 text-muted">Results for "<?php echo htmlspecialchars($search_query); ?>"</h4>
        <div class="row">
            <?php if (mysqli_num_rows($results) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($results)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="item-card">
                            <img src="uploads/<?php echo $row['image']; ?>" alt="Item">
                            <div class="p-3">
                                <h5 class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="text-success fw-bold h5">$<?php echo number_format($row['current_price'], 2); ?></p>
                                <a href="item_details.php?id=<?php echo $row['item_id']; ?>" 
                                   class="btn btn-outline-primary w-100 rounded-pill">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                    <p class="text-muted h5">No items found matching your search.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>