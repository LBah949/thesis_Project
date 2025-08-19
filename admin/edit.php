<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: ../login_combined.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// Fetch record
$stmt = $conn->prepare("SELECT * FROM dissertations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $department = $_POST['department'];
    $year = $_POST['year'];

    $update = $conn->prepare("UPDATE dissertations SET title=?, author=?, department=?, year=? WHERE id=?");
    $update->bind_param("ssssi", $title, $author, $department, $year, $id);
    $update->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Dissertation</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<h2>Edit Dissertation</h2>
<div class="form-container">
<form method="POST">
    <input type="text" name="title" value="<?= $row['title'] ?>" required><br>
    <input type="text" name="author" value="<?= $row['author'] ?>" required><br>
    <input type="text" name="department" value="<?= $row['department'] ?>" required><br>
    <input type="number" name="year" value="<?= $row['year'] ?>" required><br>
    <button type="submit">Save Changes</button>
</form>
</div>
<br><br>
<span id="back-link">
<a href="dashboard.php">⬅ Back to Dashboard</a>
</span>
</body>

</html>
