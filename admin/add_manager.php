<?php
session_start();
include 'db_connect.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_id = intval($_POST['property_id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $type = $_POST['type']; // hotel, resort, villa
    // Determine the correct table
    if ($type == 'hotel') {
        $table = 'hotel_managers';
    } elseif ($type == 'resort') {
        $table = 'resort_managers';
    } elseif ($type == 'villa') {
        $table = 'villa_managers';
    } else {
        die('Invalid property type.');
    }

    $sql = "INSERT INTO $table (property_id, name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $property_id, $name, $email, $password);

    if ($stmt->execute()) {
        echo "Manager added successfully!";
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
    <title>Add Manager</title>
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
            margin-bottom: 100px;
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
    <h2>Add Manager</h2>
        <label>Property Type:</label>
        <select name="type" required>
            <option value="hotel">Hotel</option>
            <option value="resort">Resort</option>
            <option value="villa">Villa</option>
        </select><br>

        <label>Property ID:</label>
        <input type="number" name="property_id" required><br>

        <label>Manager Name:</label>
        <input type="text" name="name" required><br>
        
        <label>Email:</label>
        <input type="text" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Add Manager</button>
    </form>
</body>
</html>
