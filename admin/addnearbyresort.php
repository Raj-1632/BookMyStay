<?php
session_start(); // Start the session
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

if (isset($_POST["submit"])) {
    $hotel_id = mysqli_real_escape_string($conn,$_POST['hotel_id']);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $img_url = mysqli_real_escape_string($conn,$_POST['img_url']);

    $sql = "INSERT INTO nearby_resort (resort_id,name, description, location, image_url) 
            VALUES ('$hotel_id', '$name', '$description' ,'$location', '$img_url' )";

    if (mysqli_query($conn, $sql)) {
        $success = "Resort Nearby places added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Resort</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .form-panel {
            background-color: #fff;
            width: 500px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-panel h2 {
            background-color: #333;
            color: #fff;
            margin: -20px -20px 20px;
            padding: 15px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .form-panel input, .form-panel textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-panel input[type="submit"] {
            background-color: #33c3a1;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .form-panel input[type="submit"]:hover {
            background-color: #28a08e;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .logout-btn {
            margin-top: 15px; /* Adds space between Create and Back */
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            width: 100%;
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
    <div class="form-panel">
        <h2>Add Resort Nearby Place</h2>
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="hotel_id" placeholder="Resort Id" required>
            <input type="text" name="name" placeholder="Place Name" required>
            <textarea name="description" placeholder="Description" rows="3" required></textarea>
            <input type="text" name="location" placeholder="Location" required>
            <input type="text" name="img_url" placeholder="Image Url" required>
            <input type="submit" name="submit" value="Add Place">
            <a href="dashboard.php?page=nearbyresort.php" class="logout-btn">Back</a>
        </form>
    </div>
</body>
</html>
