<?php
session_start();
include 'db_connect.php'; // Include your database connection

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Delete review securely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Ensure it's an integer
    $stmt = $conn->prepare("DELETE FROM hotel_reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=hotel_review.php");
    exit();
}

$result = mysqli_query($conn, "
    SELECT hr.id, u.username, p.name AS hotel_name, hr.rating, hr.review
    FROM hotel_reviews hr
    JOIN user u ON hr.user_id = u.user_id
    JOIN hotels1 p ON hr.property_id = p.id
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reviews</title>
    <link rel="stylesheet" href="review.css">
</head>
<body>
    <h2>Hotel Reviews</h2>
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
                <td><a class="delete-btn" href="hotel_review.php?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
