<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../client/css/register.css">
</head>
<body>
<?php
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        header('Location: ../client/register.html');
        exit;
    }
    
    $db = new SQLite3('../database/database.db');
    
    // Receive inputs from client side
    $entered_username = $_POST['username'];
    $entered_email = $_POST['email'];
    $entered_password = $_POST['password'];
    
    if (!empty($entered_username) && !empty($entered_email) && !empty($entered_password)) {
        $register = 0;
        
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM Accounts WHERE username = :username");
        $stmt->bindValue(':username', $entered_username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray();
        $numRows = $row['count'];

        if ($numRows > 0) {
            echo "The user exists!";
        } else {
            $stmt = $db->prepare("INSERT INTO Accounts (username, password, email, role_id) VALUES (:username, :password, :email, 2)");
            $stmt->bindValue(':username', $entered_username, SQLITE3_TEXT);
            $stmt->bindValue(':password', $entered_password, SQLITE3_TEXT);
            $stmt->bindValue(':email', $entered_email, SQLITE3_TEXT);
            if ($stmt->execute()) {
                echo "The user has been added to the database.";
            } else {
                echo "Failed to add the user to the database.";
            }
        }

        echo "<br/><a href='../client/register.html'>Go Back</a>";
        
    } else {
        echo "Username, Email, and Password cannot be empty!";
    }
?>
</body>
</html>
