<?php
session_start();
require 'includes/database.php';
require 'vendor/autoload.php';
include 'includes/header.php';
date_default_timezone_set("Asia/Kolkata"); // Change this to your correct timezone

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    mysqli_query($conn, "UPDATE user SET reset_token = NULL, reset_expiry = NULL WHERE reset_expiry < NOW()");

    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50));
         
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        
        $update = "UPDATE user SET reset_token='$token', reset_expiry='$expiry' WHERE email='$email'";
        mysqli_query($conn, $update);
        
        sendPasswordResetEmail($email, $token);
        echo "<script>alert('Password reset link has been sent to your email.'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Email not found! Please try again.'); window.location.href = 'forgot_password.php';</script>";
    }
}

function sendPasswordResetEmail($to, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com';
        $mail->Password = 'sftm blky plvr lyvc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
        $mail->addAddress($to);
        
        $resetLink = "http://localhost/bookmystay/reset_password.php?token=$token";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "<p>Click the link below to reset your password (Valid for 10 minutes)</p><p><a href='$resetLink'>$resetLink</a></p>";
        
        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
</head>
<body>
<div class="auth-container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required />
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
    <p><a href="index.php">Back to Login</a></p>
</div>
</body>
</html>
<?php
    include 'includes/footer.php';
?>
