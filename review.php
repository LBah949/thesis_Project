<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['dissertation_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Anonymous";

    $stmt = $conn->prepare("INSERT INTO reviews (dissertation_id, rating, comment, user_name)
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id, $rating, $comment, $user_name);
    $stmt->execute();

    header("Location: view.php?id=" . $id);
    exit();
}
