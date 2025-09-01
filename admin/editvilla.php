<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

// Fetch villa details based on ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM villas1 WHERE id = $id");
    $villa = mysqli_fetch_assoc($result);

    if (!$villa) {
        die("Villa not found!");
    }
} else {
    header("Location: dashboard.php?page=villa.php");
    exit();
}

// Update villa details
if (isset($_POST["update"])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $features = mysqli_real_escape_string($conn, $_POST["features"]);
    $facilities = mysqli_real_escape_string($conn, $_POST["facilities"]);
    $guest_capacity = mysqli_real_escape_string($conn, $_POST["guest_capacity"]);
    $area = mysqli_real_escape_string($conn, $_POST["area"]);
    $image_url = mysqli_real_escape_string($conn, $_POST["image_url"]);
    $iframe = mysqli_real_escape_string($conn, $_POST["iframe"]);

    $sql = "UPDATE villas1 
            SET name = '$name', location = '$location', price = '$price', description = '$description', 
                features = '$features', facilities = '$facilities', guest_capacity = '$guest_capacity', 
                area = '$area', image_url = '$image_url', iframe = '$iframe'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $success = "Villa updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM villas1 WHERE id = $id");
        $villa = mysqli_fetch_assoc($result);
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
    <title>Edit Villa</title>
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
        }
        .form-panel input[type="submit"] {
            background-color: #33c3a1;
            color: white;
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
        .back-btn {
            margin-top: 15px;
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
    </style>
</head>
<body>
    <div class="form-panel">
        <h2>Edit Villa</h2>
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($villa['name']); ?>" placeholder="Villa Name" required>
            <input type="text" name="location" value="<?php echo htmlspecialchars($villa['location']); ?>" placeholder="Location" required>
            <input type="number" name="price" value="<?php echo htmlspecialchars($villa['price']); ?>" placeholder="Price" required>
            <textarea name="description" rows="3" placeholder="Description" required><?php echo htmlspecialchars($villa['description']); ?></textarea>
            <textarea name="features" rows="3" placeholder="Features" required><?php echo htmlspecialchars($villa['features']); ?></textarea>
            <textarea name="facilities" rows="3" placeholder="Facilities" required><?php echo htmlspecialchars($villa['facilities']); ?></textarea>
            <input type="text" name="guest_capacity" value="<?php echo htmlspecialchars($villa['guest_capacity']); ?>" placeholder="Guest Capacity" required>
            <input type="text" name="area" value="<?php echo htmlspecialchars($villa['area']); ?>" placeholder="Area" required>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($villa['image_url']); ?>" placeholder="Image URL" required>
            <input type="text" name="iframe" value="<?php echo htmlspecialchars($villa['iframe']); ?>" placeholder="Google Maps Iframe Link" required>
            <input type="submit" name="update" value="Update Villa">
            <a href="dashboard.php?page=villa.php" class="back-btn">Back</a>
        </form>
    </div>
</body>
</html>
