<?php
error_reporting(0);
session_start(); // Start the session
require 'includes/database.php';
require 'vendor/autoload.php'; // Include Composer's autoloader for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"]; // No need to hash here; compare directly with the stored hash

    // Prepare the SQL query to fetch the hashed password
    $sql = "SELECT * FROM user WHERE username = '$username' AND email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the user record
        $user = mysqli_fetch_assoc($result);

        // Verify the password with the stored hash
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['username'] = $username;

            // Send Login Confirmation Email
            sendLoginConfirmationEmail($email, $username);

            echo "<script> alert('Log-in successfully');</script>"."<script> window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "<script> alert('Invalid Username or Password!'); </script>"
                    . "<script> window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script> alert('Data Not Found Please Sign Up'); </script>"
                . "<script> window.location.href = 'index.php';</script>";
    }
}

// Function to send a login confirmation email
function sendLoginConfirmationEmail($to, $username) {
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
        $mail->Subject = 'Login Confirmation for Book My Stay';
        $mail->Body = "
            <h1>Welcome back, $username!</h1>
            <p>You have successfully logged into your account at <strong>Book My Stay</strong>.</p>
            <p>If you did not initiate this login, please contact us immediately.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log the error or handle it as needed
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>
