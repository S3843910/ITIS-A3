<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role_id'] !== 1) {
    header('Location: ../client/login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../client/css/admin_page.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin!</p>
    <!-- Admin-specific content here -->
    <a href="logout.php">Logout</a>
</body>
</html1