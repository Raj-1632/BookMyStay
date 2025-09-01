<?php
session_start(); // Start the session

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "bookmyroom");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure `property_id` is set
if (!isset($_SESSION['property_id'])) {
    die("Error: Property ID not found in session.");
}

$id = $_SESSION['property_id'];

// Fetch resort details
$sql = "SELECT * FROM resorts1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$resort = $result->fetch_assoc();

if (!$resort) {
    die("Error: Resort not found!");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    // Sanitize inputs
    $name = htmlspecialchars($_POST["name"]);
    $location = htmlspecialchars($_POST["location"]);
    $price = floatval($_POST["price"]);
    $description = htmlspecialchars($_POST["description"]);
    $features = htmlspecialchars($_POST["features"]);
    $facilities = htmlspecialchars($_POST["facilities"]);
    $guest_capacity = htmlspecialchars($_POST["guest_capacity"]);
    $area = htmlspecialchars($_POST["area"]);
    $image_url = htmlspecialchars($_POST["image_url"]);
    $iframe = htmlspecialchars($_POST["iframe"]);

    // Update the resort details securely
    $update_sql = "UPDATE resorts1 
                   SET name=?, location=?, price=?, description=?, 
                       features=?, facilities=?, guest_capacity=?, 
                       area=?, image_url=?, iframe=? 
                   WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssdsssssssi", 
        $name, $location, $price, $description, $features, $facilities, 
        $guest_capacity, $area, $image_url, $iframe, $id
    );

    if ($update_stmt->execute()) {
        $success = "Resort updated successfully!";
        // Refresh the resort data
        $stmt->execute();
        $result = $stmt->get_result();
        $resort = $result->fetch_assoc();
    } else {
        $error = "Error updating record: " . $update_stmt->error;
    }

    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resort</title>
    <style>
        .form-panel {
            background-color: #fff;
            width: 500px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-left: 800px;
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
        <h2>Edit Resort</h2>
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($resort['name']); ?>" placeholder="Resort Name" required>
            <input type="text" name="location" value="<?php echo htmlspecialchars($resort['location']); ?>" placeholder="Location" required>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($resort['price']); ?>" placeholder="Price" required>
            <textarea name="description" rows="3" placeholder="Description" required><?php echo htmlspecialchars($resort['description']); ?></textarea>
            <textarea name="features" rows="3" placeholder="Features" required><?php echo htmlspecialchars($resort['features']); ?></textarea>
            <textarea name="facilities" rows="3" placeholder="Facilities" required><?php echo htmlspecialchars($resort['facilities']); ?></textarea>
            <input type="text" name="guest_capacity" value="<?php echo htmlspecialchars($resort['guest_capacity']); ?>" placeholder="Guest Capacity" required>
            <input type="text" name="area" value="<?php echo htmlspecialchars($resort['area']); ?>" placeholder="Area (e.g., 500 sqft)" required>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($resort['image_url']); ?>" placeholder="Image URL" required>
            <input type="text" name="iframe" value="<?php echo htmlspecialchars($resort['iframe']); ?>" placeholder="Google Maps Iframe Link" required>
            <input type="submit" name="update" value="Update Resort">
            <a href="dashboard_resort.php?page=resort.php" class="back-btn">Back</a>
        </form>
    </div>
</body>
</html>
