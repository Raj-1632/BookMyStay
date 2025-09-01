<?php
session_start();
include 'includes/database.php';
include 'includes/header.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch the current booking details
    $query = "SELECT * FROM villa_booking WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        echo "Booking not found!";
        exit;
    }

    // Calculate original duration
    $original_checkin = $booking['checkin'];
    $original_checkout = $booking['checkout'];
    $original_duration = (strtotime($original_checkout) - strtotime($original_checkin)) / (60 * 60 * 24);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Villa Booking</title>
    <link rel="stylesheet" href="bookstyles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        input {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            margin-top: 15px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color:blue;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let originalDuration = <?php echo $original_duration; ?>;
            let checkinInput = document.getElementById("new_checkin");
            let checkoutInput = document.getElementById("new_checkout");

            checkinInput.addEventListener("change", function () {
                let checkinDate = new Date(checkinInput.value);
                if (checkinDate) {
                    let checkoutDate = new Date(checkinDate);
                    checkoutDate.setDate(checkoutDate.getDate() + originalDuration);
                    checkoutInput.value = checkoutDate.toISOString().split("T")[0];
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h2>Reschedule Villa Booking</h2>
    <form action="update_villa_booking.php" method="POST">
        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">

        <label>New Check-in Date:</label>
        <input type="date" id="new_checkin" name="new_checkin" required min="<?php echo date('Y-m-d'); ?>">

        <label>New Check-out Date:</label>
        <input type="date" id="new_checkout" name="new_checkout" required readonly>

        <button type="submit">Update Booking</button>
    </form>

    <div class="back-link">
        <a href="mybooking.php">Go Back to My Bookings</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
