<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');window.location='register.php';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare insert statement
    $sql = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($sql->execute()) {
        echo "<script>alert('Account created successfully!');window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: Username or Email already exists');window.location='register.php';</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up POS</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
  <h2>Create Account</h2>
  <form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="admin">Admin</option>
      <option value="cashier">Cashier</option>
    </select>
    <button type="submit">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>
</body>
</html>
