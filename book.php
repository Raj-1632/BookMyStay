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
    $deleteQuery = "DELETE FROM hotel_booking WHERE booking_id = ?";
    $stmt1 = $conn->prepare($deleteQuery);
    $stmt1->bind_param("i", $bid);
    $stmt1->execute();
    $stmt1->close();

    $roomId = $_GET['room_id'];
    $username = $_SESSION['username'];

    // Fetch room details
    $query = "SELECT * FROM rooms1 WHERE room_id = ?";
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

            $totalPrice = $nights * $price;
            $amount = $totalPrice * 100; // Convert to paise for Razorpay

            $cabBooking = isset($_POST['book_cab']) && $_POST['book_cab'] === 'yes';

            // Cab booking details (if applicable)
            $cabPickup = $cabDrop = $cabDate = $cabTime = $cabType = null;
            if ($cabBooking) {
                $cabPickup = $_POST['cab_pickup_location'];
                $cabDrop = $_POST['cab_drop_location'];
                $cabDate = $_POST['cab_date'];
                $cabTime = $_POST['cab_time'];
                $cabType = $_POST['cab_type'];
            }
            $availabilityQuery = "SELECT COUNT(*) AS booking_count 
                        FROM hotel_booking 
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
                        echo "<script> alert('This room is fully booked for the selected dates. Please choose different dates or another room.'); setTimeout(function() { window.location.href = 'book.php?room_id=$roomId'; }, 1000);</script>";
                        exit();
            }

            // Razorpay API call to create order
            $apiKey = 'rzp_test_LWmY7qihZ255G1';
            $apiSecret = 'LvudH59jJCQBC389xoS5zAOW';
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
                $insertQuery = "INSERT INTO hotel_booking (username, fullname, room_id, rname, hname, hprice, checkin, checkout, adult, child, userphno, useremail, order_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);

                // Corrected bind_param: 18 placeholders, 18 variables
                $stmt->bind_param(
                    "ssisssssiisss", 
                    $username, 
                    $name,
                    $roomId, 
                    $room['room_type'], 
                    $room['hotel_name'], 
                    $totalPrice, 
                    $checkInDate, 
                    $checkOutDate, 
                    $adults, 
                    $children, 
                    $phone, 
                    $email, 
                    $orderId
                );

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
                    "image": "",
                    "order_id": "<?= $orderId ?>",
                    "handler": function (response) {
                        var form = document.createElement("form");
                        form.method = "POST";
                        form.action = "verify_payment.php";

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
                        "color": "#F37254"
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
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <style>
                    body { font-family: Arial, sans-serif;}
                    .payform{margin-top: 110px;}
                    form {max-width: 600px; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; }
                    input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
                    input[readonly], select[readonly] { background-color: #e9ecef; }
                </style>
                 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                 <script>
    let map; // Global map variable
    let marker; // Marker variable

    // Define India's approximate geographical bounds
    const indiaBounds = {
        north: 37.6,
        south: 6.8,
        east: 97.25,
        west: 68.7
    };

    // Fetch user's current location
    async function fetchCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    console.log("Coordinates:", latitude, longitude);

                    // Check if the location is within India's bounds
                    if (
                        latitude >= indiaBounds.south &&
                        latitude <= indiaBounds.north &&
                        longitude >= indiaBounds.west &&
                        longitude <= indiaBounds.east
                    ) {
                        console.log("Location is within India.");
                        initializeMap(latitude, longitude);
                    } else {
                        alert("Your location is outside India. This service is only available for users in India.");
                    }
                },
                function (error) {
                    console.error("Geolocation error:", error);
                    alert("Unable to fetch location. Please enable location services.");
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Initialize or update the map
    function initializeMap(latitude, longitude) {
        const userLocation = { lat: latitude, lng: longitude };

        if (!map) {
            // Initialize the map
            map = new google.maps.Map(document.getElementById("map"), {
                center: userLocation,
                zoom: 15,
            });

            // Add a marker for the user's location
            marker = new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "You are here!",
            });
        } else {
            // Update map center and marker position
            map.setCenter(userLocation);
            marker.setPosition(userLocation);
        }
    }
</script>

            </head>
            <body>
                <form method="POST" class="payform">
                <h2>Booking Details</h2>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $_SESSION['username']; ?>" readonly>

                    <label for="username">Full Name:</label>
                    <input type="text" id="username" name="name" value="<?= $name ?>" >

                    <label for="hotel_name">Hotel Name:</label>
                    <input type="text" id="hotel_name" value="<?= htmlspecialchars($room['hotel_name']); ?>" readonly>

                    <label for="room_type">Room Type:</label>
                    <input type="text" id="room_type" value="<?= htmlspecialchars($room['room_type']); ?>" readonly>

                    <label for="price_per_night">Price Per Night:</label>
                    <input type="text" id="price_per_night" value="₹<?= htmlspecialchars($room['price_per_night']); ?>" readonly>

                   
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?= htmlspecialchars($user['phno']); ?>" >

                    <label for="email">Email Address:</label>
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


                    <button type="submit">Proceed to Payment</button>
                </form>
            </body>
            </html>
            <?php
        }
    } else {
        echo "<p>Error: Room not found.</p>";
    }
} else {
    echo "<p>Error: Invalid request.</p>";
}
 include 'includes/footer.php';
?>

