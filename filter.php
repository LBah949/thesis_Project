
<?php
require_once 'includes/db.php';
$author = $_POST['author'] ?? '';
$department = $_POST['department'] ?? '';
$year = $_POST['year'] ?? '';
$filter = []; $params = []; $types = '';
if (!empty($author)) { $filter[] = "author LIKE ?"; $params[] = "%$author%"; $types .= "s"; }
if (!empty($department)) { $filter[] = "department = ?"; $params[] = $department; $types .= "s"; }
if (!empty($year)) { $filter[] = "year = ?"; $params[] = $year; $types .= "s"; }
$sql = "SELECT d.*, (SELECT ROUND(AVG(rating),1) FROM reviews WHERE dissertation_id = d.id) as avg_rating FROM dissertations d";
if (!empty($filter)) $sql .= " WHERE " . implode(" AND ", $filter);
$sql .= " ORDER BY upload_date DESC";
$stmt = $conn->prepare($sql); if ($params) $stmt->bind_param($types, ...$params); $stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) { echo "<p>No dissertations found.</p>"; exit; }
while ($row = $result->fetch_assoc()): ?>

<div class="card">
<h3><?= htmlspecialchars($row['title']) ?></h3>
<p><strong>Author:</strong> <?= htmlspecialchars($row['author']) ?></p>
<p><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></p>
<p><strong>Year:</strong> <?= htmlspecialchars($row['year']) ?></p>
<p><strong>Rating:</strong> <?= $row['avg_rating'] ? $row['avg_rating'] . "/5" : "No ratings yet" ?></p>
<a href="view.php?id=<?= $row['id'] ?>">View Details</a> |
<a href="dissertations/<?= $row['filename'] ?>" target="_blank">Download</a>
</div>

<?php endwhile; ?>
