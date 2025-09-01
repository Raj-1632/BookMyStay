<?php
session_start();
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_type = $_POST['property_type']; // 'hotel', 'resort', or 'villa'
    $property_id = $_POST['property_id'];
    $password = $_POST['password'];

    // Determine the correct table based on property type
    if ($property_type == 'hotel') {
        $table = 'hotel_managers';
    } elseif ($property_type == 'resort') {
        $table = 'resort_managers';
    } elseif ($property_type == 'villa') {
        $table = 'villa_managers';
    } else {
        die("Invalid property type.");
    }

    // Check login credentials
    $sql = "SELECT * FROM $table WHERE property_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $manager = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $manager['password'])) {
            // Password is correct, set session variables
            $_SESSION['manager_id'] = $manager['id'];
            $_SESSION['property_id'] = $manager['property_id'];
            $_SESSION['property_type'] = $property_type;

            // Redirect to respective dashboard based on property type
            if ($property_type == 'hotel') {
                header("Location: hotel/dashboard.php");
                exit();
            } elseif ($property_type == 'resort') {
                header("Location: resort/dashboard_resort.php");
                exit();
            } elseif ($property_type == 'villa') {
                header("Location: villa/dashboard_villa.php");
                exit();
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid login credentials.";
    }
}
?>
