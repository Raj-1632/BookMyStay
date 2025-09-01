<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
    if (mysqli_query($conn, "DELETE FROM rooms1 WHERE room_id = $room_id")) {
        echo "<script>alert('Room deleted successfully.');setTimeout(function() { window.location.href = 'dashboard.php?page=hotelrooms.php'; }, 1000);</script>";
    } else {
        echo "<script>alert('Failed to delete Room.'); setTimeout(function() { window.location.href = 'dashboard.php?page=hotelrooms.php'; }, 1000);</script>";
    }
}
?>
