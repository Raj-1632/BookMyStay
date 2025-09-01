<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'includes/fpdf/fpdf.php';
require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$keySecret = 'LvudH59jJCQBC389xoS5zAOW';

$paymentId = $_POST['razorpay_payment_id'] ?? null;
$orderId = $_POST['razorpay_order_id'] ?? null;
$signature = $_POST['razorpay_signature'] ?? null;

if ($paymentId && $orderId && $signature) {
    $generatedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

    if (hash_equals($generatedSignature, $signature)) {
        include 'includes/database.php';
        $updateQuery = "UPDATE resort_booking SET status='paid', payment_id=? WHERE order_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $paymentId, $orderId);
        
        if ($stmt->execute()) {
            
            $query = "SELECT booking_id, username, useremail, rname, userphno, checkin, checkout, rprice FROM resort_booking WHERE order_id=?";
            $stmt2 = $conn->prepare($query);

            if (!$stmt2) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            if (empty($orderId)) {
                die("Order ID is missing.");
            }

            $stmt2->bind_param("s", $orderId);

            if (!$stmt2->execute()) {
                die("Execute failed: (" . $stmt2->errno . ") " . $stmt2->error);
            }
            $result = $stmt2->get_result();
            if ($result->num_rows === 0) {
                die("No booking found for Order ID: $orderId");
            }
            $booking = $result->fetch_assoc();

            if ($booking) {
                $username = $booking['username'];
                $email = $booking['useremail'];
                $hotelName = $booking['rname'];
                $userPhone = $booking['userphno'];
                $checkIn = $booking['checkin'];
                $checkOut = $booking['checkout'];
                $price = $booking['rprice'];

                
                $invoicePath = generateInvoicePDF($orderId, $conn);

                
                if (sendPaymentConfirmationEmail($email, $username, $hotelName, $paymentId, $orderId, $invoicePath)) {
                    echo "<script>
                        alert('Payment verification successful. Confirmation email sent.');
                        setTimeout(function() { 
                            if (confirm('Do you want to book a cab (Free Service)?')) {
                                window.location.href = 'cab_resort.php?resort_booking_id= ".urlencode($booking['booking_id'])."'; 
                            } else {
                                window.location.href = 'mybooking.php';
                            }
                        }, 1000);
                    </script>";
                } else {
                    echo "<script>alert('Payment successful, but email could not be sent.');</script>";
                }
            }
        } else {
            echo "Database update failed: " . $stmt->error;
        }
    } else {
        echo "<script>
            alert('Payment verification failed: Invalid signature.');
            setTimeout(function() { window.location.href = 'resorts.php'; }, 2000);
        </script>";
    }
} else {
    echo "<script>
        alert('Payment verification failed: Missing payment details.');
        setTimeout(function() { window.location.href = 'resorts.php'; }, 2000);
    </script>";
}

function generateInvoicePDF($orderId, $conn) {
    // Query the database for booking information
    $query = "SELECT * FROM resort_booking WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        // Initialize PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Header Styling
        $pdf->SetFillColor(64, 64, 64);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 16);

        // Header
        $pdf->Cell(0, 12, 'Book My Stay', 0, 1, 'C', true);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Resort Invoice', 0, 1, 'C', true);
        $pdf->Ln(10);

        // Reset text color for content
        $pdf->SetTextColor(0, 0, 0);

        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230); // Light Gray
        $pdf->Cell(50, 10, 'Field', 1, 0, 'C', true);
        $pdf->Cell(140, 10, 'Details', 1, 1, 'C', true);

        // Table Content
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetFillColor(245, 245, 245); // Very Light Gray

        // Booking Details
        $rows = [
            'Username' => $booking['username'],
            'Full Name' => $booking['fullname'],
            'Booking ID' => $booking['booking_id'],
            'Order ID' => $booking['order_id'],
            'Payment ID' => $booking['payment_id'],
            'Resort' => $booking['rname'],
            'Room' => $booking['rname'],
            'Check-in' => $booking['checkin'],
            'Check-out' => $booking['checkout'],
            'Adults' => $booking['adult'],
            'Children' => $booking['child'],
            'Phone No.' => $booking['userphno'],
            'Email' => $booking['useremail'],
            'Total Price' => 'Rs. ' . number_format($booking['rprice'], 2),
            'Status' => ucfirst($booking['status']),
        ];

        $fill = false; // Alternate row colors
        foreach ($rows as $field => $detail) {
            $pdf->Cell(50, 10, $field, 1, 0, 'L', $fill);
            $pdf->Cell(140, 10, $detail, 1, 1, 'L', $fill);
            $fill = !$fill; // Toggle fill color
        }

        // Footer
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->SetFillColor(64, 64, 64);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, 'Thanks for booking!', 0, 1, 'C', true);

        // Save PDF to file
        if (!is_dir('invoices')) {
            mkdir('invoices', 0755, true); // Ensure invoices folder exists
        }
        $invoicePath = "invoices/Invoice_$orderId.pdf";
        $pdf->Output('F', $invoicePath);

        return $invoicePath;
    } else {
        throw new Exception("No booking found for Order ID: $orderId");
    }
}

function sendPaymentConfirmationEmail($to, $username, $hotelName, $paymentId, $orderId, $invoicePath) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bookmystayonline@gmail.com'; // Set in environment
        $mail->Password = 'sftm blky plvr lyvc'; // Set in environment
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation for Book My Stay';
        $mail->Body = "
            <h1>Thank you for your payment, $username!</h1>
            <p>Your payment for <strong>$hotelName</strong> has been successfully processed.</p>
            <p><strong>Payment ID:</strong> $paymentId</p>
            <p><strong>Order ID:</strong> $orderId</p>
            <p>We look forward to hosting you. Have a great stay!</p>
        ";

    
        if (file_exists($invoicePath)) {
            $mail->addAttachment($invoicePath, 'Invoice.pdf');
        } else {
            error_log("Invoice file not found at $invoicePath");
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>


    