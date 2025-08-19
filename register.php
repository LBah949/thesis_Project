<?php
session_start();
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check for existing user
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed);

            if ($insert->execute()) {
                $success = "Registration successful. You may now <a href='login_combined.php'>login</a>.";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<?php include 'templates/header.php'; ?>

<style>
/* Register Container 
.register-container {
    max-width: 400px;
    margin: 40px auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', sans-serif;
}
.register-container h2 {
    text-align: center;
    color: #1e3c72;
}
.register-container label {
    font-weight: bold;
    margin-top: 15px;
    display: block;
}
.register-container input {
    width: 95%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.register-container .password-wrapper {
    position: relative;
}
.register-container .toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}
.register-container input[type="submit"] {
    margin-top: 25px;
    margin-left: 10px;
    background-color: #1e3c72;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
.success {
    color: green;
    text-align: center;
}
.error {
    color: red;
    text-align: center;
} */
</style>

<div class="form-container">
    <h2>User Registration</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>

    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="password">Password</label>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword('password')"></span>
 </div>

        <label for="confirm_password">Confirm Password</label>
        <div class="password-wrapper">
            <input type="password" id="confirm" name="confirm_password" required>
            <span class="toggle-password" onclick="togglePassword('confirm')"></span>
        </div>

        <input type="submit" value="Register">
    </form>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php include 'templates/footer.php'; ?>
