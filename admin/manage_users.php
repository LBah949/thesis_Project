<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: ../login_combined.php");
    exit;
}

require_once '../includes/db.php';
include '../templates/admin_header.php';

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $message = "User deleted successfully.";
}
?>

<div style="max-width: 1000px; margin: auto;">
    <h2 style="text-align:center;">Manage Registered Users</h2>

    <?php if (!empty($message)): ?>
        <p style="color: green; text-align:center;"><?= $message ?></p>
    <?php endif; ?>

    <table class="user-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
            $sn = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $sn++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <a class="action-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../templates/admin_footer.php'; ?>
