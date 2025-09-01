<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM user WHERE user_id = $delete_id")) {
        echo "<script>alert('User deleted successfully.');setTimeout(function() { window.location.href = 'dashboard.php?page=user.php'; }, 1000);</script>";
    } else {
        echo "<script>alert('Failed to delete User.'); setTimeout(function() { window.location.href = 'dashboard.php?page=user.php'; }, 1000);</script>";
    }
}
?>
