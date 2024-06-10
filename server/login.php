<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../client/css/login.css">
</head>
<body>

<?php
if (!isset($_POST['username'])) {
    header('Location: ../client/login.html');
    exit;
}

$db = new SQLite3('../database/database.db');

// Receive username from client side
$entered_username = $_POST['username'];
// Receive password from client side
$entered_password = $_POST['password'];

if (!empty($entered_username) && !empty($entered_password)) {
    $stmt = $db->prepare("SELECT * FROM Accounts WHERE username = :username AND password = :password");
    $stmt->bindValue(':username', $entered_username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $entered_password, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        $_SESSION['login'] = "YES";
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        header('Location: content.php');
        exit;
    } else {
        echo "Wrong Username or Password!";
        echo "<br/><a href='../client/login.html'>Go Back</a>";
    }
} else {
    echo "Username and Password cannot be empty!";
    echo "<br/><a href='../client/login.html'>Go Back</a>";
}
?>

</body>
</html>
