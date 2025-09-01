<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Fetch all rooms
$result = mysqli_query($conn, "SELECT * FROM rooms1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotel Rooms</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .add-btn { background-color: #007bff; padding: 10px; margin-top: 20px; display: inline-block; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Manage Hotel Rooms</h2>
    <a href="inserthotelroom.php" class="add-btn">Add New Room</a>
    <table>
        <tr>
            <th>Room ID</th>
            <th>Hotel ID</th>
            <th>Hotel Name</th>
            <th>Room Type</th>
            <th>Price Per Night</th>
            <th>Capacity</th>
            <th>Description</th>
            <th>Image</th>
            <th>360° Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['room_id']); ?></td>
                <td><?php echo htmlspecialchars($row['hotel_id']); ?></td>
                <td><?php echo htmlspecialchars($row['hotel_name']); ?></td>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo htmlspecialchars($row['price_per_night']); ?></td>
                <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['image_url']); ?></td>
                <td><?php echo htmlspecialchars($row['360_image_url']); ?></td>
                <td>
                    <a href="edithotelroom.php?room_id=<?php echo $row['room_id']; ?>" class="edit-btn">Edit</a>
                    <a href="deletehotelroom.php?room_id=<?php echo $row['room_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
