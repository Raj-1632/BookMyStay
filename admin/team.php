<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");

// Add member
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $img_path = $_POST['img_path'];
    mysqli_query($conn, "INSERT INTO team (name, role, img_path) VALUES ('$name', '$role', '$img_path')");
    if ($conn) {
        echo "<script>alert('Member added successfully');setTimeout(function() { window.location.href = 'dashboard.php?page=team.php'; }, 1000);</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add member');setTimeout(function() { window.location.href = 'dashboard.php?page=team.php'; }, 1000);</script>";
    }
}

// Update member
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $img_path = $_POST['img_path'];
    mysqli_query($conn, "UPDATE team SET name='$name', role='$role', img_path='$img_path' WHERE id=$id");
    if ($conn) {
        echo "<script>alert('Member updated successfully');setTimeout(function() { window.location.href = 'dashboard.php?page=team.php'; }, 1000);</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update member');setTimeout(function() { window.location.href = 'dashboard.php?page=team.php'; }, 1000);</script>";
    }
    exit();
}

// Delete member
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM team WHERE id=$id");
    header("Location:  dashboard.php?page=team.php");
    exit();
}

// Fetch all members
$result = mysqli_query($conn, "SELECT * FROM team");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4;}
        h2 { text-align: center; }
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
    </style>
</head>
<body>
    <h2>Manage Team</h2>
    
    <form method="POST">
        <input type="hidden" name="id" id="member_id">
        <input type="text" name="name" id="name" placeholder="Enter Name" required>
        <input type="text" name="role" id="role" placeholder="Enter Role" required>
        <input type="text" name="img_path" id="img_path" placeholder="Enter Image Path" required>
        <button type="submit" name="add" id="add-btn" class="add-btn">Add Member</button>
        <button type="submit" name="update" id="update-btn" class="edit-btn" style="display:none;">Update Member</button>
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Img Path</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['img_path']; ?></td>
                <td>
                    <button id="edit-btn" class="edit-btn" onclick="editMember(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['role']; ?>', '<?php echo $row['img_path']; ?>')">Edit</button>
                    <a class="delete-btn" href="team.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editMember(id, name, role, img_path) {
            document.getElementById('member_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('role').value = role;
            document.getElementById('img_path').value = img_path;
            document.getElementById('add-btn').style.display = 'none';
            document.getElementById('update-btn').style.display = 'inline-block';
        }
    </script>
</body>
</html>
