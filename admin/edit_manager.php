<?php
session_start();
include 'db_connect.php'; // Include database connection file

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type'];

    if ($type == 'hotel') {
        $table = 'hotel_managers';
    } elseif ($type == 'resort') {
        $table = 'resort_managers';
    } elseif ($type == 'villa') {
        $table = 'villa_managers';
    } else {
        die('Invalid property type.');
    }

    $sql = "SELECT * FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $manager = $result->fetch_assoc();

    if (!$manager) {
        die('Manager not found.');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $property_id = intval($_POST['property_id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $type = $_POST['type'];

    if ($type == 'hotel') {
        $table = 'hotel_managers';
    } elseif ($type == 'resort') {
        $table = 'resort_managers';
    } elseif ($type == 'villa') {
        $table = 'villa_managers';
    } else {
        die('Invalid property type.');
    }

    if ($password) {
        $sql = "UPDATE $table SET property_id = ?, name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssi', $property_id, $name, $email, $password, $id);
    } else {
        $sql = "UPDATE $table SET property_id = ?, name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issi', $property_id, $name, $email, $id);
    }

    if ($stmt->execute()) {
        echo "Manager updated successfully!";
        header("Location: dashboard.php?page=manager.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            
        }

        form {
            padding: 10px;
            border: 2px solid black;
            border-radius: 5px;
        }

        label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            justify-content: center;
        }

        button:hover {
            background: #0056b3;
        }

    </style>
</head>
<body>
    
    <form method="POST">

    <h2>Edit Manager</h2>

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($manager['id']); ?>">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
        
        <label>Property ID:</label>
        <input type="number" name="property_id" value="<?php echo htmlspecialchars($manager['property_id']); ?>" required><br>

        <label>Manager Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($manager['name']); ?>" required><br>
        
        <label>Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($manager['email']); ?>" required><br>

        
        <label>New Password (Leave blank to keep current):</label>
        <input type="password" name="password"><br>
        
        <button type="submit">Update Manager</button>
        
    </form>
</body>
</html>
