
<link rel="stylesheet" href="../assets/css/admin.css">
<?php include '../templates/admin_header.php'; ?>
<?php require_once '../includes/db.php'; ?>

<div class="dashboard-cards">

    <!-- Total Dissertations -->
    <a href="upload_dissertation.php" class="card-link">
    <div class="card">
        <i class="fa-solid fa-book fa-2x"></i>
        <h3>Total Dissertations</h3>
        <p>
            <?php
            $count = $conn->query("SELECT COUNT(*) AS total FROM dissertations")->fetch_assoc();
            echo $count['total'];
            ?>
        </p>
    </div> </a>

    <!-- Total Users -->
    <a href="manage_users.php" class="card-link">
    <div class="card">
        <i class="fa-solid fa-users fa-2x"></i>
        <h3>Total Users</h3>
        <p>
            <?php
            $count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc();
            echo $count['total'];
            ?>
        </p>
    </div> </a>

    <!-- Most Recent Upload -->
    <a href="view_dissertation.php?id=<?= $latest['id'] ?>" class="card-link">
    <div class="card">
        <i class="fa-solid fa-clock fa-2x"></i>
        <h3>Latest Upload</h3>
        <p>
            <?php
            $latest = $conn->query("SELECT title FROM dissertations ORDER BY upload_date DESC LIMIT 1")->fetch_assoc();
            echo $latest ? htmlspecialchars($latest['title']) : "No uploads yet";
            ?>
        </p>
    </div> </a>

    <!-- Most Liked Dissertation -->
    <a href="view_dissertation.php?id=<?= $liked['id'] ?>" class="card-link">
    <div class="card">
        <i class="fa-solid fa-thumbs-up fa-2x"></i>
        <h3>Most Liked</h3>
        <p>
            <?php
            $liked = $conn->query("SELECT d.title, COUNT(r.id) as likes FROM reviews r JOIN dissertations d ON d.id = r.dissertation_id GROUP BY r.dissertation_id ORDER BY likes DESC LIMIT 1")->fetch_assoc();
            echo $liked && $liked['title'] ? htmlspecialchars($liked['title']) . " ({$liked['likes']} likes)" : "No likes yet";
            ?>
    </p>
    </div>

    <hr>

    <!-- 📊 Analytics Section Inside Dashboard -->
    <h2>Analytics Overview</h2>
    <div class="dashboard-charts">
        <div class="chart-card">
            <canvas id="chartDepartments"></canvas>
        </div>
        <div class="chart-card">
            <canvas id="chartYears"></canvas>
        </div>
        <div class="chart-card">
            <canvas id="chartLikes"></canvas>
        </div>
    </div>

</div>
<script>
// Chart 1: Dissertations by Department
const deptCtx = document.getElementById('chartDepartments');
new Chart(deptCtx, {
    type: 'pie',
    data: {
        labels: [
            <?php
            $res = $conn->query("SELECT department, COUNT(*) as total FROM dissertations GROUP BY department");
            while ($row = $res->fetch_assoc()) {
                echo "'" . $row['department'] . "',";
            }
            ?>
        ],
        datasets: [{
            data: [
                <?php
                $res = $conn->query("SELECT department, COUNT(*) as total FROM dissertations GROUP BY department");
                while ($row = $res->fetch_assoc()) {
                    echo $row['total'] . ",";
                }
                ?>
            ],
            backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']
        }]
    }
});

// Chart 2: Dissertations by Year
const yearCtx = document.getElementById('chartYears');
new Chart(yearCtx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            $res = $conn->query("SELECT year, COUNT(*) as total FROM dissertations GROUP BY year ORDER BY year ASC");
            while ($row = $res->fetch_assoc()) {
                echo "'" . $row['year'] . "',";
            }
            ?>
        ],
        datasets: [{
            label: 'Dissertations',
            data: [
                <?php
                $res = $conn->query("SELECT year, COUNT(*) as total FROM dissertations GROUP BY year ORDER BY year ASC");
                while ($row = $res->fetch_assoc()) {
                    echo $row['total'] . ",";
                }
                ?>
            ],
            backgroundColor: '#36b9cc'
        }]
    }
});

// Chart 3: Top Liked Dissertations
const likesCtx = document.getElementById('chartLikes');
new Chart(likesCtx, {
    type: 'line',
    data: {
        labels: [
            <?php
            $res = $conn->query("SELECT title, like_count FROM dissertations ORDER BY like_count DESC LIMIT 5");
            while ($row = $res->fetch_assoc()) {
                echo "'" . substr($row['title'], 0, 15) . "…',";
            }
            ?>
        ],
        datasets: [{
            label: 'Likes',
            data: [
                <?php
                $res = $conn->query("SELECT title, like_count FROM dissertations ORDER BY like_count DESC LIMIT 5");
                while ($row = $res->fetch_assoc()) {
                    echo $row['like_count'] . ",";
                }
                ?>
            ],
            borderColor: '#e74a3b',
            backgroundColor: 'rgba(231,74,59,0.3)',
            fill: true
        }]
    }
});
</script>

<?php include '../templates/admin_footer.php'; ?>

