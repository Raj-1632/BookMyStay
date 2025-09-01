<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

$sql = "SELECT `booking_id`, `room_id`, `rname`, `hname`, `hprice`, `checkin`, `checkout`, `adult`, `child`, `username`, `fullname`, `userphno`, `useremail`, `payment_id`, `order_id`, `status` FROM `hotel_booking`";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Hotel Bookings</title>
  <style>
    body { 
        font-family: Arial, sans-serif; 
        background-color: #f4f4f4; 
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 20px; 
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
    .edit-btn { 
        background-color: #28a745; 
    }
    .delete-btn { 
        background-color: #dc3545; 
    }
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
        <h1>Manage Hotel Bookings</h1>
    </div>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>Booking ID</th>
                    <th>Hotel Name</th>
                    <th>Room ID</th>
                    <th>Room Name</th>
                    <th>Price</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Adult</th>
                    <th>Child</th>
                    <th>User Name</th>
                    <th>Full Name</th>
                    <th>User Phone</th>
                    <th>User Email</th>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["booking_id"]) . "</td>
                    <td>" . htmlspecialchars($row["hname"]) . "</td>
                    <td>" . htmlspecialchars($row["room_id"]) . "</td>
                    <td>" . htmlspecialchars($row["rname"]) . "</td>
                    <td>" . htmlspecialchars($row["hprice"]) . "</td>
                    <td>" . htmlspecialchars($row["checkin"]) . "</td>
                    <td>" . htmlspecialchars($row["checkout"]) . "</td>
                    <td>" . htmlspecialchars($row["adult"]) . "</td>
                    <td>" . htmlspecialchars($row["child"]) . "</td>
                    <td>" . htmlspecialchars($row["username"]) . "</td>
                    <td>" . htmlspecialchars($row["fullname"]) . "</td>
                    <td>" . htmlspecialchars($row["userphno"]) . "</td>
                    <td>" . htmlspecialchars($row["useremail"]) . "</td>
                    <td>" . htmlspecialchars($row["payment_id"]) . "</td>
                    <td>" . htmlspecialchars($row["order_id"]) . "</td>
                    <td>" . htmlspecialchars($row["status"]) . "</td>
                    <td>
                        <a href='invoice.php?order_id=" . $row["order_id"] . "' class='edit-btn'>Invoice</a>
                        <a href='deletehotelbooking.php?delete_id=" . $row["booking_id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this booking?\");'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No data found";
    }

    mysqli_close($conn); // Close the connection
    ?>
</body>
</html>
