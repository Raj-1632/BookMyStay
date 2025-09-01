<?php
error_reporting(E_ALL);

require 'includes/database.php';
require 'vendor/autoload.php'; // Include Composer's autoloader for PHPMailer

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

    $checkQuery = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script> alert('Username or Email already exists. Please try another one or log in ');</script>"
            . "<script> window.location.href = 'index.php';</script>";
        exit();
    }

    if ($password === $cpassword) {
        $sql = "INSERT INTO user (username,fname,lname,email,phno,address,dob,password) VALUES ('$username','$fname','$lname','$email','$phno','$add','$dob','$hashedpassword')";

        if (mysqli_query($conn, $sql)) {
            // Send Welcome Email
            sendWelcomeEmail($email, $username);

            echo "<script> alert('New User created successfully');</script>"    
                . "<script> window.location.href = 'index.php';</script>";
        } else {
            echo "<script> alert('Unable to create new user');</script>"
             ."<script> window.location.href = 'index.php';</script>";
        }
    } else {    
        echo "<script> alert('Password does not match');</script>"
        . "<script> window.location.href = 'index.php';</script>";
    }
}

// Function to send a welcome email
function sendWelcomeEmail($to, $username) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com'; // Replace with your email
        $mail->Password = 'sftm blky plvr lyvc'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay'); // Replace with your sender email and name
        $mail->addAddress($to); // User's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Book My Stay!';
        $mail->Body = "
            <h1>Welcome, $username!</h1>
            <p>Thank you for signing up at <strong>Book My Stay</strong>. We’re excited to have you on board.</p>
            <p>Explore amazing hotels, resorts, and villas now!</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log the error or handle it as needed
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>