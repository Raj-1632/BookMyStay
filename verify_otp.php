<?php
session_start();
include 'includes/header.php';
require 'vendor/autoload.php'; 
require 'includes/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect if OTP session is not set
if (!isset($_SESSION['login_otp']) || !isset($_SESSION['otp_email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['otp_email']; 
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify_otp'])) {
        $entered_otp = trim($_POST['otp']);

        // Check if OTP has expired
        if (time() > $_SESSION['otp_expiry']) {
            $error_message = "OTP has expired. Please request a new one.";
            unset($_SESSION['login_otp']);
            unset($_SESSION['otp_expiry']);
        } elseif ($entered_otp === strval($_SESSION['login_otp'])) { // Strict OTP check
            $_SESSION['authenticated'] = true; 

            // Fetch user details securely
            $query = "SELECT username FROM user WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['username'] = $user['username']; 
            }

            // Clear OTP session data
            unset($_SESSION['login_otp']);
            unset($_SESSION['otp_email']);
            unset($_SESSION['otp_expiry']);

            // Send successful login email
            sendLoginEmail($email);

            // Redirect to homepage
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid OTP! Please try again.";
        }
    }

    // Resend OTP
    if (isset($_POST['resend_otp'])) {
        if (!isset($_SESSION['otp_email'])) {
            header("Location: login.php");
            exit();
        }
    
        $new_otp = rand(100000, 999999);
        $_SESSION['login_otp'] = $new_otp;
        $_SESSION['otp_expiry'] = time() + 600; // Reset expiry time
    
        if (sendOTPEmail($email, $_SESSION['username'], $new_otp)) {
            $error_message = "A new OTP has been sent to your email.";
        } else {
            $error_message = "Error sending OTP. Please try again.";
        }
    }
    
}

// Function to send successful login email
function sendLoginEmail($to) {
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
        $mail->Subject = 'Successful Login - Book My Stay';
        $mail->Body = "
            <h1>Login Successful</h1>
            <p>Hello,</p>
            <p>Your account was successfully logged in at <strong>Book My Stay</strong>.</p>
            <p>If this wasn't you, please change your password immediately.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Login email could not be sent. Error: {$mail->ErrorInfo}");
    }
}

// Function to send OTP via email
function sendOTPEmail($to, $username, $otp) {
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
        $mail->Subject = 'Your OTP for Login - Book My Stay';
        $mail->Body = "
            <h1>Hello, $username!</h1>
            <p>Your One-Time Password (OTP) for login is: <strong>$otp</strong>(Valid for 10 minutes)</p>
            <p>Please enter this OTP to complete your login.</p>
        ";

        return $mail->send();
    } catch (Exception $e) {
        error_log("OTP Email could not be sent. Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
    <style>
        .msg{
            margin-top : 10px;
            margin-bottom : 10px;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <h2>Enter OTP</h2>
    <div class="msg">
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    </div>
    
    <form method="post">
        <label>Enter the OTP sent to your email</label>
        <input type="text" name="otp" placeholder="Enter OTP" required />
        <button type="submit" name="verify_otp">Verify OTP</button>
    </form>

    <form method="post" style="margin-top: 10px;">
        <button type="submit" name="resend_otp">Resend OTP</button>
    </form>
</div>

</body>
</html>

<?php include 'includes/footer.php'; ?>
