<?php
session_start();
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

$rid = $_SESSION['property_id'];

// Fetch villa name based on property_id
$stmt = mysqli_prepare($conn, "SELECT name FROM villas1 WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $rid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $vname = $row['name'];
} else {
    die("Error fetching villa details: " . mysqli_error($conn));
}
mysqli_stmt_close($stmt);

// Fetch bookings for this villa
$stmt = mysqli_prepare($conn, "SELECT `booking_id`, `vid`, `vname`, `vprice`, `checkin`, `checkout`, `adult`, `child`, `username`, `fullname`, `userphno`, `useremail`, `payment_id`, `order_id`, `status` 
                               FROM `villa_booking` 
                               WHERE vname = ?");
mysqli_stmt_bind_param($stmt, "s", $vname);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Villa Bookings</title>
  <style>
  table { 
        width: 85%; 
        border-collapse: collapse; 
        margin-top: 20px;
        margin-left: 270px; 
    }
    th, td { 
        border: 1px solid #ddd;
        padding: 10px; 
        text-align: center; 
    }
    th { 
        background-color: #333; 
        color: white; 
    }
    a { 
        display: inline-block;
        text-decoration: none; 
        padding: 5px 10px; 
        border-radius: 5px; 
        color: white; 
    }
    .edit-btn { background-color: #28a745; }
    .delete-btn { background-color: #dc3545; }
    .add-btn { 
        background-color: #007bff; 
        padding: 10px; 
        margin-top: 20px; 
        display: inline-block; 
        margin-bottom: 10px; 
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .header h1 {
        font-size: 24px;
        color: #333;
        margin-left: 270px;
    }
    .header .logout-btn {
        padding: 8px 16px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }
  </style>
</head>
<body>
<div class="header">
        <h1>Manage Villa Bookings</h1>
    </div>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Villa ID</th>
                <th>Villa Name</th>
                <th>Price</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Adult</th>
                <th>Child</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>User Phone</th>
                <th>User Email</th>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row["booking_id"]) ?></td>
                    <td><?= htmlspecialchars($row["vid"]) ?></td>
                    <td><?= htmlspecialchars($row["vname"]) ?></td>
                    <td><?= htmlspecialchars($row["vprice"]) ?></td>
                    <td><?= htmlspecialchars($row["checkin"]) ?></td>
                    <td><?= htmlspecialchars($row["checkout"]) ?></td>
                    <td><?= htmlspecialchars($row["adult"]) ?></td>
                    <td><?= htmlspecialchars($row["child"]) ?></td>
                    <td><?= htmlspecialchars($row["username"]) ?></td>
                    <td><?= htmlspecialchars($row["fullname"]) ?></td>
                    <td><?= htmlspecialchars($row["userphno"]) ?></td>
                    <td><?= htmlspecialchars($row["useremail"]) ?></td>
                    <td><?= htmlspecialchars($row["payment_id"]) ?></td>
                    <td><?= htmlspecialchars($row["order_id"]) ?></td>
                    <td><?= htmlspecialchars($row["status"]) ?></td>
                    <td>
                        <a href='invoice2.php?order_id=<?= $row["order_id"] ?>' class='edit-btn'>Invoice</a>
                        <a href='deletevillabooking.php?delete_id=<?= $row["booking_id"] ?>' class='delete-btn' onclick='return confirm("Are you sure you want to delete this booking?");'>Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>

    <?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</body>
</html>
