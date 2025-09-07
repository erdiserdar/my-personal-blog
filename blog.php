<?php
// This is our new blog page that displays posts as cards.
include 'includes/db_connect.php';
include 'includes/header.php';

// --- DYNAMIC CATEGORY & POST FETCHING ---

// 1. Fetch all unique, non-empty categories from the posts table.
$category_sql = "SELECT DISTINCT category FROM posts WHERE category IS NOT NULL AND category != '' ORDER BY category ASC";
$category_result = $conn->query($category_sql);
$categories = [];
if ($category_result->num_rows > 0) {
    while($row = $category_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// --- CATEGORY FILTERING LOGIC ---
// Check if a category is selected from the URL, otherwise show all.
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';

// Base SQL query
$sql = "SELECT id, title, author, category, excerpt, image_url, DATE_FORMAT(created_at, '%M %d, %Y') AS formatted_date FROM posts";

// If a specific category is chosen (and it's not 'All'), add a WHERE clause.
if ($selected_category !== 'All') {
    // We use a prepared statement to prevent SQL injection.
    $sql .= " WHERE category = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_category);
} else {
    // If 'All' is selected, fetch all posts.
    $sql .= " ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container">
    <h2 class="page-title">The Blog</h2>
    
    <!-- Category Navigation -->
    <nav class="category-nav">
        <a href="blog.php" class="<?= ($selected_category == 'All') ? 'active' : '' ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a href="blog.php?category=<?= urlencode($category) ?>" class="<?= ($selected_category == $category) ? 'active' : '' ?>">
                <?= htmlspecialchars($category) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Post Grid -->
    <div class="post-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="post-card">
                    <?php if (!empty($post['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="card-image">
                    <?php endif; ?>
                    <div class="card-content">
                        <span class="card-category"><?php echo htmlspecialchars($post['category']); ?></span>
                        <h3 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="card-meta">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo $post['formatted_date']; ?></p>
                        <p class="card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More &rarr;</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts found in this category.</p>
        <?php endif; ?>
    </div>
</main>

<?php
$stmt->close();
$conn->close();
include 'includes/footer.php';
?>