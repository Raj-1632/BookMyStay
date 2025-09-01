<?php
session_start();
include("db_connect.php"); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type'];
    
    // Determine table name based on manager type
    $table = "";
    switch ($type) {
        case "hotel":
            $table = "hotel_managers";
            break;
        case "resort":
            $table = "resort_managers";
            break;
        case "villa":
            $table = "villa_managers";
            break;
        default:
            echo "Invalid manager type!";
            exit();
    }
    
    // Delete the manager from the respective table
    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Manager deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting manager.";
    }
    
    $stmt->close();
    $conn->close();
    
    header("Location: dashboard.php?page=manager.php"); // Redirect back to managers list
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage_managers.php");
    exit();
}
