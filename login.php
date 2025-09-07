<?php
// Always start the session at the very top of the script.
session_start();

// Hard-coded credentials for simplicity.
// In a real-world application, you would hash the password and store it in a database.
define('USERNAME', 'admin');
define('PASSWORD', 'password123'); // Change this to a more secure password!

$error_message = '';

// Check if the user is already logged in, if so, redirect to the admin dashboard.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: admin/index.php');
    exit;
}

// Check if the form has been submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the submitted credentials are correct.
    if ($username === USERNAME && $password === PASSWORD) {
        // Credentials are correct, so we set the session variables.
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Redirect the user to the admin dashboard.
        header('Location: admin/index.php');
        exit;
    } else {
        // Incorrect credentials.
        $error_message = 'Invalid username or password.';
    }
}

// Include the header file.
include 'includes/header.php';
?>

<main class="container">
    <h2 class="page-title">Admin Login</h2>

    <?php if (!empty($error_message)): ?>
        <p class="message error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST" class="post-form login-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="admin" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="password123" required>
        </div>
        <button type="submit" class="button">Login</button>
    </form>
</main>

<?php
// Include the footer file.
include 'includes/footer.php';
?>
