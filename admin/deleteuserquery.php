<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom");
if (!$conn) {
    die("<script>alert('Database connection failed.');</script>");
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM user_queries WHERE srno = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Record deleted successfully.');
                setTimeout(function() { window.location.href = 'dashboard.php?page=userquery.php'; }, 1000);
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete the query. Please try again.');
                setTimeout(function() { window.location.href = 'dashboard.php?page=userquery.php'; }, 1000);
              </script>";
    }
    $stmt->close();
}

$conn->close();
?>
