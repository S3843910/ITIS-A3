<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password</title>
    <link rel="stylesheet" href="./css/password_reset.css">
</head>
<body>
    <h2>Create New Password</h2>
    <form action="../server/password_reset.php" method="POST" onsubmit="return hashPassword()">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        New Password: <input type="password" id="password" name="password" required>
        <br><br>
        Confirm Password: <input type="password" id="confirm_password" name="confirm_password" required>
        <br><br>
        <button type="submit">Reset Password</button>
    </form>
    <script src="js/sha256.js"></script>
    <script type="text/javascript">
        function hashPassword() {
            var newPassword = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            var hashedPassword = SHA256.hash(newPassword);

            document.getElementById("password").value = hashedPassword;
            document.getElementById('confirm_password').value = hashedPassword;
            return true;
        }
    </script>
</body>
</html>
