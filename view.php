
<?php
require_once 'includes/db.php';
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM dissertations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$dissertation = $stmt->get_result()->fetch_assoc();

$reviewStmt = $conn->prepare("SELECT * FROM reviews WHERE dissertation_id = ?");
$reviewStmt->bind_param("i", $id);
$reviewStmt->execute();
$reviews = $reviewStmt->get_result();

$avgStmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE dissertation_id = ?");
$avgStmt->bind_param("i", $id);
$avgStmt->execute();
$avg_rating = $avgStmt->get_result()->fetch_assoc()['avg_rating'] ?? 0;
?>

<?php include 'templates/header.php'; ?>
<body>
    <style>
        .star-rating {
            direction: ltr;
            display: inline-block;
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            user-select: none;
        }
        .star-rating span {
            padding: 5px;
        }
        .star-rating span.selected,
        .star-rating span:hover,
        .star-rating span:hover ~ span {
            color: gold;
        }
        .review {
            background: #f1f1f1;
            padding: 10px;
            margin-top: 10px;
            border-left: 4px solid #007BFF;
            border-radius: 4px;
        }
    </style>
<h2>Dissertation Details</h2>
<h2><?= htmlspecialchars($dissertation['title']) ?></h2>
<p><strong>Author(s):</strong> <?= htmlspecialchars($dissertation['author']) ?></p>
<p><strong>Department:</strong> <?= htmlspecialchars($dissertation['department']) ?></p>
<p><strong>Year:</strong> <?= htmlspecialchars($dissertation['year']) ?></p>
<p><strong>Supervisor:</strong> <?= htmlspecialchars($dissertation['supervisor']) ?></p>

<a href="dissertations/<?= $dissertation['filename'] ?>" target="_blank">Download PDF</a>
<hr>
<h3>Abstract</h3>
<p style="line-height: 1.6;"><?= nl2br(htmlspecialchars($dissertation['abstract'])) ?></p>
<hr>
<h3>Leave a Review</h3>
<form method="POST" action="review.php" id="reviewForm">
    <input type="hidden" name="dissertation_id" value="<?= $id ?>">
    <input type="hidden" name="rating" id="ratingValue" value="0">

    <label>Rating:</label>
    <div class="star-rating">
        <span data-value="1">★</span>
        <span data-value="2">★</span>
        <span data-value="3">★</span>
        <span data-value="4">★</span>
        <span data-value="5">★</span>
    </div><br>

    <label>Comment:</label><br>
    <textarea name="comment" required></textarea><br>
    <button type="submit">Submit Review</button>
</form>

<hr>
<h3>Reviews</h3>
<?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review">
            <strong><?= $r['rating'] ?>/5</strong> by <?= htmlspecialchars($r['user_name'] ?? 'Anonymous') ?>
            <p><?= htmlspecialchars($r['comment']) ?></p>
        </div>
    <?php endwhile; ?>

    <!--Inline JavaScript-->
<script>
    const stars = document.querySelectorAll('.star-rating span');
    const ratingValue = document.getElementById('ratingValue');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const val = this.getAttribute('data-value');
            ratingValue.value = val;

            stars.forEach(s => {
                s.classList.remove('selected');
            });

            for (let i = 0; i < val; i++) {
                stars[i].classList.add('selected');
            }
        });
    });
</script>

<?php include 'templates/footer.php'; ?>

