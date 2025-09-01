<?php
session_start();
error_reporting(E_ALL);
include 'includes/header.php';
require 'includes/database.php';
date_default_timezone_set("Asia/Kolkata");
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    $sql = "SELECT * FROM user WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<script>alert('Invalid or expired token!'); window.location.href = 'index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No token provided!'); window.location.href = 'index.php';</script>";
    exit();
}

if (isset($_POST['reset_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $user_query = "SELECT email FROM user WHERE reset_token = '$token'";
        $user_result = mysqli_query($conn, $user_query);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user_data = mysqli_fetch_assoc($user_result);
            $email = $user_data['email'];

            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE user SET `password`='$hashed_password', reset_token=NULL, reset_expiry=NULL WHERE reset_token='$token'";

            if (mysqli_query($conn, $update_sql)) {
                echo "<script>alert('Password reset successfully! Redirecting to login...'); window.location.href = 'login.php';</script>";
                sendPasswordResetConfirmation($email); // Send email after redirect
                exit();
            } else {
                error_log("Error: " . mysqli_error($conn));
                echo "<script>alert('Error resetting password! Please try again.');</script>";
                exit();
            }
        } else {
            echo "<script>alert('Invalid token!'); window.location.href = 'index.php';</script>";
            exit();
        }
    }
}

function sendPasswordResetConfirmation($to) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com';
        $mail->Password = 'sftm blky plvr lyvc'; // Replace with your actual Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Successful - Book My Stay';
        $mail->Body = "<h1>Password Reset Successful</h1>
                       <p>Your password has been changed successfully.</p>
                       <p>Thank you for using Book My Stay. We hope you have a great experience.</p>";

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
    <title>Reset Password - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
</head>
<body>
<div class="auth-container">
    <h2>Reset Password</h2>
    <form method="POST">
        <label>New Password</label>
        <input type="password" name="new_password" placeholder="Enter new password" required />
        
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required />
        
        <button type="submit" name="reset_password">Reset Password</button>
        <p><a href="login.php">Back to Login</a></p>
    </form>
</div>
</body>
</html>
<?php
    include 'includes/footer.php';
?>
