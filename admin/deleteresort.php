<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM resorts1 WHERE id = $delete_id")) {
        echo "<script>alert('Resort record deleted successfully.'); setTimeout(function() { window.location.href = 'dashboard.php?page=resort.php'; }, 1000);</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete the resort record.'); setTimeout(function() { window.location.href = 'dashboard.php?page=resort.php'; }, 1000);</script>";
    }
}
?>
