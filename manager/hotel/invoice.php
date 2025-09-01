<?php
require 'D:/xampp/htdocs/bookmystay/includes/fpdf/fpdf.php';
include 'D:/xampp/htdocs/bookmystay/includes/database.php';

// Retrieve order ID from POST request
$orderId = $_GET['order_id'];

// Query the database for the booking information
$query = "SELECT * FROM hotel_booking WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if ($booking) {
    // Create PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set header colors
    $pdf->SetFillColor(64, 64, 64);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 16);

    // Header
    $pdf->Cell(0, 12, 'Book My Stay', 0, 1, 'C', true);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Hotel Invoice', 0, 1, 'C', true);
    $pdf->Ln(10); // Add space after header

    // Reset text color for body
    $pdf->SetTextColor(0, 0, 0);

    // Set table header style
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(230, 230, 230); // Light Gray
    $pdf->Cell(50, 10, 'Field', 1, 0, 'C', true);
    $pdf->Cell(140, 10, 'Details', 1, 1, 'C', true);

    // Table body
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(245, 245, 245); // Very Light Gray

    // Add booking details as rows
    $rows = [
        'Username' => $booking['username'],
        'Full Name' => $booking['fullname'],
        'Booking ID' => $booking['booking_id'],
        'Order ID' => $booking['order_id'],
        'Payment ID' => $booking['payment_id'],
        'Hotel' => $booking['hname'],
        'Room' => $booking['rname'],
        'Check-in' => $booking['checkin'],
        'Check-out' => $booking['checkout'],
        'Adults' => $booking['adult'],
        'Children' => $booking['child'],
        'Phone No.' => $booking['userphno'],
        'Email' => $booking['useremail'],
        'Total Price' => 'Rs. '. number_format($booking['hprice'], 2),
        'Status' => ucfirst($booking['status']),
    ];

    $fill = false; // Alternate row colors
    foreach ($rows as $field => $detail) {
        $pdf->Cell(50, 10, $field, 1, 0, 'L', $fill);
        $pdf->Cell(140, 10, $detail, 1, 1, 'L', $fill);
        $fill = !$fill; // Toggle fill color
    }

    // Footer
    $pdf->Ln(10); // Add space before footer
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetFillColor(64, 64, 64); // Footer background color
    $pdf->SetTextColor(255, 255, 255); // Footer text color
    $pdf->Cell(0, 10, 'Thanks for booking!', 0, 1, 'C', true);

    // Output the PDF for download
    $pdf->Output('D', 'Invoice_' . $orderId . '.pdf');
} else {
    echo "Invalid Order ID.";
}
?>
