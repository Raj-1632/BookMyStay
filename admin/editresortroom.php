<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Fetch room details for editing
if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
    $result = mysqli_query($conn, "SELECT * FROM rooms2 WHERE room_id = $room_id");
    $room = mysqli_fetch_assoc($result);
    if (!$room) {
        die("Room not found.");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resort_id = intval($_POST['resort_id']);
    $resort_name = mysqli_real_escape_string($conn, $_POST['resort_name']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price_per_night = floatval($_POST['price_per_night']);
    $capacity = intval($_POST['capacity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $image_360_url = mysqli_real_escape_string($conn, $_POST['360_image_url']);

    $sql = "UPDATE rooms2 SET 
            resort_id = '$resort_id',
            resort_name = '$resort_name',
            room_type = '$room_type', 
            price_per_night = '$price_per_night', 
            capacity = '$capacity', 
            description = '$description', 
            image_url = '$image_url', 
            360_image_url = '$image_360_url'
            WHERE room_id = $room_id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Room updated successfully!'); window.location.href='dashboard.php?page=resortrooms.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to update room.');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resort Room</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
        }
        form { background: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            max-width: 600px; 
            margin: auto; 
        }
        label { display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
        }
        input, textarea { 
            width: 97%; 
            padding: 10px; 
            margin-bottom: 20px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
        }
        button { background-color: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            cursor: pointer;
        }
        button:hover { 
            background-color: #0056b3; 
        }
        .back-btn {
            margin-top: 15px;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h2>Edit Resort Room</h2>
    <form method="POST" action="">
        <label for="resort_id">Resort ID</label>
        <input type="number" id="resort_id" name="resort_id" value="<?php echo htmlspecialchars($room['resort_id']); ?>" required>

        <label for="resort_name">Resort Name</label>
        <input type="text" id="resort_name" name="resort_name" value="<?php echo htmlspecialchars($room['resort_name']); ?>" required>

        <label for="room_type">Room Type</label>
        <input type="text" id="room_type" name="room_type" value="<?php echo htmlspecialchars($room['room_type']); ?>" required>

        <label for="price_per_night">Price Per Night</label>
        <input type="number" step="0.01" id="price_per_night" name="price_per_night" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>

        <label for="capacity">Capacity</label>
        <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($room['capacity']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($room['description']); ?></textarea>

        <label for="image_url">Image URL</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($room['image_url']); ?>">

        <label for="360_image_url">360 Image URL</label>
        <input type="text" id="360_image_url" name="360_image_url" value="<?php echo htmlspecialchars($room['360_image_url']); ?>">

        <button type="submit">Update Room</button>
        <a href="dashboard.php?page=resortrooms.php" class="back-btn">Back</a>
    </form>
</body>
</html>
