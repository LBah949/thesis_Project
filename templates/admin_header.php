<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../login_combined.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Dissertation Repo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .admin-container {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 220px;
    background: #1e3c72;
    color: white;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    padding: 20px;
}

.sidebar-logo {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 30px;
}

.sidebar-nav {
    list-style: none;
    padding: 0;
}

.sidebar-nav li {
    margin: 15px 0;
}

.sidebar-nav a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar-nav a:hover, .sidebar-nav a.active {
    background: #2a5298;
}

.admin-main {
    flex: 1;
    padding: 20px;
    background: #f5f6fa;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.admin-footer {
    text-align: center;
    background: #1e3c72;
    color: white;
    padding: 15px;
    position: fixed;
    bottom: 0;
    left: 220px;
    width: calc(100% - 220px);
}

/* Dashboard Cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.card i {
    color: #1e3c72;
    margin-bottom: 10px;
}
/* Make dashboard cards clickable */
.card-link {
    text-decoration: none;
    color: inherit;
}

.card-link .card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card-link .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}
/* Charts layout */
.dashboard-charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.chart-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="admin-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fa-solid fa-book"></i> <span>Admin Panel</span>
        </div>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="analytics.php" class="<?= basename($_SERVER['PHP_SELF']) === 'analytics.php' ? 'active' : '' ?>"><i class="fa-solid fa-chart-line"></i> Analytics</a></li>
            <li><a href="upload_dissertation.php" class="<?= basename($_SERVER['PHP_SELF']) === 'upload_dissertation.php' ? 'active' : '' ?>"><i class="fa-solid fa-upload"></i> Upload</a></li>
            <li><a href="manage_users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'manage_users.php' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Users</a></li>
            <li><a href="../index.php" target="_blank"><i class="fa-solid fa-eye"></i> Preview Site</a></li>
            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main content area -->
    <main class="admin-main">
        <div class="admin-header">
            <h2><?= ucfirst(basename($_SERVER['PHP_SELF'], '.php')) ?></h2>
            <span>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
        </div>
