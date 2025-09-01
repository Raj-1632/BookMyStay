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
    $stmt = $conn->prepare("DELETE FROM resort_reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=resort_review.php");
    exit();
}

$result = mysqli_query($conn, "
     SELECT rr.id, u.username, r.name AS resort_name, rr.rating, rr.review
     FROM resort_reviews rr
     JOIN user u ON rr.user_id = u.user_id
     JOIN resorts1 r ON rr.property_id = r.id
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Reviews</title>
    <link rel="stylesheet" href="review.css">
</head>
<body>
    <h2>Resort Reviews</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Resort</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['resort_name']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['review']) ?></td>
                <td><a class="delete-btn" href="resort_review.php?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
