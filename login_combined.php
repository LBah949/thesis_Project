<?php
session_start();
require_once 'includes/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; // student or admin

    if ($role === 'admin') {
        $query = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    } else {
        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    }

    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($role === 'admin') {
                $_SESSION['admin_username'] = $user['username'];
                header("Location: admin/dashboard.php");
                echo "Logged in as ADMIN: " . $_SESSION['admin_username']; exit;
            } else {
                $_SESSION['user_name'] = $user['username'];
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>

<?php include 'templates/header.php'; ?>

<style> /*
.login-container {
    max-width: 350px;
    margin: 60px auto;
    padding: 30px;
    background: #f4f4f4;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.login-container h2 {
    text-align: center;
    color: #1e3c72;
}
.login-container label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
}
.login-container input,
.login-container select {
    width: 90%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.password-wrapper {
    position: relative;
}
.toggle-password {
    position: absolute;
    top: 50%;
    right: 10px;
    cursor: pointer;
    transform: translateY(-50%);
}
.login-container input[type="submit"] {
    margin-top: 20px;
    margin-left: 10px;
    background-color: #1e3c72;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
.error {
    color: red;
    text-align: center;
} */
</style>

<div class="form-container">
    <h2>Login</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="">
        <label for="role">Login As</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select>

        <label for="username">Username</label>
        <input type="text" name="username" required>

        <label for="password">Password</label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword('password')"></span>
        </div>

        <input type="submit" value="Login">
    </form>

    <div style="text-align:center; margin-top: 15px;">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php include 'templates/footer.php'; ?>
