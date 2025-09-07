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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $excerpt = $_POST['excerpt'];
    $image_url = $_POST['image_url'];

    if (!empty($title) && !empty($content) && !empty($author) && !empty($category)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author, category, excerpt, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title, $content, $author, $category, $excerpt, $image_url);
        
        if ($stmt->execute()) {
            $message = "New post created successfully! <a href='index.php'>Back to Manage Posts</a>";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Title, Content, Author, and Category are required.";
    }
}

?>

<main class="container">
    <h2 class="page-title">Add New Post</h2>

    <?php if (!empty($message)) echo "<p class='message'>{$message}</p>"; ?>

    <form action="add_post.php" method="POST" class="post-form">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">--Select a Category--</option>
                <option value="Economics">Economics</option>
                <option value="Law">Law</option>
                <option value="Philosophy">Philosophy</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="text" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
        </div>
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="A short summary for the blog page..."></textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10" required></textarea>
        </div>
        <button type="submit" class="button">Publish Post</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
