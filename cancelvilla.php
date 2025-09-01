<?php 
session_start();
require 'includes/database.php';
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoload

// Razorpay API credentials (use environment variables for production)
$apiKey = 'rzp_test_LWmY7qihZ255G1';  // Razorpay Test API Key
$apiSecret = 'LvudH59jJCQBC389xoS5zAOW';  // Razorpay Test API Secret

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
    $query = "SELECT * FROM villa_booking WHERE booking_id = ? AND username = ? AND status = 'paid'";
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

        // Allow cancellation only if check-in date is more than 1 day away
        if ($dateDifference <= 1 || $isPastCheckin) {
            echo "<script>
                alert('Cancellation not allowed within one day of check-in or after the check-in date.');
                setTimeout(function() {
                    window.location.href = 'mybooking.php';
                }, 1000);
            </script>";
            exit();
        }

        $paymentId = $booking['payment_id'];
        $originalPrice = $booking['vprice'];
        $deduction = $originalPrice * 0.10; // 10% deduction
        $refundAmount = ($originalPrice - $deduction) * 100; // Convert to paise

        // Check if payment ID exists
        if (empty($paymentId)) {
            die("Error: Payment ID not found for this booking.");
        }

        // Process refund via Razorpay (with 10% deduction)
        $refundData = ['amount' => $refundAmount];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.razorpay.com/v1/payments/$paymentId/refund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($refundData),
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_USERPWD => $apiKey . ':' . $apiSecret,
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorMessage = curl_error($ch);
        curl_close($ch);

        if ($errorMessage) {
            echo "<p>cURL Error: " . htmlspecialchars($errorMessage) . "</p>";
            exit();
        }

        $responseArray = json_decode($response, true);

        // Handle Razorpay refund response
        if ($httpStatus === 200 && isset($responseArray['id'])) {
            // Update booking status in the database
            $updateQuery = "UPDATE villa_booking SET status = 'canceled' WHERE booking_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("i", $bookingId);

            if ($stmt->execute()) {
                // Send cancellation email
                $email = $booking['useremail'];
                $villaName = $booking['vname'];
                $refundAmountFormatted = number_format($originalPrice - $deduction, 2);

                if (sendCancellationEmail($email, $username, $villaName, $refundAmountFormatted, $booking['checkin'])) {
                    echo "<script>
                        alert('Booking successfully canceled with a 10% deduction. Refund processed, and a confirmation email has been sent.');
                        window.location.href = 'mybooking.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('Booking canceled, but email notification could not be sent.');
                        window.location.href = 'mybooking.php';
                    </script>";
                }
                exit();
            } else {
                echo "<p>Error updating booking status. Please contact support.</p>";
            }
        } else {
            $errorMessage = isset($responseArray['error']['description']) ? $responseArray['error']['description'] : "Unknown error.";
            echo "<p>Error processing refund: " . htmlspecialchars($errorMessage) . "</p>";
        }
    } else {
        echo "<p>Invalid booking ID or you are not authorized to cancel this booking.</p>";
    }
} else {
    echo "<p>No booking ID provided.</p>";
}

$conn->close();

// Function to send cancellation email
function sendCancellationEmail($to, $username, $villaName, $refundAmount, $checkinDate) {
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

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Villa Booking Cancellation Confirmation';
        $mail->Body = "
            <h1>Villa Booking Cancellation Confirmation</h1>
            <p>Dear <strong>$username</strong>,</p>
            <p>Your booking at <strong>$villaName</strong> has been successfully canceled.</p>
            <p>A refund of <strong>₹$refundAmount</strong> has been processed to your account after a 10% deduction.</p>
            <p><strong>Check-in Date:</strong> $checkinDate</p>
            <p>Thank you for choosing <strong>Book My Stay</strong>. We hope to serve you again soon.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
