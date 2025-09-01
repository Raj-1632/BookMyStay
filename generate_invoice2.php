<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/database.php';
include 'includes/header.php';
$orderId = $_GET['order_id'] ?? null; // Get order_id from the URL
                     

                    
if ($orderId) {
    // Fetch booking details from the database
    $query = "SELECT * FROM villa_booking WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice</title>
            <style>
                body { font-family: Arial, sans-serif; margin-top: 100px; }
                .invoice { max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; }
                h1 { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
                button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
                button:hover { background-color: #45a049; }
                .back { text-decoration: none; }
                .button-row {
                    display: flex;
                    gap: 10px;
                    justify-content: center;
                    flex-wrap: wrap;
                }
                .button-row button, .button-row a {
                    display: inline-block;
                }
            </style>
        </head>
        <body>
            <div class="invoice">
                <table>
                <h1>Villa Invoice</h1>
                <table>
                    <tr><th>Booking ID</th><td><?= htmlspecialchars($booking['booking_id']); ?></td></tr>
                    <tr><th>Order ID</th><td><?= htmlspecialchars($booking['order_id']); ?></td></tr>
                    <tr><th>Username</th><td><?= htmlspecialchars($booking['username']); ?></td></tr>
                    <tr><th>Full Name</th><td><?= htmlspecialchars($booking['fullname']); ?></td></tr>
                    <tr><th>Villa</th><td><?= htmlspecialchars($booking['vname']); ?></td></tr>
                    <tr><th>Check-in</th><td><?= htmlspecialchars($booking['checkin']); ?></td></tr>
                    <tr><th>Check-out</th><td><?= htmlspecialchars($booking['checkout']); ?></td></tr>
                    <tr><th>Total Price</th><td>₹<?= number_format($booking['vprice'], 2); ?></td></tr>
                    <tr><th>Adults</th><td><?= htmlspecialchars($booking['adult']); ?></td></tr>
                    <tr><th>Children</th><td><?= htmlspecialchars($booking['child']); ?></td></tr>
                    <tr><th>Status</th><td><?= htmlspecialchars($booking['status']); ?></td></tr>
                </table>
                <div class="button-row">
                <?php if ($booking['status'] === 'pending'): ?>

                    <a href="book2.php?booking_id=<?= urlencode($booking['booking_id']); ?>&villa_id=<?= htmlspecialchars($booking['vid']) ?>"><button name="paynow" id="paynow" class="back">Pay Now</button></a>
                    
                <?php else: ?>
                <form method="POST" action="download_invoice2.php">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderId); ?>">
                    <button type="submit">Download Invoice</button>
                </form>
                <a href="mybooking.php"><button class="back">Back</button></a>
                <?php endif; ?>
                </div>
                </table>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Invalid Order ID.</p>"; // If no booking found
    }
} else {
    echo "<p>No Order ID provided.</p>"; // If no order ID is passed
}
include 'includes/footer.php';
?>
