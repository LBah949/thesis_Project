
<?php
session_start();
require_once 'includes/db.php';

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT d.*, (SELECT ROUND(AVG(rating), 1) FROM reviews WHERE dissertation_id = d.id) AS avg_rating 
        FROM dissertations d ORDER BY upload_date DESC LIMIT $limit OFFSET $offset";
// Prepare filtering query
$where = [];
$params = [];
$types = "";

if (!empty($_GET['department'])) {
    $where[] = "department = ?";
    $params[] = $_GET['department'];
    $types .= "s";
}

if (!empty($_GET['year'])) {
    $where[] = "year = ?";
    $params[] = $_GET['year'];
    $types .= "i";
}

if (!empty($_GET['author'])) {
    $where[] = "author LIKE ?";
    $params[] = "%" . $_GET['author'] . "%";
    $types .= "s";
}

if (!empty($_GET['supervisor'])) {
    $where[] = "supervisor LIKE ?";
    $params[] = "%" . $_GET['supervisor'] . "%";
    $types .= "s";
}

$sql = "SELECT * FROM dissertations";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY upload_date DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

?>

<?php include 'templates/header.php'; ?>
<!DOCTYPE html>
<head>
    <style>
        .dissertation-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}

.dissertation-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    background: #fff;
    padding: 15px;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    min-height: 220px;   /* make the card a bit taller */
    position: relative;  /* needed for the hover preview */
    overflow: hidden;    /* keeps content neatly inside */
}


.dissertation-card:hover {
    transform: translateY(-3px);
    box-shadow: 0px 4px 12px rgba(0,0,0,0.15);
}

.dissertation-card h3 a {
    text-decoration: none;
    color: #0073e6;
}

.dissertation-card h3 a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1 class="typewriter-text" id="typedText"></h1>
        <p><i>The first ever Digital Dissertation Repository at IPAM-USL </i></p>
        <a href="#dissertations" class="hero-btn">Explore Dissertations</a>
    </div>
</section>
<br>
<a id="dissertations"></a>

    <h1>Dissertation Catalogue</h1>
    <div class="filter-box">
    <form method="GET" action="index.php" class="filter-form">
        <div class="filter-group">
            <select name="department">
                <option value="">All Departments</option>
                <?php
                $departments = $conn->query("SELECT DISTINCT department FROM dissertations ORDER BY department ASC");
                $selectedDept = $_GET['department'] ?? '';
                while ($dept = $departments->fetch_assoc()):
                    $value = htmlspecialchars($dept['department']);
                    $selected = ($selectedDept === $dept['department']) ? 'selected' : '';
                    echo "<option value=\"$value\" $selected>$value</option>";
                endwhile;
                ?>

            </select>

            <select name="year">
                <option value="">All Years</option>
                <?php
                $years = $conn->query("SELECT DISTINCT year FROM dissertations ORDER BY year DESC");
                $selectedYear = $_GET['year'] ?? '';
                while ($yr = $years->fetch_assoc()):
                    $yearVal = $yr['year'];
                    $selected = ($selectedYear == $yearVal) ? 'selected' : '';
                    echo "<option value=\"$yearVal\" $selected>$yearVal</option>";
                endwhile;
                ?>
            </select>


            <input type="text" name="author" placeholder="Search by Author"
        value="<?= htmlspecialchars($_GET['author'] ?? '') ?>">
            <input type="text" name="supervisor" placeholder="Search by Supervisor"
        value="<?= htmlspecialchars($_GET['supervisor'] ?? '') ?>">


            <input type="submit" value="Filter">
        </div>
    </form>
</div>

<div class="dissertation-grid">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="dissertation-card" style="border: 1px solid #ccc; margin: 10px 0; padding: 10px;">
        <h3><a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
        <p><strong>Author:</strong> <?= htmlspecialchars($row['author']) ?> | 
           <strong>Department:</strong> <?= htmlspecialchars($row['department']) ?> | 
           <strong>Year:</strong> <?= htmlspecialchars($row['year']) ?></p>
           <p><strong>Supervisor:</strong> <?= htmlspecialchars($row['supervisor']) ?></p>


        <a href="dissertations/<?= $row['filename'] ?>" download>Download PDF</a>
        <span id="view-details">
            <a href="view.php?id=<?= $row['id'] ?>">View Details</a>
        </span>
    </div>
<?php endwhile; ?>
</div>
    <div id="results"></div>

    <script>
    document.getElementById("filterForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("filter.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById("results").innerHTML = html;
        })
        .catch(err => {
            document.getElementById("results").innerHTML = "<p>Something went wrong.</p>";
            console.error(err);
        });
        });

        document.getElementById("filterForm").dispatchEvent(new Event("submit"));
    </script>
    <script>
    const text = "Welcome to the IT/IS Dissertation Repository";
    let i = 0;
    const speed = 50;

    function typeWriter() {
        if (i < text.length) {
            document.getElementById("typedText").innerHTML += text.charAt(i);
            i++;
            setTimeout(typeWriter, speed);
        }
    }

    window.onload = typeWriter;
</script>

</body>
</html>
