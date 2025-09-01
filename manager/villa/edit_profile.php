<?php
session_start();
include "../db_connection.php"; // Adjust path as needed

// Ensure manager is logged in
if (!isset($_SESSION['manager_id']) || !isset($_SESSION['property_type'])) {
    echo "<script>alert('Unauthorized access.');</script>";
    die("Unauthorized access.");
}

$property_type = $_SESSION['property_type'];
$manager_id = $_SESSION['manager_id'];

switch ($property_type) {
    case 'hotel': $table = 'hotel_managers'; break;
    case 'resort': $table = 'resort_managers'; break;
    case 'villa': $table = 'villa_managers'; break;
    default: die("Invalid property type.");
}

// Ensure the database connection is established
if (!$conn) {
    die("Database connection error.");
}

// Fetch manager details
$sql = "SELECT name, email FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();
$manager = $result->fetch_assoc();

if (!$manager) {
    die("Manager not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        
        .card {
            max-width: 450px;
            width: 100%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-left: 850px;
            margin-top: 150px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #2ebf91;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #25a478;
        }
    </style>
</head>
<body>

<div class="card text-center bg-white">
    <h2 class="mb-3 text-primary">Edit Profile</h2>
    <form method="post" action="update_manager.php">
        <input type="hidden" name="manager_id" value="<?php echo $manager_id; ?>">

        <div class="mb-3">
            <label class="form-label fw-bold">Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($manager['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($manager['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">New Password (Leave blank to keep current):</label>
            <input type="password" name="new_password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Update</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
