<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_username'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// Get filename to delete file
$stmt = $conn->prepare("SELECT filename FROM dissertations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    $file_path = "../dissertations/" . $row['filename'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete DB record
    $del = $conn->prepare("DELETE FROM dissertations WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
}

header("Location: dashboard.php");
exit();
