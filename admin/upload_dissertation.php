<?php session_start(); if (!isset($_SESSION['admin_username'])) { header("Location: ../login_combined.php"); exit(); }
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $supervisor = $_POST['supervisor']; // Added supervisor field
    $department = $_POST['department'];
    $year = $_POST['year'];
    $abstract = trim($_POST['abstract']);

    $pdf = $_FILES['pdf_file'];
    $filename = time() . "_" . basename($pdf['name']);
    $target = "../dissertations/" . $filename;

    if (move_uploaded_file($pdf['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO dissertations (title, author, supervisor, department, year, abstract, filename, upload_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $title, $author, $supervisor, $department, $year, $abstract, $filename);
        
        $stmt->execute();
        $stmt->close();

        header("Location: dashboard.php?success=1");
        exit();
    } else {
        echo "<p style='color:red;'>Upload failed. Please try again.</p>";
    }
}

 ?>
<?php include '../templates/admin_header.php'; ?>
<body>

<h2>Upload New Dissertation</h2>
<div class="form-container">
<form method="POST" enctype="multipart/form-data" action="upload_dissertation.php">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Author(s)</label>
    <input type="text" name="author" required>

    <label>Supervisor</label>
    <input type="text" name="supervisor" required>


    <label>Department</label>
    <input type="text" name="department" required>

    <label>Year</label>
    <input type="number" name="year" required>

    <label>Abstract</label>
    <textarea name="abstract" rows="5" required></textarea>

    <label>PDF File</label>
    <input type="file" name="pdf_file" accept="application/pdf" required>

    <input type="submit" value="Upload">
</form>
</div>
<br><br>
<a href="export.php">Export to CSV</a> | <a href="../logout.php">Logout</a>
<?php include '../templates/admin_footer.php'; ?>
