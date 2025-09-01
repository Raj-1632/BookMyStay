<?php
session_start();
include 'includes/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $new_checkin = $_POST['new_checkin'];
    $new_checkout = $_POST['new_checkout'];
    
    if (empty($booking_id) || empty($new_checkin) || empty($new_checkout)) {
        echo "<script> alert('Missing Data!'); setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000); </script>";
        exit;
    }

    // Fetch user email
    $query = "SELECT username, useremail FROM resort_booking WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_email = $user['useremail'];
    $username = $user['username'];
    if (!$user) {
        echo "<script>alert('Booking not found!'); window.history.back();</script>";
        exit;
    }

    // Validate the new dates
    if (strtotime($new_checkin) >= strtotime($new_checkout)) {
        echo "<script> alert('Invalid date range!'); setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000); </script>";
        exit;
    }

    // Update the database
    $query = "UPDATE resort_booking SET checkin = ?, checkout = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $new_checkin, $new_checkout, $booking_id);
    if ($stmt->execute()) {
        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bookmystayonline@gmail.com';
            $mail->Password = 'sftm blky plvr lyvc'; // Replace with App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
            $mail->addAddress($user_email, $username);

            $mail->Subject = "Resort Booking Rescheduled Successfully";
            $mail->Body = "Dear $username,\n\nYour resort booking has been successfully rescheduled.\nNew Check-in: $new_checkin\nNew Check-out: $new_checkout\n\nThank you for using Book My Stay.";

            if ($mail->send()) {
                echo "<script>alert('Booking successfully rescheduled! Email sent.'); window.location.href = 'mybooking.php';</script>";
            } else {
                echo "Email failed: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('Error updating booking: " . $stmt->error . "');</script>";
    }
}

include 'includes/footer.php';
?>
