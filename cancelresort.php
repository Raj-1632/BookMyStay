<?php
session_start();
require 'includes/database.php';
include 'includes/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoload

// Razorpay API credentials for test mode
$apiKey = 'rzp_test_LWmY7qihZ255G1';  
$apiSecret = 'LvudH59jJCQBC389xoS5zAOW';  

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if booking ID is provided
if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];
    $username = $_SESSION['username'];

    // Fetch booking details from the database
    $query = "SELECT * FROM resort_booking WHERE booking_id = ? AND username = ? AND status = 'paid'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $bookingId, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $checkinDate = new DateTime($booking['checkin']);
        $currentDate = new DateTime();

        // Calculate the date difference
        $dateDifference = $currentDate->diff($checkinDate)->days;
        $isPastCheckin = $currentDate > $checkinDate;

        // Prevent cancellation within 1 day of check-in
        if ($dateDifference <= 1 || $isPastCheckin) {
            echo "<script>
                alert('Cancellation not allowed within one day of check-in or after check-in date.');
                setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000);
            </script>";
            exit();
        }

        $paymentId = $booking['payment_id']; // Razorpay Payment ID

        // Calculate refund (10% deduction)
        $totalAmount = $booking['rprice'];
        $refundAmount = $totalAmount - ($totalAmount * 0.10);  
        $refundAmountPaise = $refundAmount * 100; // Convert to paise
        echo "<script> alert('Refund Amount: ₹" . number_format($refundAmount, 2) . "'); </script>";
        echo "<script> alert('Refund Amount in Paise: " . $refundAmountPaise . "'); </script>";
        if (empty($paymentId)) {
            die("Error: Payment ID not found for this booking.");
        }

        // Process refund via Razorpay
        $refundData = json_encode(['amount' => $refundAmountPaise]);
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.razorpay.com/v1/payments/$paymentId/refund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $refundData,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_USERPWD => "$apiKey:$apiSecret",
        ]);

        // Execute cURL request
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorMessage = curl_error($ch);
        curl_close($ch);

        if ($errorMessage) {
            echo "<p>cURL Error: " . htmlspecialchars($errorMessage) . "</p>";
        }

        // Decode API response
        $responseArray = json_decode($response, true);

        if ($httpStatus === 200 && isset($responseArray['id'])) {
            // Refund successful, update booking status
            $updateQuery = "UPDATE resort_booking SET status = 'canceled' WHERE booking_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("i", $bookingId);

            if ($stmt->execute()) {
                $checkCabQuery = "SELECT * FROM cab_booking_resort WHERE resort_booking_id = ?";
                $cabCheckStmt = $conn->prepare($checkCabQuery);
                $cabCheckStmt->bind_param("i", $bookingId);
                $cabCheckStmt->execute();
                $cabResult = $cabCheckStmt->get_result();

                if ($cabResult->num_rows > 0) {
                    // Cancel cab booking if found
                    $cabQuery = "UPDATE cab_booking_resort SET status = 'canceled' WHERE resort_booking_id = ?";
                    $cabStmt = $conn->prepare($cabQuery);
                    $cabStmt->bind_param("i", $bookingId);

                    if ($cabStmt->execute()) {
                        error_log("Cab booking canceled for resort_booking_id: $bookingId");
                    } else {
                        error_log("Failed to cancel cab booking for resort_booking_id: $bookingId");
                    }
                } else {
                    error_log("No cab booking found for resort_booking_id: $bookingId");
                }

                // Send cancellation email
                $email = $booking['useremail']; 

                if (sendCancellationEmail($email, $username, $booking['rname'], $refundAmount, $booking['checkin'])) {
                    echo "<script>
                        alert('Booking canceled & refund processed (10% deducted). A confirmation email has been sent.');
                        setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000);
                    </script>";
                    exit();
                } else {
                    echo "<script>
                        alert('Booking canceled but email could not be sent.');
                        setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000);
                    </script>";
                    exit();
                }
            } else {
                echo "<p>Error updating booking status. Please contact support.</p>";
            }
        } else {
            $errorMessage = isset($responseArray['error']['description'])
                ? $responseArray['error']['description']
                : "Unknown error occurred.";
            echo "<p>Error processing refund: " . htmlspecialchars($errorMessage) . "</p>";
        }
    } else {
        echo "<p>Invalid booking ID or unauthorized cancellation attempt.</p>";
    }
} else {
    echo "<p>No booking ID provided.</p>";
}

// Close database connection
$conn->close();

// Function to send cancellation email
function sendCancellationEmail($to, $username, $hotelName, $refundAmount, $checkinDate) {
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
        $mail->Subject = 'Booking Cancellation & Refund - Book My Stay';
        $mail->Body = "
            <h1>Booking Cancellation Confirmation</h1>
            <p>Dear <strong>$username</strong>,</p>
            <p>Your booking at <strong>$hotelName</strong> has been canceled.</p>
            <p>Refund Processed: <strong>₹$refundAmount</strong> (10% cancellation fee deducted).</p>
            <p><strong>Check-in Date:</strong> $checkinDate</p>
            <p><strong>Note:</strong> If you booked a cab, it has also been automatically canceled.</p>
            <p>Thank you for choosing <strong>Book My Stay</strong>. We hope to serve you again.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
