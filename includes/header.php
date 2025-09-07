<?php
// We need to check for a session on every page, so let's start it here
// if it's not already started.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Awesome Blog</title>
    <?php 
        // This logic correctly sets the path to the CSS file whether we are in the root or admin directory.
        $css_path_prefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
    ?>
    <link rel="stylesheet" href="<?php echo $css_path_prefix; ?>css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="<?php echo $css_path_prefix; ?>index.php" class="logo">My Awesome Blog</a>
            <nav>
                <a href="<?php echo $css_path_prefix; ?>index.php">Home</a>
                <a href="<?php echo $css_path_prefix; ?>blog.php">Blog</a>
                <a href="<?php echo $css_path_prefix; ?>about.php">About Me</a>
                
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <!-- If logged in, show Manage Posts and Logout -->
                    <a href="<?php echo $css_path_prefix; ?>admin/">Manage Posts</a>
                    <a href="<?php echo $css_path_prefix; ?>logout.php">Logout</a>
                <?php else: ?>
                    <!-- If not logged in, show Login -->
                    <a href="<?php echo $css_path_prefix; ?>login.php">Admin Login</a>
                <?php endif; ?>

            </nav>
        </div>
    </header>
