<?php
session_start(); // Start the session

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["admin"]);
    $password = $_POST["password"]; // No need to hash here; compare directly with the stored hash

    // Prepare the SQL query to fetch the hashed password
    $sql = "SELECT * FROM admin WHERE name = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the user record
        $user = mysqli_fetch_assoc($result);

        // Verify the password with the stored hash
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['admin'] = $username;

            // Redirect to dashboard.php
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script> alert('Invalid Username or Password!'); </script>";
        }
    } else {
        echo "<script> alert('Invalid Username or Password!'); </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <style>
        .container {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr; /* Flexible columns */
        }
        /* For tablets and smaller screens */
        @media (max-width: 768px) {
        .sidebar {
            display: none; /* Hide sidebar on smaller screens */
        }
        }

        /* For mobile devices */
        @media (max-width: 480px) {
        body {
             font-size: 14px;
            }
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .login-panel {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .login-panel h2 {
            background-color: #333;
            color: #fff;
            margin: -20px -20px 20px;
            padding: 15px;
            font-size: 18px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .login-panel input[type="text"],
        .login-panel input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-panel input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #33c3a1;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-panel input[type="submit"]:hover {
            background-color: #28a08e;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-panel">
        <h2>ADMIN LOGIN PANEL</h2>
        <form action="" method="POST">
            <input type="text" name="admin" placeholder="Admin Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="LOGIN">
        </form>
    </div>
</body>
</html>
