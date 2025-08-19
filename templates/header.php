<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Dissertation Repository</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/global.css">

</head>
<body>

<header class="navbar">
    <div class="nav-left">
        <a href="index.php" class="nav-logo">🎓 Dissertation Repo</a>
    </div>

    <nav class="nav-center">
        <a href="index.php">Home</a>
        <a href="about_faculty.php">About Faculty</a>
        <a href="about_developers.php">About Developers</a>
    </nav>

    <div class="nav-right">
        <?php if (isset($_SESSION['user_name'])): ?>
            <span style="color: white; font-weight: bold;">👋 Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="logout_user.php" class="nav-btn">Logout</a>
        <?php else: ?>
            <a href="login_combined.php" class="nav-btn">Login</a>
            <a href="register.php" class="nav-btn">Register</a>
        <?php endif; ?>
    </div>
</header>
