
<?php
require_once '../includes/db.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="dissertations.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Title', 'Author', 'Department', 'Year', 'Filename', 'Upload Date']);
$result = $conn->query("SELECT * FROM dissertations");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['title'], $row['author'], $row['department'], $row['year'], $row['filename'], $row['upload_date']]);
}
fclose($output);
