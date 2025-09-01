<?php
session_start();
include "../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manager_id = $_POST['manager_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (!isset($_SESSION['property_type'])) {
        die("Unauthorized access.");
    }

    $property_type = $_SESSION['property_type'];
    switch ($property_type) {
        case 'hotel': $table = 'hotel_managers'; break;
        case 'resort': $table = 'resort_managers'; break;
        case 'villa': $table = 'villa_managers'; break;
        default: die("Invalid property type.");
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE $table SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $manager_id);
    } else {
        $sql = "UPDATE $table SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $manager_id);
    }

    if ($stmt->execute()) {
        echo "<script>
                alert('Profile updated successfully.');
                setTimeout(function() { 
                    window.location.href = 'dashboard_resort.php?page=edit_profile.php'; 
                }, 1000);
              </script>";
    } else {
        echo "<script>
                alert('Error updating profile: " . addslashes($conn->error) . "');
                setTimeout(function() { 
                    window.location.href = 'dashboard_resort.php?page=edit_profile.php'; 
                }, 1000);
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
