<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Enable PHP error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable('/var/www/html/app');
$dotenv->load();

// Database connection
$db = new SQLite3('../database/database.db');

function sendVerificationEmail($toEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['GMAIL_USERNAME'];
        $mail->Password = $_ENV['GMAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($_ENV['GMAIL_USERNAME'], 'Book Library Password Reset');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $resetLink = "https://ec2-34-229-175-78.compute-1.amazonaws.com/app/client/new_password.php?token=$token";

        $mail->Body = "<html><body><p>Click the following link to reset your password:</p><p><a href='$resetLink'>$resetLink</a></p></body></html>";
        $mail->AltBody = "Click the following link to reset your password: $resetLink";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $db->prepare("SELECT * FROM Accounts WHERE email = :email");
    $stmt->bindValue(':email', $entered_email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        // Generate a secure token
        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the token in the database
        $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $stmt->bindValue(':email', $entered_email, SQLITE3_TEXT);
        $stmt->bindValue(':token', $token, SQLITE3_TEXT);
        $stmt->bindValue(':expires_at', $expires_at, SQLITE3_TEXT);
        $stmt->execute();

        // Send verification email
        if (sendVerificationEmail($entered_email, $token)) {
            $message = "A password reset link has been sent to your email.";
        } else {
            $message = "Failed to send verification email. Please try again later.";
        }
    } else {
        $message = "No account found with that email address.";
    }
    // Provide feedback to the user
    echo "<html><head><link rel='stylesheet' href='../client/css/password_reset.css'></head><body><h3>{$message}</h3><p><a href='../client/password_reset.html'>Go back</a></p></body></html>";
}
?>
