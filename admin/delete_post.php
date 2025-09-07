<?php
// --- SECURITY CHECK ---
session_start();
// If the user is not logged in, redirect to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.php');
    exit;
}
// --- END SECURITY CHECK ---

include '../includes/db_connect.php';
include '../includes/header.php';

$message = '';
$post_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0; // Use REQUEST to get ID from GET or POST

// If confirmation form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    if ($post_id > 0) {
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to the dashboard after deletion
            exit();
        } else {
            $message = "Error deleting post: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch post title for the confirmation message
$post_title = '';
if ($post_id > 0) {
    $stmt = $conn->prepare("SELECT title FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $post = $result->fetch_assoc();
        $post_title = $post['title'];
    } else {
        $message = "Post not found.";
    }
    $stmt->close();
} else {
    $message = "No post specified.";
}

?>

<main class="container">
    <h2 class="page-title">Delete Post</h2>

    <?php if (!empty($message)): ?>
        <p class='message'><?php echo $message; ?></p>
        <a href="index.php">Back to Manage Posts</a>
    <?php elseif (!empty($post_title)): ?>
        <p>Are you sure you want to permanently delete the following post?</p>
        <h3><?php echo htmlspecialchars($post_title); ?></h3>

        <form action="delete_post.php" method="POST" class="delete-form">
            <input type="hidden" name="id" value="<?php echo $post_id; ?>">
            <button type="submit" name="confirm_delete" class="button delete-btn">Yes, Delete</button>
            <a href="index.php" class="button">Cancel</a>
        </form>
    <?php endif; ?>

</main>

<?php
$conn->close();
include '../includes/footer.php';
?>
