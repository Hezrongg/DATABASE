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
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $sql->bind_param("ss", $username, $role);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: menu.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('No user found');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
  <h2>☕Brewha Coffee Shop</h2>
  <form action="login.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="admin">Admin</option>
      <option value="cashier">Cashier</option>
    </select>
    <button type="submit">Login</button>
    <p>Don't have an account? <a href="register.php">Sign up</a></p>
  </form>
</div>
</body>
</html>
