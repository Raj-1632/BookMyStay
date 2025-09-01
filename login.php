<?php
error_reporting(0);
session_start();
require 'includes/database.php';
include 'includes/header.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate Email Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: login.php");
        exit();
    }

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: login.php");
        exit();
    }

    // Fetch user from database
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['otp_email'] = $email;

            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            $_SESSION['login_otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 600;
            
            // Send OTP to email
            if (sendOTPEmail($email, $user['username'], $otp)) {
                header("Location: verify_otp.php"); // Redirect to OTP verification page
                exit();
            } else {
                $_SESSION['error'] = "Error sending OTP. Try again.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid Email or Password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Account not found. Please Sign Up.";
        exit();
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
            <p>Your One-Time Password (OTP) for login is: <strong>$otp</strong> (Valid for 10 minutes)</p>
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
    <title>Login - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
    <script>
        function validateForm() {
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let errorMsg = document.getElementById("error_message");

            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                errorMsg.innerHTML = "Invalid email format!";
                return false;
            }
            if (password.length < 8) {
                errorMsg.innerHTML = "Password must be at least 8 characters!";
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="auth-container">
    <h2>Log In</h2>

    <?php if (isset($_SESSION['error'])) { ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php } ?>

    <p id="error_message" style="color: red;"></p>

    <form method="POST" onsubmit="return validateForm();">
        <label>Email</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" required />

        <label for="password">Password</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Enter Password" required />
            <i id="togglePassword" class="fa fa-eye" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>

        <script>
            document.getElementById("togglePassword").addEventListener("click", function () {
                let passwordField = document.getElementById("password");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                }
            });
        </script>

        <button type="submit" name="login">Log In</button>
    </form>
    <p><a href="forgot_password.php">Forgot Password?</a></p>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>

</body>
</html>

<?php include 'includes/footer.php'; ?>
