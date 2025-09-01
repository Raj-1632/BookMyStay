<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Add image
if (isset($_POST['add'])) {
    $image_url = $_POST['image_url'];
    mysqli_query($conn, "INSERT INTO carousel_images (image_url) VALUES ('$image_url')");
    echo "<script>alert('Image added successfully'); window.location.href = 'dashboard.php?page=carousel.php';</script>";
    exit();
}

// Update image
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $image_url = $_POST['image_url'];
    mysqli_query($conn, "UPDATE carousel_images SET image_url='$image_url' WHERE id=$id");
    echo "<script>alert('Image updated successfully'); window.location.href = 'dashboard.php?page=carousel.php';</script>";
    exit();
}

// Delete image
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM carousel_images WHERE id=$id");
    header("Location: dashboard.php?page=carousel.php");
    exit();
}

// Fetch all images
$result = mysqli_query($conn, "SELECT * FROM carousel_images");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4;}
        h2 { text-align: center;  }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color:rgb(31, 33, 35); color: white; font-size: 16px; }
        td { font-size: 14px; color: #333; }
        a, button { text-decoration: none; padding: 8px 12px; border-radius: 5px; color: white; border: none; cursor: pointer; }
        .edit-btn { background-color: #28a745; transition: 0.3s; }
        .delete-btn { background-color: #dc3545; transition: 0.3s; }
        .edit-btn:hover, .delete-btn:hover { opacity: 0.8; }
        .add-btn { background-color: #007bff; padding: 12px; margin-top: 12px; display: inline-block; margin-bottom: 10px; font-size: 16px; border-radius: 5px; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1); }
        form { margin: 20px 0; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        input { padding: 10px; font-size: 14px; border: 1px solid #ddd; border-radius: 5px; width: 200px; }
        button { font-size: 14px; }
        img { width: 100px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Manage Carousel</h2>
    
    <form method="POST">
        <input type="hidden" name="id" id="image_id">
        <input type="text" name="image_url" id="image_url" placeholder="Enter Image URL" required>
        <img id="image_preview" src="" alt="Image Preview" style="display:none;">
        <button type="submit" name="add" id="add-btn" class="add-btn">Add Image</button>
        <button type="submit" name="update" id="update-btn" class="edit-btn" style="display:none;">Update Image</button>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Image URL</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><img src="../<?php echo $row['image_url']; ?>" alt="Carousel Image"></td>
                <td><?php echo $row['image_url']; ?></td>
                <td>
                    <button class="edit-btn" onclick="editImage(<?php echo $row['id']; ?>, '../<?php echo $row['image_url']; ?>')">Edit</button>
                    <a class="delete-btn" href="carousel.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editImage(id, image_url) {
            document.getElementById('image_id').value = id;
            document.getElementById('image_url').value = image_url;
            document.getElementById('image_preview').src = image_url;
            document.getElementById('image_preview').style.display = 'block';
            document.getElementById('add-btn').style.display = 'none';
            document.getElementById('update-btn').style.display = 'inline-block';
        }
    </script>
</body>
</html>
