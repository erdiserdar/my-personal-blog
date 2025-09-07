<?php
// --- SECURITY CHECK ---
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.php');
    exit;
}
// --- END SECURITY CHECK ---

include '../includes/db_connect.php';
include '../includes/header.php';

$message = '';
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If the form is submitted, process the update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id_update = intval($_POST['post_id']);
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $excerpt = $_POST['excerpt'];
    $image_url = $_POST['image_url'];

    if (!empty($title) && !empty($content) && !empty($author) && !empty($category) && $post_id_update > 0) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, author = ?, category = ?, excerpt = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $title, $content, $author, $category, $excerpt, $image_url, $post_id_update);

        if ($stmt->execute()) {
            $message = "Post updated successfully! <a href='index.php'>Back to Manage Posts</a>";
        } else {
            $message = "Error updating post: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "All required fields must be filled.";
    }
}

// Fetch the existing post data to populate the form
$post = null;
if ($post_id > 0) {
    $stmt = $conn->prepare("SELECT title, content, author, category, excerpt, image_url FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $post = $result->fetch_assoc();
    } else {
        $message = "Post not found.";
    }
    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] != "POST") {
     $message = "No post ID specified.";
}
$conn->close();
?>

<main class="container">
    <h2 class="page-title">Edit Post</h2>

    <?php if (!empty($message)) echo "<p class='message'>{$message}</p>"; ?>

    <?php if ($post): ?>
    <form action="edit_post.php?id=<?php echo $post_id; ?>" method="POST" class="post-form">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($post['author']); ?>" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">--Select a Category--</option>
                <option value="Economics" <?php if($post['category'] == 'Economics') echo 'selected'; ?>>Economics</option>
                <option value="Law" <?php if($post['category'] == 'Law') echo 'selected'; ?>>Law</option>
                <option value="Philosophy" <?php if($post['category'] == 'Philosophy') echo 'selected'; ?>>Philosophy</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($post['image_url']); ?>" placeholder="https://example.com/image.jpg">
        </div>
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="A short summary for the blog page..."><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <button type="submit" class="button">Save Changes</button>
    </form>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
