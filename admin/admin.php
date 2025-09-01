<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
$logged_in_admin_name = $_SESSION['admin'];
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

// Fetch all admins
$sql = "SELECT `id`, `name` FROM `admin`";
$result = mysqli_query($conn, $sql);

// Handle delete admin
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM `admin` WHERE `id` = ?";
    if ($stmt = mysqli_prepare($conn, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header("Location: dashboard.php?page=admin.php");
        exit();
    }
}

// Handle change password
if (isset($_POST['change_password'])) {
    $admin_id = $_POST['admin_id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $password_sql = "UPDATE `admin` SET `password` = ? WHERE `id` = ?";
    if ($stmt = mysqli_prepare($conn, $password_sql)) {
        mysqli_stmt_bind_param($stmt, "si", $new_password, $admin_id);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Password updated successfully.'); setTimeout(function() { window.location.href = 'dashboard.php?page=admin.php'; }, 1000);</script>";
        exit();
    }
}

// Handle create admin
if (isset($_POST['create_admin'])) {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if admin already exists
    $check_sql = "SELECT * FROM `admin` WHERE `name` = ?";
    if ($stmt = mysqli_prepare($conn, $check_sql)) {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "<script>alert('Admin name already exists. Try another one.'); window.location.href = 'dashboard.php?page=admin.php';</script>";
            exit();
        }
    }
    
    $create_sql = "INSERT INTO `admin` (`name`, `password`) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $create_sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $name, $password);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Admin created successfully.'); setTimeout(function() { window.location.href = 'dashboard.php?page=admin.php'; }, 1000);</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1, h3 {
            color: #444;
        }

        /* Header */
        .header {
            
            padding: 20px;
            color: white;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .change-password-btn {
            background-color: #28a745;
        }

        .create-btn {
            background-color: #333;
            padding: 10px 20px;
            margin-top: 20px;
            display: inline-block;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .create-btn:hover, .delete-btn:hover, .change-password-btn:hover {
            opacity: 0.8;
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Form Styles */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        form h3{
            margin-bottom : 20px;
        }
        label {
            font-size: 14px;
            font-style : bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Modal Styles */
        #changePasswordModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        #changePasswordModal form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 20px;
            }

            .create-btn {
                width: 100%;
                text-align: center;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Admins</h1>
    </div>

    <!-- Create Admin Form -->
    <div>
        
        <form method="POST" action="dashboard.php?page=admin.php">
        <h3>Create Admin</h3>
            <label for="name">Username:</label>
            <input type="text" name="name" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br><br>
            <button type="submit" name="create_admin" class="create-btn">Create Admin</button>
        </form>
    </div>

    <!-- Admin List Table -->
    <h3>List of Admins</h3>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>Admin ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["id"]) . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>";
                    
                    // Check if it's the logged-in admin
                    if ($row["name"] == $logged_in_admin_name) {
                        echo "<a href='#changePasswordModal' class='btn change-password-btn' data-toggle='modal' data-id='" . $row["id"] . "'>Change Password</a>";
                    } else {
                        echo "<a href='admin.php?delete_id=" . $row["id"] . "' class='btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this admin?\");'>Delete</a>";
                    }
                    
                    echo "</td>
                        </tr>";
                }
                echo "</table>";
    } else {
        echo "No admins found.";
    }
    ?>

    <!-- Change Password Modal -->
    <div id="changePasswordModal">
        <form method="POST" action="dashboard.php?page=admin.php">
            <input type="hidden" name="admin_id" id="admin_id">
            <h3>Change Password</h3>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required><br><br>
            <button type="submit" name="change_password" class="create-btn">Change Password</button>
        </form>
    </div>

    <script>
        
        document.querySelectorAll('.change-password-btn').forEach(button => {
            button.addEventListener('click', function () {
                var adminId = this.getAttribute('data-id');
                document.getElementById('admin_id').value = adminId;
                document.getElementById('changePasswordModal').style.display = 'flex';
            });
        });

        
        document.getElementById('changePasswordModal').addEventListener('click', function (e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    </script>
</body>
</html>
