<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");
$id = $_SESSION['manager_id'];

// Get the property_id assigned to the manager
$query = "SELECT property_id FROM hotel_managers WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result1 = mysqli_stmt_get_result($stmt);
$row1 = mysqli_fetch_assoc($result1);
$hid = $row1['property_id'];

$query = "SELECT name FROM hotels1 WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $hid);
mysqli_stmt_execute($stmt);
$result1 = mysqli_stmt_get_result($stmt);
$row1 = mysqli_fetch_assoc($result1);
$hname = $row1['name'];

// Fetch cab bookings for the assigned hotel
$query = "SELECT * FROM cab_booking_hotel WHERE dropoff = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $hname);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM `cab_booking_hotel` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php?page=cabbooking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage cab bookings</title>
    <style>
        .head{margin-left: 300px;}
        table { width: 85%; border-collapse: collapse; margin-top: 20px; margin-left: 275px;}
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .add-btn { background-color: #007bff; padding: 10px; margin-top: 20px; display: inline-block; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2 class="head">Manage Cab Booking</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Booking ID</th>
            <th>User</th>
            <th>Pick Up</th>
            <th>Drop</th>
            <th>Date</th>
            <th>Time</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['hotel_booking_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['pickup']; ?></td>
                <td><?php echo $row['dropoff']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['contact']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="cabbooking.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>