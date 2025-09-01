<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(2,1fr);
            gap: 30px;
            margin-bottom: 30px;
        }
        .card {
            background-color: #fff;
            padding: 60px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            font-size: 25px;
            color: #333;
            margin-bottom: 10px;
        }
        .card h3 a{
            text-decoration: none;
            color: #333;
        }
        .card .count {
            font-size: 40px;
            font-weight: bold;
        }
        /* Colors for categories */
        .hotel-bookings { color: #007bff; }
        .villa-bookings { color: #28a745; }
        .resort-bookings { color: #ffc107; }
        .total-bookings { color: #17a2b8; }
        .total-users { color: #6c757d; }
        .user-queries { color: blue; }
        .total-cab{ color :rgb(29, 172, 117);}
    </style>
</head>
<body>
    <div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px; left: 0; top: 0;">
    <h2 class="text-center">Manager Panel</h2>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="dashboard.php?page=hotel.php" class="nav-link text-white">Analysis</a></li>
        <li class="nav-item"><a href="dashboard.php?page=hotelbooking.php" class="nav-link text-white">Bookings</a></li>
        <li class="nav-item"><a href="dashboard.php?page=cabbooking.php" class="nav-link text-white">Cab Bookings</a></li>
        <li class="nav-item"><a href="dashboard.php?page=reviews.php" class="nav-link text-white">Reviews</a></li>
        <li class="nav-item"><a href="dashboard.php?page=hotelrooms.php" class="nav-link text-white">Rooms</a></li>
        <li class="nav-item"><a href="dashboard.php?page=edithotel.php" class="nav-link text-white">Update</a></li>
        <li class="nav-item"><a href="dashboard.php?page=nearbyhotel.php" class="nav-link text-white">Near By Places</a></li>
        <li class="nav-item"><a href="dashboard.php?page=edit_profile.php" class="nav-link text-white">Edit Profile</a></li>
        <li class="nav-item mt-3"><a href="/bookmystay/manager/logout.php" class="nav-link text-danger">Logout</a></li>
    </ul>
</nav>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <h3>Welcome to the Manager Panel</h3>
            <p>Select an option from the sidebar to manage your hotel.</p>
        </div>
        
    </div>
    <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $allowedPages = ['dashboard.php', 'hotel.php', 'nearbyhotel.php', 'cabbooking.php','edithotel.php', 'reviews.php','edit_profile.php', 'hotelrooms.php', 'hotelbooking.php', 'resort.php', 'resortrooms.php', 'resortbooking.php', 'villa.php', 'villabooking.php'];
                if (in_array($page, $allowedPages)) {
                    include($page);
                } else {
                    echo "<p>Invalid page selected.</p>";
                }
            }
            ?>
            
</body>
</html>
