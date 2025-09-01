<?php
// Start the session
session_start();
error_reporting(0);

// Include database connection
include 'includes/database.php';
include 'includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch the username from the session
$username = $_SESSION['username'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$query = "";
if($filter == 'Hotel'){
    $query = " SELECT 'Hotel' AS booking_type, booking_id, order_id, hname AS name, checkin, checkout, hprice AS price ,status
    FROM hotel_booking WHERE username = ?";
} elseif($filter == 'Resort'){
    $query = "SELECT 'Resort' AS booking_type, booking_id, order_id, rname AS name, checkin, checkout, rprice AS price ,status
    FROM resort_booking WHERE username = ?";
} elseif($filter == 'Villa'){
    $query = " SELECT 'Villa' AS booking_type, booking_id, order_id, vname AS name, checkin, checkout, vprice AS price ,status
    FROM villa_booking WHERE username = ?";
} else{
$query = "
    SELECT 'Hotel' AS booking_type, booking_id, order_id, hname AS name, checkin, checkout, hprice AS price ,status
    FROM hotel_booking WHERE username = ?
    UNION
    SELECT 'Resort' AS booking_type, booking_id, order_id, rname AS name, checkin, checkout, rprice AS price ,status
    FROM resort_booking WHERE username = ?
    UNION
    SELECT 'Villa' AS booking_type, booking_id, order_id, vname AS name, checkin, checkout, vprice AS price ,status
    FROM villa_booking WHERE username = ?
    ORDER BY checkin DESC
";
}
$stmt = $conn->prepare($query);
if ($filter) {
    $stmt->bind_param("s", $username);
} else {
    $stmt->bind_param("sss", $username, $username, $username);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="bookstyles.css">
    <style>
        .head{
            text-align : center;
            align-items: center;
            text-decoration : underline;
            margin-bottom : 10px;
        }
        button{
            width: 120px;    
        }
        .filter-buttons {
            display: flex;
            justify-content: center; /* Center horizontally */
            gap: 10px; /* Space between buttons */
            margin-bottom: 20px;
        }

        .filter-buttons button {
            background-color: white;
            color: black;
            border: none;
            text-align: center;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .filter-buttons p{
            background-color: none;
            color: black;
            border: none;
            padding: 10px 20px;
            font-size: 19px;
            font-style : bold;
            border-radius: 5px;
        }
        .filter-buttons button:hover {
            background-color: #218838;
            color : white;
        }
    </style>
</head>
<body>
    
    <h1 class="head">My Bookings</h1>

    <div class="filter-buttons">
        <p>Filter by : </p> 
        <button onclick="filterBookings('')">All</button>
        <button onclick="filterBookings('Hotel')">Hotels</button>
        <button onclick="filterBookings('Resort')">Resorts</button>
        <button onclick="filterBookings('Villa')">Villas</button>
    </div>
    
    <main>
        <?php if ($result->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Booking Type</th>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $currentDate = date("Y-m-d");
                    $isCheckinPastOrToday = strtotime($row['checkin']) <= strtotime($currentDate);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['checkin']); ?></td>
                        <td><?php echo htmlspecialchars($row['checkout']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'paid' && !$isCheckinPastOrToday): ?>
                                <form action="<?php echo ($row['booking_type'] === 'Hotel') ? 'reschedule_hotel_booking.php' : (($row['booking_type'] === 'Resort') ? 'reschedule_resort_booking.php' : 'reschedule_villa_booking.php'); ?>" method="GET" style="display:inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <button type="submit">Reschedule</button>
                                </form>
                            <?php endif; ?>

                            <form action="<?php echo ($row['booking_type'] === 'Hotel') ? 'generate_invoice.php' : (($row['booking_type'] === 'Resort') ? 'generate_invoice1.php' : 'generate_invoice2.php'); ?>" method="GET" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <button type="submit">Details</button>
                            </form>

                            <?php if ($row['status'] !== 'canceled' && $row['status'] !=='pending' && !$isCheckinPastOrToday): ?>
                                <form action="<?php echo ($row['booking_type'] === 'Hotel') ? 'cancelhotel.php' : (($row['booking_type'] === 'Resort') ? 'cancelresort.php' : 'cancelvilla.php'); ?>" method="GET" style="display:inline;" onsubmit="return confirmCancel();">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <button type="submit">Cancel</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($row['status'] === 'paid' && $isCheckinPastOrToday): ?>
                                <form action="review.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <input type="hidden" name="booking_type" value="<?php echo htmlspecialchars($row['booking_type']); ?>">
                                    <button type="submit">Review</button>
                                </form>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
    function confirmCancel() {
        return confirm("Are you sure you want to cancel this booking?");
    }
    </script>
    <script>
        function filterBookings(type) {
            window.location.href = 'mybooking.php?filter=' + type;
        }
    </script>
</body>
</html>
