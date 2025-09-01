<?php
session_start();
require 'includes/database.php';
require 'vendor/autoload.php';
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["signup"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $fname = mysqli_real_escape_string($conn, $_POST["fname"]);
    $lname = mysqli_real_escape_string($conn, $_POST["lname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phno = mysqli_real_escape_string($conn, $_POST["phno"]);
    $add = mysqli_real_escape_string($conn, $_POST["add"]);
    $dob = mysqli_real_escape_string($conn, $_POST["dob"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email or username already exists
    $checkQuery = "SELECT * FROM user WHERE username = '$username' OR email = '$email' OR phno = '$phno'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Username, Email, or Phone No. already exists. Please try another one.; setTimeout(function() { window.location.href = 'signup.php'; }, 1000));</script>";
        exit();
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.; setTimeout(function() { window.location.href = 'signup.php'; }, 1000));</script>";
        exit();
    }
    if ($password === $cpassword) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['signup_otp'] = $otp;
        $_SESSION['signup_otp_time'] = time();
        $_SESSION['signup_email'] = $email;
        $_SESSION['signup_data'] = compact('username', 'fname', 'lname', 'email', 'phno', 'add', 'dob', 'hashedpassword');

        // Send OTP Email
        sendSignupOTP($email, $otp);

        // Redirect to OTP verification page
        header("Location: verify_signup_otp.php");
        exit();
    } else {
        echo "<script>alert('Passwords do not match!; setTimeout(function() { window.location.href = 'signup.php'; }, 1000));</script>";
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
    <title>Sign Up - Book My Stay</title>
    <link rel="stylesheet" href="assets/css/ls.css">
    <style>
        .auth-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        input, button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="auth-container">
    <h2>Sign Up</h2>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required />
        <label>First Name</label>
        <input type="text" name="fname" required />
        <label>Last Name</label>
        <input type="text" name="lname" required />
        <label>Email</label>
        <input type="email" name="email" required />
        <label>Phone Number</label>
        <input type="text" name="phno" required />
        <label>Address</label>
        <input type="text" name="add" required />
        <label>Date of Birth</label>
        <input type="date" name="dob" required />
        <label>Password</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required />
            <span class="toggle-password" onclick="togglePassword('password', 'eye1')">
                <i id="eye1" class="fa fa-eye"></i>
            </span>
        </div>
        <label>Confirm Password</label>
        <div class="password-container">
            <input type="password" id="cpassword" name="cpassword" required />
            <span class="toggle-password" onclick="togglePassword('cpassword', 'eye2')">
                <i id="eye2" class="fa fa-eye"></i>
            </span>
        </div>
        <button type="submit" name="signup">Sign Up</button>
    </form>
</div>
<script>
    function togglePassword(fieldId, eyeId) {
        let passwordField = document.getElementById(fieldId);
        let eyeIcon = document.getElementById(eyeId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    function validatePassword() {
        let password = document.getElementById("password").value;
        let validationMsg = document.getElementById("password-validation");
        let pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (!pattern.test(password)) {
            validationMsg.style.display = "block";
            return false;
        } else {
            validationMsg.style.display = "none";
            return true;
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .password-container {
        position: relative;
    }
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>
</body>
</html>
<?php
    include 'includes/footer.php';
?>
