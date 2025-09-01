<?php
session_start();
error_reporting(E_ALL);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Connection Failed");

// Fetch all managers from hotel_manager, resort_manager, and villa_manager tables
$hotel_managers = mysqli_query($conn, "SELECT id, property_id ,name, email FROM hotel_managers");
$resort_managers = mysqli_query($conn, "SELECT id, property_id ,name, email FROM resort_managers");
$villa_managers = mysqli_query($conn, "SELECT id, property_id ,name, email FROM villa_managers");
?>
<!DOCTYPE html>     
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Managers</title>
    <style>
        .head{ margin-bottom: 15px; margin-top: 15px;}
        .main{display: flex;}
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a, button { padding: 5px 10px; border-radius: 5px; text-decoration: none; color: white; }
        .edit-btn { background-color: #28a745; }
        .delete-btn { background-color: #dc3545; }
        .add-btn { background-color: #007bff; padding: 10px; display: flex; justify-content: center; margin-bottom: 10px; width: 160px; height: 37px; margin-top: auto; margin-left: auto;}
    </style>
</head>
<body>
    <div class="main">
        <h1 class="head">Manage Managers</h1>
        <a href="add_manager.php" class="add-btn">Add New Manager</a>
    </div>
    <h2 class="head">Hotel Managers</h2>
    <table>
        <tr><th>ID</th><th>Property ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
        <?php while ($row = mysqli_fetch_assoc($hotel_managers)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['property_id'] ?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['email']?></td>
                <td>
                    <a href="edit_manager.php?id=<?= $row['id'] ?>&type=hotel" class="edit-btn">Edit</a>
                    <a href="delete_manager.php?id=<?= $row['id'] ?>&type=hotel" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2 class="head">Resort Managers</h2>
    <table>
        <tr><th>ID</th><th>Property ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
        <?php while ($row = mysqli_fetch_assoc($resort_managers)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['property_id'] ?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['email']?></td>
                <td>
                    <a href="edit_manager.php?id=<?= $row['id'] ?>&type=resort" class="edit-btn">Edit</a>
                    <a href="delete_manager.php?id=<?= $row['id'] ?>&type=resort" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2 class="head">Villa Managers</h2>
    <table>
        <tr><th>ID</th><th>Property ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
        <?php while ($row = mysqli_fetch_assoc($villa_managers)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['property_id'] ?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['email']?></td>
                <td>
                    <a href="edit_manager.php?id=<?= $row['id'] ?>&type=villa" class="edit-btn">Edit</a>
                    <a href="delete_manager.php?id=<?= $row['id'] ?>&type=villa" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php mysqli_close($conn); ?>