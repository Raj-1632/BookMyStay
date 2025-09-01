<?php
session_start();
require 'includes/database.php';
require 'vendor/autoload.php';
include 'includes/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['booking_id'], $_POST['booking_type'], $_POST['rating'], $_POST['review'])) {
        die("Error: Missing required fields.");
    }

    $booking_id = htmlspecialchars($_POST['booking_id']);
    $booking_type = htmlspecialchars($_POST['booking_type']);
    $rating = intval($_POST['rating']);  
    $review = htmlspecialchars($_POST['review']);
    $username = $_SESSION['username'];

    $booking_table = '';
    $property_table = '';
    $review_table = '';
    $name = '';

    if ($booking_type === 'Hotel') {
        $booking_table = 'hotel_booking';
        $property_table = 'hotels1';
        $review_table = 'hotel_reviews';
        $name = 'hname';
    } elseif ($booking_type === 'Resort') {
        $booking_table = 'resort_booking';
        $property_table = 'resorts1';
        $review_table = 'resort_reviews';
        $name = 'rname';
    } elseif ($booking_type === 'Villa') {
        $booking_table = 'villa_booking';
        $property_table = 'villas1';
        $review_table = 'villa_reviews';
        $name = 'vname';
    } else {
        die("Error: Invalid booking type.");
    }

    // Fetch user ID and email
    $stmt = $conn->prepare("SELECT user_id, email FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $user_email);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        die("Error: User not found.");
    }

    // Fetch property name using booking_id
    $stmt = $conn->prepare("SELECT $name FROM $booking_table WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($property_name);
    $stmt->fetch();
    $stmt->close();

    if (!$property_name) {
        die("Error: Property not found for this booking.");
    }

    // Fetch property_id using property name
    $stmt1 = $conn->prepare("SELECT id FROM $property_table WHERE name = ?");
    $stmt1->bind_param("s", $property_name);
    $stmt1->execute();
    $stmt1->bind_result($property_id);
    $stmt1->fetch();
    $stmt1->close();

    if (!$property_id) {
        die("Error: Property ID not found.");
    }

    // Insert the review into the correct review table
    $sql = "INSERT INTO $review_table (user_id, property_id, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_id, $property_id, $rating, $review);

    if ($stmt->execute()) {
        // Send confirmation email
        sendReviewConfirmation($user_email, $username, $property_name, $rating, $review);
        echo "<script>alert('Review submitted successfully. A confirmation email has been sent.'); window.location.href='mybooking.php';</script>";
    } else {
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

function sendReviewConfirmation($to, $username, $property_name, $rating, $review) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com'; 
        $mail->Password = 'sftm blky plvr lyvc';  // Use App Password for Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Review Confirmation - Book My Stay';
        $mail->Body = "<p>Dear $username,</p>
                <p>Thank you for submitting your review for <strong>$property_name</strong> on Book My Stay.</p>
                <p>Your Rating: <strong>$rating/5</strong></p>
                <p>Your Review: \"$review\"</p>
                <p>We appreciate your feedback and look forward to serving you again!</p>
                <br>
                <p>Best Regards,</p>
                <p>Book My Stay Team</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Review confirmation email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>
