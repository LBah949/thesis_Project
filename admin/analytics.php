<?php include '../templates/admin_header.php'; ?>
<?php require_once '../includes/db.php'; ?>


<canvas id="dissertationsPerYear" height="100"></canvas>
<canvas id="departmentDistribution" height="100"></canvas>
<canvas id="topRated" height="100"></canvas>

<?php
// Fetch data for charts
$years = $conn->query("SELECT year, COUNT(*) as total FROM dissertations GROUP BY year ORDER BY year ASC");
$departments = $conn->query("SELECT department, COUNT(*) as total FROM dissertations GROUP BY department ORDER BY total DESC");
$topRated = $conn->query("SELECT title, AVG(rating) as avg_rating FROM reviews JOIN dissertations d ON reviews.dissertation_id=d.id GROUP BY dissertation_id ORDER BY avg_rating DESC LIMIT 5");

$yearData = [];
while ($row = $years->fetch_assoc()) {
    $yearData[$row['year']] = $row['total'];
}

$deptData = [];
while ($row = $departments->fetch_assoc()) {
    $deptData[$row['department']] = $row['total'];
}

$ratingData = [];
while ($row = $topRated->fetch_assoc()) {
    $ratingData[$row['title']] = round($row['avg_rating'],1);
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dissertations per year
new Chart(document.getElementById("dissertationsPerYear"), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($yearData)) ?>,
        datasets: [{
            label: 'Dissertations',
            data: <?= json_encode(array_values($yearData)) ?>,
            borderColor: '#1e3c72',
            fill: false
        }]
    }
});

// Department distribution
new Chart(document.getElementById("departmentDistribution"), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($deptData)) ?>,
        datasets: [{
            label: 'By Department',
            data: <?= json_encode(array_values($deptData)) ?>,
            backgroundColor: '#2a5298'
        }]
    }
});

// Top rated dissertations
new Chart(document.getElementById("topRated"), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($ratingData)) ?>,
        datasets: [{
            label: 'Avg Rating',
            data: <?= json_encode(array_values($ratingData)) ?>,
            backgroundColor: ['#1e3c72','#2a5298','#4a6fa5','#89a3d0','#c2d3f0']
        }]
    }
});
</script>

<?php include '../templates/admin_footer.php'; ?>
