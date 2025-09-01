<?php
error_reporting(0);
ini_set('display_errors', 1);
include 'includes/database.php';
include 'includes/header.php';

$orderId = $_GET['order_id'] ?? null;

if ($orderId) {
    $query = "SELECT * FROM resort_booking WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $username = $booking['username'];
    $booking_id = $booking['booking_id'];

    // Fetch cab booking details
    $cabQuery = "SELECT * FROM cab_booking_resort WHERE resort_booking_id = ?";
    $cabStmt = $conn->prepare($cabQuery);
    $cabStmt->bind_param("s", $booking_id);
    $cabStmt->execute();
    $cabResult = $cabStmt->get_result();
    $cabBooking = $cabResult->fetch_assoc();
    
    $currentDate = date('Y-m-d');
    $twoDaysBeforeCheckin = date('Y-m-d', strtotime($booking['checkin'] . ' -2 days'));

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


                /* Modal Styles */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.5);
                }
                .modal-content {
                    background-color: #fefefe;
                    margin: 10% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 50%;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }
                .close-btn {
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }
                .close-btn:hover, .close-btn:focus {
                    color: #000;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="invoice">
                <h1>Resort Invoice</h1>
                <table>
                    <tr><th>Booking ID</th><td><?= htmlspecialchars($booking['booking_id']); ?></td></tr>
                    <tr><th>Order ID</th><td><?= htmlspecialchars($booking['order_id']); ?></td></tr>
                    <tr><th>Username</th><td><?= htmlspecialchars($booking['username']); ?></td></tr>
                    <tr><th>Fullname</th><td><?= htmlspecialchars($booking['fullname']); ?></td></tr>
                    <tr><th>Resort</th><td><?= htmlspecialchars($booking['rname']); ?></td></tr>
                    <tr><th>Room</th><td><?= htmlspecialchars($booking['roomname']); ?></td></tr>
                    <tr><th>Check-in</th><td><?= htmlspecialchars($booking['checkin']); ?></td></tr>
                    <tr><th>Check-out</th><td><?= htmlspecialchars($booking['checkout']); ?></td></tr>
                    <tr><th>Total Price</th><td>₹<?= number_format($booking['rprice'], 2); ?></td></tr>
                    <tr><th>Adults</th><td><?= htmlspecialchars($booking['adult']); ?></td></tr>
                    <tr><th>Children</th><td><?= htmlspecialchars($booking['child']); ?></td></tr>
                    <tr><th>Status</th><td><?= htmlspecialchars($booking['status']); ?></td></tr>
                </table>
                <div class="button-row">
                <?php if ($booking['status'] === 'pending'): ?>
                    <a href="book1.php?booking_id=<?= urlencode($booking['booking_id']); ?>&room_id=<?= htmlspecialchars($booking['room_id']) ?>">
                        <button name="paynow" id="paynow">Pay Now</button>
                    </a>
                <?php else: ?>
                    <form method="POST" action="download_invoice1.php">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderId); ?>">
                        <button type="submit">Download Invoice</button>
                    </form>
                <?php endif; ?>

                <?php if ($cabBooking): ?>
                    <button onclick="showCabDetails()">Cab Booking Details</button>
                <?php else: ?>
                    <?php if ($currentDate <= $twoDaysBeforeCheckin): ?>
                        <a href="cab_resort.php?resort_booking_id=<?= urlencode($booking_id); ?>">
                            <button>Book a Cab</button>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <br><a href="mybooking.php"><button class="back">Back</button></a>
                </div>
            </div>

            <!-- Cab Booking Modal -->
            <div id="cabModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeCabModal()">&times;</span>
                    <h2>Cab Booking Details</h2>
                    <table>
                        <tr><th>Booking ID</th><td><?= htmlspecialchars($cabBooking['id']); ?></td></tr>
                        <tr><th>Resort Booking ID</th><td><?= htmlspecialchars($cabBooking['resort_booking_id']); ?></td></tr>
                        <tr><th>Username</th><td><?= htmlspecialchars($cabBooking['username']); ?></td></tr>
                        <tr><th>Pickup Location</th><td><?= htmlspecialchars($cabBooking['pickup']); ?></td></tr>
                        <tr><th>Drop-off Location</th><td><?= htmlspecialchars($cabBooking['dropoff']); ?></td></tr>
                        <tr><th>Date</th><td><?= htmlspecialchars($cabBooking['date']); ?></td></tr>
                        <tr><th>Time</th><td><?= htmlspecialchars($cabBooking['time']); ?></td></tr>
                        <tr><th>Contact</th><td><?= htmlspecialchars($cabBooking['contact']); ?></td></tr>
                        <tr><th>Status</th><td><?= htmlspecialchars($cabBooking['status']); ?></td></tr>
                    </table>
                </div>
            </div>

            <script>
                function showCabDetails() {
                    document.getElementById('cabModal').style.display = 'block';
                }
                function closeCabModal() {
                    document.getElementById('cabModal').style.display = 'none';
                }
                window.onclick = function(event) {
                    const modal = document.getElementById('cabModal');
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Invalid Order ID.</p>";
    }
} else {
    echo "<p>No Order ID provided.</p>";
}
include 'includes/footer.php';
?>