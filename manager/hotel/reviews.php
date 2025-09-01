<?php
session_start();

$conn = new mysqli("localhost", "root", "", "bookmyroom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['manager_id'])) {
    header("Location: /manager/login.php");
    exit();
}
$property_id = $_SESSION['property_id'];
// Delete review securely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    $stmt = $conn->prepare("DELETE FROM hotel_reviews WHERE id = ? AND property_id = ?");
    $stmt->bind_param("ii", $id, $property_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=reviews.php");
    exit();
}


$result = mysqli_query($conn, "
    SELECT hr.id, u.username, p.name AS hotel_name, hr.rating, hr.review
    FROM hotel_reviews hr
    JOIN user u ON hr.user_id = u.user_id
    JOIN hotels1 p ON hr.property_id = p.id where p.id = $property_id
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reviews</title>
    <style>
        table { 
            width: 85%; 
            border-collapse: collapse; 
            margin-top: 20px;
            margin-left: 270px;  
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
            display: inline-block;
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .header .logout-btn {
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .head{
            margin-left: 270px;
        }
    </style>    
</head>
<body>
    <h2 class="head">Hotel Reviews</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Hotel</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['hotel_name']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['review']) ?></td>
                <td><a class="delete-btn" href="reviews.php?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
