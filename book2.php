<?php
session_start();
error_reporting(0);
ini_set('display_errors', 1);

require 'includes/database.php';
include 'includes/header.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if villa_id is provided
if (isset($_GET['booking_id']) || isset($_GET['villa_id'])) {

    $bid = $_GET['booking_id'];
    $deleteQuery = "DELETE FROM villa_booking WHERE booking_id = ?";
    $stmt1 = $conn->prepare($deleteQuery);
    $stmt1->bind_param("i", $bid);
    $stmt1->execute();
    $stmt1->close();


    $villaId = $_GET['villa_id'];
    $username = $_SESSION['username'];
    // Fetch villa details
    $query = "SELECT * FROM villas1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $villaId);
    $stmt->execute();
    $result = $stmt->get_result();
    $villa = $result->fetch_assoc();
    $guestCapacity = $villa['guest_capacity'];

    $query1 = "SELECT * FROM user WHERE username = '$username'";
    $stmt1 = $conn->prepare($query1);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $user = $result1->fetch_assoc();
    $name = $user['fname'] . ' ' . $user['lname'];

    if ($villa) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data
            $checkInDate = $_POST['check_in_date'];
            $checkOutDate = $_POST['check_out_date'];
            $adults = $_POST['adults'];
            $children = $_POST['children'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $pricePerNight = $villa['price'];
            
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
            $totalPrice = $nights * $pricePerNight;
            $amount = $totalPrice * 100; // Convert to paise for Razorpay

            $availabilityQuery = "SELECT COUNT(*) AS booking_count 
                        FROM villa_booking 
                        WHERE vid = ? 
                        AND (
                            (checkin <= ? AND checkout >= ?) OR 
                            (checkin <= ? AND checkout >= ?) OR 
                            (checkin >= ? AND checkout <= ?)
                        )";
                        $stmt = $conn->prepare($availabilityQuery);
                        $stmt->bind_param("issssss", $villaId, $checkInDate, $checkInDate, $checkOutDate, $checkOutDate, $checkInDate, $checkOutDate);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $availability = $result->fetch_assoc();

                        if ($availability['booking_count'] >= 5) {
                        echo "<script> alert('This Villa is fully booked for the selected dates. Please choose different dates.'); setTimeout(function() { window.location.href = 'book2.php?villa_id=$villaId'; }, 1000);</script>";
                        exit();
            }

            // Razorpay Order Creation
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
                $orderId = $responseArray['id'];

                // Insert booking details into the database
                $insertQuery = "INSERT INTO villa_booking 
                    (username, fullname, vid, vname, vprice, checkin, checkout, adult, child, userphno, useremail, order_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param(
                    "ssissssiiiss",
                    $username,
                    $name,
                    $villaId,
                    $villa['name'],
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

                // Redirect to Razorpay payment gateway
                $_SESSION['amount'] = $amount;
                ?>

                <!-- Razorpay Payment Gateway -->
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    var options = {
                        "key": "<?= $apiKey ?>",
                        "amount": "<?= $_SESSION['amount'] ?>",
                        "currency": "INR",
                        "name": "Book My Stay",
                        "description": "Booking Payment",
                        "image": "https://your-site.com/logo.png",
                        "order_id": "<?= $orderId ?>",
                        "handler": function (response) {
                            // Submit payment data for verification
                            var form = document.createElement("form");
                            form.method = "POST";
                            form.action = "verify_payment2.php";

                            form.appendChild(createInput("razorpay_payment_id", response.razorpay_payment_id));
                            form.appendChild(createInput("razorpay_order_id", response.razorpay_order_id));
                            form.appendChild(createInput("razorpay_signature", response.razorpay_signature));

                            document.body.appendChild(form);
                            form.submit();
                        },
                        "prefill": {
                            "name": "<?= $username ?>",
                            "email": "<?= $email ?>",
                            "contact": "<?= $phone ?>"
                        },
                        "theme": {
                            "color": "#3399cc"
                        },
                        "modal": {
                            "ondismiss": function() {
                                window.location.href = "mybooking.php"; // Redirect on payment cancel
                            }
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();

                    function createInput(name, value) {
                        var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = name;
                        input.value = value;
                        return input;
                    }
                </script>
                <?php
            } else {
                echo "<p>Error creating Razorpay order. Please try again later.</p>";
            }
        } else {
            // Display booking form
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Book Villa</title>
                <link rel="stylesheet" href="payform.css">
                <style>
                    form { max-width: 600px; margin-top: 100px; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; }
                    input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
                    input[readonly], select[readonly] { background-color: #e9ecef; }
                </style>
            </head>
            <body>
                <form method="POST" class="payform">
                <h2>Booking Details</h2>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $_SESSION['username']; ?>" readonly>

                    <label for="username">Full Name:</label>
                    <input type="text" id="username" name="username" value="<?= $name ?>" >

                    <label for="villa_name">Villa Name:</label>
                    <input type="text" id="villa_name" value="<?= htmlspecialchars($villa['name']); ?>" readonly>

                    <label for="price">Price per Night (₹):</label>
                    <input type="text" id="price" value="<?= number_format($villa['price'], 2); ?>" readonly>

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
                            const guestCapacity = <?= $villa['guest_capacity']; ?>; // Fetch capacity from PHP
                            const form = document.querySelector("form");

                            function validateGuests() {
                                const adults = parseInt(adultsInput.value) || 0;
                                const children = parseInt(childrenInput.value) || 0;
                                const totalGuests = adults + children;

                                if (totalGuests > guestCapacity) {
                                    alert(`Error: Total guests cannot exceed ${guestCapacity}.`);
                                    return false;
                                }
                                return true;
                            }

                            // Validate input on change
                            adultsInput.addEventListener("input", validateGuests);
                            childrenInput.addEventListener("input", validateGuests);

                            // Validate before form submission
                            form.addEventListener("submit", function (event) {
                                if (!validateGuests()) {
                                    event.preventDefault(); // Stop form submission if invalid
                                }
                            });
                        });
                    </script>


                    <button type="submit">Pay Now</button>
                </form>
                
            </body>
            </html>
            <?php
        }
    } else {
        echo "<p>Invalid Villa ID.</p>";
    }
} else {
    echo "<p>No Villa ID provided.</p>";
}

include 'includes/footer.php';
$conn->close();
?>

