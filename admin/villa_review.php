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
    $stmt = $conn->prepare("DELETE FROM villa_reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=villa_review.php");
    exit();
}

$result = mysqli_query($conn, "
     SELECT vr.id, u.username, v.name AS villa_name, vr.rating, vr.review
     FROM villa_reviews vr
     JOIN user u ON vr.user_id = u.user_id
     JOIN villas1 v ON vr.property_id = v.id
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Reviews</title>
    <link rel="stylesheet" href="review.css">
</head>
<body>
    <h2>Villa Reviews</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Villa</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['villa_name']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['review']) ?></td>
                <td><a class="delete-btn" href="villa_review.php?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
