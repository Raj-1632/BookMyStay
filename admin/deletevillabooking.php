<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}


$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    
    $sql = "DELETE FROM `villa_booking` WHERE `booking_id` = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id); 
        if (mysqli_stmt_execute($stmt)) {
            
            echo "<script>alert('Booking deleted successfully.');setTimeout(function() { window.location.href = 'dashboard.php?page=villabooking.php'; }, 1000);</script>";   
            exit();
        } else {
            echo "<script>alert('Error deleting booking.');setTimeout(function() { window.location.href = 'dashboard.php?page=villabooking.php'; }, 1000);</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error deleting booking.');setTimeout(function() { window.location.href = 'dashboard.php?page=villabooking.php'; }, 1000);</script>";
    }
} else {
    echo "<script>alert('Error deleting booking.');setTimeout(function() { window.location.href = 'dashboard.php?page=villabooking.php'; }, 1000);</script>";
}

mysqli_close($conn);
?>
