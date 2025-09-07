<?php
// This is now our static homepage.
// We just need the header and footer.
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<main class="container">
    <div class="static-page-content">
        <h1>Welcome to My Website</h1>
        <p>This is the homepage of my personal blog. Here, I explore various topics that I'm passionate about, from technology and science to philosophy and art.</p>
        <p>Feel free to browse through my latest articles on the <a href="blog.php">Blog page</a> or learn more <a href="#">About Me</a>.</p>
        
        <div class="cta-button-container">
            <a href="blog.php" class="button cta-button">Explore the Blog</a>
        </div>
    </div>
</main>

<?php
$conn->close();
include 'includes/footer.php';
?>