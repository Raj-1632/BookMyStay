<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

$rid = $_SESSION['property_id'];

// Fetch resort name based on property_id
$qry = mysqli_query($conn, "SELECT name FROM resorts1 WHERE id = $rid");
if ($qry) {
    $resort = mysqli_fetch_assoc($qry);
    $rname = $resort['name'];

    // Fetch rooms based on resort name
    $result = mysqli_query($conn, "SELECT * FROM rooms2 WHERE resort_name = '$rname'");
} else {
    echo "Error fetching resort details.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resort Rooms</title>
    <style>
        .head { margin-left: 270px; }
        table { width: 85%; border-collapse: collapse; margin-top: 20px; margin-left: 270px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .add-btn { background-color: #007bff; padding: 10px; margin-top: 20px; display: inline-block; margin-bottom: 10px; margin-left: 270px; }
    </style>
</head>
<body>
    <h2 class="head">Manage Resort Rooms</h2>
    <a href="insertresortroom.php" class="add-btn">Add New Resort Room</a>
    <table>
        <tr>
            <th>Room ID</th>
            <th>Resort ID</th>
            <th>Resort Name</th>
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
                <td><?php echo htmlspecialchars($row['resort_id']); ?></td>
                <td><?php echo htmlspecialchars($row['resort_name']); ?></td>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td><?php echo htmlspecialchars($row['price_per_night']); ?></td>
                <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><img src="<?php echo htmlspecialchars('/bookmystay/'.$row['image_url']); ?>" alt="Room Image" width="100"></td>
                <td><img src="<?php echo htmlspecialchars('/bookmystay/web/web/'.$row['360_image_url']); ?>" alt="360° View" width="100"></td>
                <td>
                    <a href="editresortroom.php?room_id=<?php echo $row['room_id']; ?>" class="edit-btn">Edit</a>
                    <a href="deleteresortroom.php?room_id=<?php echo $row['room_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
