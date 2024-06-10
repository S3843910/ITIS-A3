<?php
require '../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable('/var/www/html/app');
$dotenv->load();

// Database connection
$db = new SQLite3('../database/database.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Verify token and check expiration
    $stmt = $db->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > datetime('now')");
  
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
 

    $result = $stmt->execute();
   
    $user = $result->fetchArray(SQLITE3_ASSOC);

  if ($user) {
        $email = $user['email'];

        

        // Update the user's password in the database
        $stmt = $db->prepare("UPDATE Accounts SET password = :password WHERE email = :email");
        $stmt->bindValue(':password', $new_password, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->execute();

        // Delete the token from the database
        $stmt = $db->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->bindValue(':token', $token, SQLITE3_TEXT);
        $stmt->execute();

        echo "Your password has been reset successfully.";
        echo '<br> <button onclick="window.location.href = \'../client/login.html\';">Go to Login</button>';

    } else {
        echo "Invalid or expired token.";
    }






}
?>