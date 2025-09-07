<?php
// Always include our connection and header files.
include 'includes/db_connect.php';
include 'includes/header.php';

// Check if an 'id' was passed in the URL (e.g., post.php?id=1)
if (isset($_GET['id'])) {
    // Sanitize the ID to make sure it's an integer. This is a crucial security step.
    $post_id = intval($_GET['id']);

    // Prepare a SQL statement to prevent SQL injection. This is more secure.
    $stmt = $conn->prepare("SELECT title, content, author, created_at FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id); // "i" means the parameter is an integer

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if we found a post
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        ?>
        <main class="container">
            <article class="single-post-content">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="post-meta">
                    Published on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    by <?php echo htmlspecialchars($post['author']); ?>
                </p>
                <div class="post-body">
                    <!-- Use nl2br() to convert newlines in the text to <br> tags for proper formatting -->
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
            </article>
        </main>
        <?php
    } else {
        // No post with that ID was found
        echo "<main class='container'><p>Post not found.</p></main>";
    }

    $stmt->close();
} else {
    // No ID was provided in the URL
    echo "<main class='container'><p>No post specified.</p></main>";
}

$conn->close();
include 'includes/footer.php';
?>
