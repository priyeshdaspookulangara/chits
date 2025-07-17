<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'includes/header.php';
?>

<h1>Login</h1>

<?php if (isset($_GET['error'])): ?>
    <p style="color: red;">Invalid username or password.</p>
<?php endif; ?>

<form action="actions/login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" value="Login">
</form>

<?php include 'includes/footer.php'; ?>
