<?php
session_start();
error_reporting(0);

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM `nearby_hotel` WHERE `place_id` = ?";
    if ($stmt = mysqli_prepare($conn, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: dashboard.php?page=nearbyhotel.php");
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Fetch all hotels
$result = mysqli_query($conn, "SELECT place_id, hotel_id, name, description, location, image_url FROM nearby_hotel");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Near By Places</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white;display: inline-block; }
        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .add-btn { background-color: #007bff; padding: 10px; margin-top: 20px; display: inline-block; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Manage Hotels Near By Places</h2>
    <a href="addnearbyhotel.php" class="add-btn">Add New Near By Places</a>
    <table>
        <tr>
            <th>Place ID</th>
            <th>Hotel ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Location</th>
            <th>Image Path</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['place_id']; ?></td>
                <td><?php echo $row['hotel_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['image_url']; ?></td>
                <td>
                    <a href="editnearbyhotel.php?id=<?php echo $row['place_id']; ?>" class="edit-btn">Edit</a>
                    <a href="nearbyhotel.php?delete_id=<?php echo $row['place_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this place?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
