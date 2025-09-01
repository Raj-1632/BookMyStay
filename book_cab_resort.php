<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include 'includes/database.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $username = $_POST['username'];
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $contact = $_POST['contact'];

    $userQuery = "SELECT useremail FROM resort_booking WHERE username = ?";
    $stmtUser = $conn->prepare($userQuery);
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $result = $stmtUser->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }
    $email = $user['useremail'];

    $insertQuery = "INSERT INTO cab_booking_resort (resort_booking_id,username,pickup, dropoff, date, time, contact) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("issssss", $booking_id, $username, $pickup, $dropoff, $date, $time, $contact);

    if ($stmt->execute()) {
        // Send email notification
        if (sendCabBookingEmail($email, $username, $pickup, $dropoff, $date, $time)) {
            echo "<script>
                alert('Cab booking successful! Confirmation email sent.');
                window.location.href = 'mybooking.php';
            </script>";
        } else {
            echo "<script>
                alert('Cab booking successful! However, the email could not be sent.');
                window.location.href = 'mybooking.php';
            </script>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
function sendCabBookingEmail($to, $username, $pickup, $dropoff, $date, $time) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com'; // Replace with your email
        $mail->Password = 'sftm blky plvr lyvc'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Cab Booking Confirmation - Book My Stay';
        $mail->Body = "
            <h1>Cab Booking Confirmed</h1>
            <p>Dear <strong>$username</strong>,</p>
            <p>Your cab has been successfully booked with the following details:</p>
            <ul>
                <li><strong>Pickup Location:</strong> $pickup</li>
                <li><strong>Drop-off Location:</strong> $dropoff</li>
                <li><strong>Date:</strong> $date</li>
                <li><strong>Time:</strong> $time</li>
            </ul>
            <p>Thank you for choosing <strong>Book My Stay</strong>. Have a safe journey!</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
        return false;
    }
}

?>
