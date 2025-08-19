
<?php
session_start(); require_once 'includes/db.php';
if (isset($_POST['upload']) && isset($_SESSION['admin'])) {
    $title = $_POST['title']; $author = $_POST['author'];
    $dept = $_POST['department']; $year = $_POST['year'];
    $abstract = trim($_POST['abstract']);
    $pdf = $_FILES['pdf']; $filename = time() . "_" . basename($pdf['name']);
    $target = "dissertations/" . $filename;
    if (move_uploaded_file($pdf['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO dissertations (title, author, department, year, abstract, filename, upload_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $title, $author, $department, $year, $abstract, $filename);

        header("Location: admin/dashboard.php?success=1");
    } else { echo "Upload failed!"; }
}
