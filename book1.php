<?php
session_start();
error_reporting(0);

ini_set('display_errors', 1);
require 'includes/database.php';
include 'includes/header.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ((isset($_GET['booking_id'])) || isset($_GET['room_id'])) {

    $bid = $_GET['booking_id'];
    $deleteQuery = "DELETE FROM resort_booking WHERE booking_id = ?";
    $stmt1 = $conn->prepare($deleteQuery);
    $stmt1->bind_param("i", $bid);
    $stmt1->execute();
    $stmt1->close();


    $roomId = $_GET['room_id'];
    $username = $_SESSION['username'];

    // Fetch room details
    $query = "SELECT * FROM rooms2 WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $guestCapacity = $room['capacity'];

    $query1 = "SELECT * FROM user WHERE username = '$username'";
    $stmt1 = $conn->prepare($query1);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $user = $result1->fetch_assoc();
    $name = $user['fname'] . ' ' . $user['lname'];

    if ($room) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data
            $checkInDate = $_POST['check_in_date'];
            $checkOutDate = $_POST['check_out_date'];
            $adults = $_POST['adults'];
            $children = $_POST['children'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $price = $room['price_per_night'];

            $currentDate = date('Y-m-d');
           
            if ($checkInDate < $currentDate || $checkOutDate < $currentDate) {
                echo "<p>Error: Dates cannot be in the past.</p>";
                exit();
            }
            $totalGuests = $adults + $children;
            if ($totalGuests > $guestCapacity) {
                echo "<script> 
                    alert('Error: Total guests (adults + children) exceed room capacity of $guestCapacity. Please adjust your booking.');
                    window.history.back();
                </script>";
                exit(); // Stop further execution
            }
            // Validate that check-out date is after check-in date
            if ($checkOutDate <= $checkInDate) {
                echo "<p>Error: Check-out date must be after check-in date.</p>";
                exit();
            }

            $startDate = new DateTime($checkInDate);
            $endDate = new DateTime($checkOutDate);
            $nights = $endDate->diff($startDate)->days;
            $totalPrice = $nights * $room['price_per_night'];

            $amount = $totalPrice * 100;

            $availabilityQuery = "SELECT COUNT(*) AS booking_count 
                        FROM resort_booking 
                        WHERE room_id = ? 
                        AND (
                            (checkin <= ? AND checkout >= ?) OR 
                            (checkin <= ? AND checkout >= ?) OR 
                            (checkin >= ? AND checkout <= ?)
                        )";
                        $stmt = $conn->prepare($availabilityQuery);
                        $stmt->bind_param("issssss", $roomId, $checkInDate, $checkInDate, $checkOutDate, $checkOutDate, $checkInDate, $checkOutDate);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $availability = $result->fetch_assoc();

                        if ($availability['booking_count'] >= 5) {
                        echo "<script> alert('This room is fully booked for the selected dates. Please choose different dates or another room.'); setTimeout(function() { window.location.href = 'book1.php?room_id=$roomId'; }, 1000);</script>";
                        exit();
            }

            // Razorpay API call to create order
            $apiKey = 'Your Razorpay API Key';
            $apiSecret = 'API Key Secret';
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.razorpay.com/v1/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'amount' => $amount,
                    'currency' => 'INR',
                    'payment_capture' => 1
                ]),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
                CURLOPT_USERPWD => $apiKey . ':' . $apiSecret
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $responseArray = json_decode($response, true);

            if (isset($responseArray['id'])) {
                $orderId = $responseArray['id']; // Razorpay Order ID

                // Insert booking into the database with Razorpay Order ID
                $insertQuery = "INSERT INTO resort_booking (username, fullname, room_id, roomname, rname, rprice, checkin, checkout, adult, child, userphno, useremail, order_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ssisssssiiiss", $username, $name, $roomId, $room['room_type'], $room['resort_name'], $totalPrice, $checkInDate, $checkOutDate, $adults, $children, $phone, $email, $orderId);
                $stmt->execute();
                $_SESSION['amount'] = $amount;
            } else {
                die("Error creating Razorpay order.");
            }
            ?>

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                var options = {
                    "key": "<?= $apiKey ?>",
                    "amount": "<?= $_SESSION['amount'] ?>",
                    "currency": "INR",
                    "name": "Book My Stay",
                    "description": "Room Booking Payment",
                    "image": "https://your-site.com/logo.png",
                    "order_id": "<?= $orderId ?>",
                    "handler": function (response) {
                        // Submit payment data via POST
                        var form = document.createElement("form");
                        form.method = "POST";
                        form.action = "verify_payment1.php";

                        var paymentIdInput = document.createElement("input");
                        paymentIdInput.type = "hidden";
                        paymentIdInput.name = "razorpay_payment_id";
                        paymentIdInput.value = response.razorpay_payment_id;
                        form.appendChild(paymentIdInput);

                        var orderIdInput = document.createElement("input");
                        orderIdInput.type = "hidden";
                        orderIdInput.name = "razorpay_order_id";
                        orderIdInput.value = response.razorpay_order_id;
                        form.appendChild(orderIdInput);

                        var signatureInput = document.createElement("input");
                        signatureInput.type = "hidden";
                        signatureInput.name = "razorpay_signature";
                        signatureInput.value = response.razorpay_signature;
                        form.appendChild(signatureInput);

                        document.body.appendChild(form);
                        form.submit();
                    },
                    "prefill": {
                        "name": "<?= $_SESSION['username']; ?>",
                        "email": "<?= $email; ?>",
                        "contact": "<?= $phone; ?>"
                    },
                    "theme": {
                        "color": "#FF6B6B"
                    },
                    "modal": {
                        "ondismiss": function() {
                            window.location.href = "mybooking.php"; // Redirect on payment cancel
                        }
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            </script>
            <?php
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                
                <title>Book Room</title>
                <link rel="stylesheet" href="payform.css">
                <style>
                    body { font-family: Arial, sans-serif; margin-top: 100px; }
                    form { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; }
                    input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
                    input[readonly], select[readonly] { background-color: #e9ecef; }
                </style>
            </head>
            <body>
                
                <form method="POST"  class="payform">
                <h2>Booking Details</h2>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $_SESSION['username']; ?>" readonly>

                    <label for="username">Full Name:</label>
                    <input type="text" id="username" name="username" value="<?= $name ?>" >

                    <label for="hotel_name">Resort Name:</label>
                    <input type="text" id="hotel_name" value="<?= htmlspecialchars($room['resort_name']); ?>" readonly>

                    <label for="hotel_name">Room Name:</label>
                    <input type="text" id="hotel_name" value="<?= htmlspecialchars($room['room_type']); ?>" readonly>

                    <label for="price">Price per Night (₹):</label>
                    <input type="text" id="price" value="<?= number_format($room['price_per_night'], 2); ?>" readonly>

                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?= htmlspecialchars($user['phno']); ?>" >

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" >

                    <label for="check_in_date">Check-in Date:</label>
                    <input type="date" id="check_in_date" name="check_in_date" min="<?= date('Y-m-d'); ?>" required>

                    <label for="check_out_date">Check-out Date:</label>
                    <input type="date" id="check_out_date" name="check_out_date" min="<?= date('Y-m-d'); ?>" required>



                    <script>
                        document.getElementById('check_in_date').addEventListener('change', function () {
                            // Ensure check-out date is not earlier than check-in date
                            const checkInDate = this.value;
                            document.getElementById('check_out_date').setAttribute('min', checkInDate);
                        });
                    </script>

                    

                    <label for="adults">Adults:</label>
                    <input type="number" id="adults" name="adults" min="1" required>

                    <label for="children">Children:</label>
                    <input type="number" id="children" name="children" min="0" required>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const adultsInput = document.getElementById("adults");
                            const childrenInput = document.getElementById("children");
                            const guestCapacity = <?= $room['capacity']; ?>; // Fetch from PHP

                            function validateGuests() {
                                const adults = parseInt(adultsInput.value) || 0;
                                const children = parseInt(childrenInput.value) || 0;
                                const totalGuests = adults + children;

                                if (totalGuests > guestCapacity) {
                                    alert(`Total guests cannot exceed ${guestCapacity}.`);
                                    return false;
                                }
                                return true;
                            }

                            adultsInput.addEventListener("input", validateGuests);
                            childrenInput.addEventListener("input", validateGuests);
                        });
                    </script>

                    <button type="submit">Pay Now</button>
                </form>
            </body>
            </html>
            <?php
        }
    } else {
        echo "<p>Invalid Room ID.</p>";
    }
} else {
    echo "<p>No Room ID provided.</p>";
}

include 'includes/footer.php';
$conn->close();
?>

