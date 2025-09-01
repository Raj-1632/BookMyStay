<?php
session_start();
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Connection Error");
$mid = $_SESSION['manager_id'];
$qry = "select property_id from resort_managers where id = $mid";
$result = mysqli_query($conn, $qry);
$result = mysqli_fetch_assoc($result);
$pid = $result['property_id'];
$qry1 = "select id from resorts1 where id = $pid";
$result1 = mysqli_query($conn, $qry1);
$result1 = mysqli_fetch_assoc($result1);
$rid = $result1['id'];
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resort_id = intval($_POST['resort_id']);
    $resort_name = mysqli_real_escape_string($conn, $_POST['resort_name']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $price_per_night = floatval($_POST['price_per_night']);
    $capacity = intval($_POST['capacity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $image_360_url = mysqli_real_escape_string($conn, $_POST['image_360_url']);

    $sql = "INSERT INTO rooms2 (resort_id, resort_name, room_type, price_per_night, capacity, description, image_url, 360_image_url) 
            VALUES ('$resort_id', '$resort_name', '$room_type', '$price_per_night', '$capacity', '$description', '$image_url', '$image_360_url')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Resort room added successfully!'); window.location.href='dashboard_resort.php?page=resortrooms.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add resort room.');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Resort Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        label {
            display: block;
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
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            margin-top: 15px; /* Adds space between Create and Back */
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            box-sizing: border-box;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h2>Insert New Resort Room</h2>
    <form method="POST" action="">
        <label for="resort_id">Resort ID</label>
        <input type="number" id="resort_id" name="resort_id" value="<?php echo $rid; ?>" readonly>

        <label for="resort_name">Resort Name</label>
        <input type="text" id="resort_name" name="resort_name" required>

        <label for="room_type">Room Type</label>
        <input type="text" id="room_type" name="room_type" required>

        <label for="price_per_night">Price Per Night</label>
        <input type="number" step="0.01" id="price_per_night" name="price_per_night" required>

        <label for="capacity">Capacity</label>
        <input type="number" id="capacity" name="capacity" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="image_url">Image URL</label>
        <input type="text" id="image_url" name="image_url">

        <label for="image_360_url">360 Image URL</label>
        <input type="text" id="image_360_url" name="image_360_url">

        <button type="submit">Add Resort Room</button>
        <a href="dashboard_resort.php?page=resortrooms.php" class="logout-btn">Back</a>
    </form>
</body>
</html>
