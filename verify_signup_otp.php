<?php
session_start();
require 'includes/database.php';
require 'vendor/autoload.php';
include 'includes/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['signup_otp']) || !isset($_SESSION['signup_email'])) {
    header("Location: signup.php");
    exit();
}

$email = $_SESSION['signup_email'];

// Initialize OTP attempt counter if not set
if (!isset($_SESSION['otp_attempts'])) {
    $_SESSION['otp_attempts'] = 0;
}

if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);

    // Check if OTP is expired (10 minutes)
    if (time() - $_SESSION['signup_otp_time'] > 600) { // 600 seconds = 10 minutes
        unset($_SESSION['signup_otp']);
        unset($_SESSION['signup_otp_time']);
        echo "<script>alert('OTP expired! Please request a new OTP.'); setTimeout(function() { window.location.href = 'verify_signup_otp.php'; }, 1000);</script>";
        exit();
    }

    // Check if user exceeded maximum OTP attempts
    if ($_SESSION['otp_attempts'] >= 5) {
        echo "<script>alert('Too many incorrect attempts! Please request a new OTP.');</script>";
        exit();
    }

    if ($entered_otp == $_SESSION['signup_otp']) {
        if (!isset($_SESSION['signup_data'])) {
            echo "<script>alert('Session expired! Please sign up again.'); window.location.href = 'signup.php';</script>";
            exit();
        }

        $userData = $_SESSION['signup_data'];

        // Insert user into database
        $sql = "INSERT INTO user (username, fname, lname, email, phno, address, dob, password) 
                VALUES ('{$userData['username']}', '{$userData['fname']}', '{$userData['lname']}', 
                '{$userData['email']}', '{$userData['phno']}', '{$userData['add']}', 
                '{$userData['dob']}', '{$userData['hashedpassword']}')";

        if (mysqli_query($conn, $sql)) {
            // Clear session data
            unset($_SESSION['signup_otp']);
            unset($_SESSION['signup_otp_time']);
            unset($_SESSION['signup_email']);
            unset($_SESSION['signup_data']);
            unset($_SESSION['otp_attempts']); // Reset attempt counter

            echo "<script>alert('Signup Successful! Redirecting to login...'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            error_log("Database Error: " . mysqli_error($conn));
            echo "<script>alert('Signup failed! Please try again.');</script>";
        }
    } else {
        $_SESSION['otp_attempts']++; // Increment failed attempts
        echo "<script>alert('Invalid OTP! Attempts left: " . (5 - $_SESSION['otp_attempts']) . "');</script>";
    }
}

// Resend OTP with cooldown
if (isset($_POST['resend_otp'])) {
    if (isset($_SESSION['last_otp_request']) && (time() - $_SESSION['last_otp_request'] < 30)) {
        echo "<script>alert('Please wait 30 seconds before requesting a new OTP.');</script>";
    } else {
        $_SESSION['signup_otp'] = rand(100000, 999999);
        $_SESSION['signup_otp_time'] = time(); // Reset OTP time
        $_SESSION['last_otp_request'] = time(); // Store last request time
        sendSignupOTP($email, $_SESSION['signup_otp']);
        $_SESSION['otp_attempts'] = 0; // Reset attempt counter
        echo "<script>alert('A new OTP has been sent to your email.');</script>";
    }
}

function sendSignupOTP($to, $otp) {
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

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Signup - Book My Stay';
        $mail->Body = "<h1>OTP: $otp</h1><p>Please enter this OTP to verify your signup.</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("OTP Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
</head>
<body>
<div class="auth-container">
    <h2>Enter OTP</h2>
    <form method="post">
        <label>Enter the OTP sent to your email</label>
        <input type="text" name="otp" required />
        <button type="submit" name="verify_otp">Verify OTP</button>
    </form>
    <form method="post">
        <button type="submit" name="resend_otp">Resend OTP</button>
    </form>
</div>
</body>
</html>
<?php
    include 'includes/footer.php';
?>
