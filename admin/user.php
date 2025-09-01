<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM user");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
        }
        th { 
            background-color: #333; 
            color: white; 
        }
        a { 
            text-decoration: none; 
            padding: 5px 10px; 
            border-radius: 5px; 
            color: white; 
        }
        .edit-btn { 
            background-color: #28a745; 
        }
        .delete-btn { 
            background-color: #dc3545; 
        }
        .add-btn { 
            background-color: #007bff; 
            padding: 10px; 
            margin-top: 20px; 
            display: inline-block; 
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Date of Birth</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['fname']; ?></td>
                <td><?php echo $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phno']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td>
                    <a href="deleteuser.php?delete_id=<?php echo $row['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
