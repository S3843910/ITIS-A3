<?php
    session_start();
    
    // Unset the login session variable
    unset($_SESSION['login']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link rel="stylesheet" href="../client/css/logout.css">
</head>
<body>
    <h1>Logout</h1>
    <p>You have logged out. You cannot access the <a href="content.php">Book library page</a> right now.</p>
    <p>If you try to access the content page without logging in successfully, you will be redirected to the <a href="../client/login.html">login page</a>.</p>
</body>
</html>
