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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request. No ID provided.";
    exit();
}

$place_id = $_GET['id'];

// Fetch existing data for the selected place
$query = "SELECT * FROM `nearby_villa` WHERE `place_id` = ?";
if ($stmt = mysqli_prepare($conn, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $place_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $place = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$place) {
        echo "No record found with the given ID.";
        exit();
    }
} else {
    echo "Error preparing query: " . mysqli_error($conn);
    exit();
}

// Handle form submission to update the record
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $image_url = $_POST['image_url'];

    $update_query = "UPDATE `nearby_villa` SET `villa_id` = ?, `name` = ?, `description` = ?, `location` = ?, `image_url` = ? WHERE `place_id` = ?";
    if ($stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($stmt, "issssi", $hotel_id, $name, $description, $location, $image_url, $place_id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?page=nearbyvilla.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing update query: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Near By Place</title>
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
    <h2>Edit Near By Place</h2>
    <form method="POST">
        <label for="hotel_id">Villa ID</label>
        <input type="number" id="hotel_id" name="hotel_id" value="<?php echo htmlspecialchars($place['villa_id']); ?>" required>

        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($place['name']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($place['description']); ?></textarea>

        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($place['location']); ?>" required>

        <label for="image_url">Image Path</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($place['image_url']); ?>" required>

        <button type="submit">Update Place</button>
        <a href="dashboard.php?page=nearbyvilla.php" class="back-btn">Cancel</a>
    </form>
</body>
</html>
