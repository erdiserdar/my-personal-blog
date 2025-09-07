<?php
// --- SECURITY CHECK ---
session_start();
// If the user is not logged in, redirect to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.php');
    exit;
}
// --- END SECURITY CHECK ---

// Connect to the database and get the header
include '../includes/db_connect.php';
include '../includes/header.php';
?>

<main class="container">
    <div class="admin-header">
        <h2 class="page-title">Manage Posts</h2>
        <a href="add_post.php" class="button">Add New Post</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // SQL to fetch all posts, newest first
            $sql = "SELECT id, title, author, created_at FROM posts ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop through each post
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                    // These links won't work yet, but we are setting them up for the next step.
                    echo "<td class='actions'>";
                    echo "<a href='edit_post.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a>";
                    echo "<a href='delete_post.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No posts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php
$conn->close();
include '../includes/footer.php';
?>

