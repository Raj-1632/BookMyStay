<?php
session_start();
$_SESSION['last_page'] = $_SERVER['REQUEST_URI']; 
error_reporting(0);
require 'includes/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotels - Book My Stay</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">
</head>
<body>
  <?php include './includes/header.php'; ?>

    <?php
    if (isset($_GET['id'])) {
        $hotelId = $_GET['id'];
        // Connect to the database and fetch details
        $query = "SELECT * FROM rooms2 WHERE resort_id = $hotelId";
        $result = mysqli_query($conn, $query);
        $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if ($rooms) {
            ?>
            <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Resort Details</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                        }
                        .room-types-section {
                            padding: 20px;
                            background-color: #f9f9f9;
                            border-radius: 8px;
                            margin-top: 20px;
                            
                            align-items : center;
                            box-shadow : 0px 10px 12px rgba(0, 0, 0.1, 0.1);
                        }
                        .room-types-section h3 {
                            text-align: center;
                            margin-bottom: 15px;
                            font-size: 1.5rem;
                            margin-left : 50px;
                        }
                        .room-types-section ul {
                            list-style-type: none;
                            padding: 0;
                            display: flex;
                            flex-wrap: wrap;
                            gap: 50px;
                            margin-top : 70px;
                            justify-content: center;
                            align-items: center;
                        }
                        .room-types-section li {
                            width: 300px;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                            overflow: hidden;
                            background-color: #fff;
                        }
                        .room-types-section img {
                            width: 100%;
                            height: 200px;
                            object-fit: cover;
                        }
                        .room-info {
                            padding: 15px;
                            align-items: center;
                        }
                        .room-info strong {
                            display: block;
                            margin-bottom: 5px;
                        }
                        .book-now-btn {
                            justify-content : center;
                            align-items: center;
                            background-color: #007bff;
                            color: white;
                            padding: 10px 20px;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;
                            font-size: 16px;
                            transition: background-color 0.3s;
                            margin : 15px;
                            box-shadow : 0px 10px 12px rgba(0, 0, 0.1, 0.1);
                        }
                        .book-now-btn:hover {
                            background-color: #0056b3;
                        }
                        a{
                            text-decoration : none;
                        }
                        @media (max-width: 768px) {
                            .room-types-section h3 {
                                font-size: 1.6rem; /* Adjust heading size */
                                display : flex;
                                margin-bottom : 250px;
                            }

                            .room-types-section ul {
                                margin-top : 100px;
                                margin-left : -80px;
                                gap: 15px; /* Reduce gap between items */
                            }

                            .room-info {
                                padding: 10px; /* Adjust padding for smaller screens */
                            }

                            .book-now-btn {
                                font-size: 14px; /* Smaller button text */
                                padding: 8px 16px; /* Smaller button size */
                            }
                        }

                        @media (max-width: 480px) {
                            .room-types-section {
                                padding: 15px; /* Reduce padding for very small screens */
                            }

                            .room-types-section h3 {
                                font-size: 1rem; /* Further reduce heading size */
                                margin-bottom: 200px;
                            }

                            .room-types-section ul {
                                gap: 10px; /* Compact gap for narrow screens */
                            }

                            .room-info {
                                font-size: 0.9rem; /* Smaller text size */
                            }

                            .book-now-btn {
                                font-size: 12px; /* Compact button size */
                                padding: 6px 12px;
                            }
                        }
                    </style>
                </head>
                <body>

                   

                    <div class="room-types-section">

                        <h3>Available Rooms</h3>
                        <?php if (!empty($rooms)): ?>
                            <ul>
                                <?php foreach ($rooms as $room): ?>
                                    <li>
                                        <img src="<?php echo htmlspecialchars($room['image_url']); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>">
                                        <div class="room-info">
                                            <strong><?php echo htmlspecialchars($room['room_type']); ?></strong>
                                            ₹<?php echo number_format($room['price_per_night'], 2); ?> per night<br>
                                            Capacity: <?php echo htmlspecialchars($room['capacity']); ?> guests<br>
                                            <?php echo htmlspecialchars($room['description']); ?>
                                        </div>
                                        <div><?php
                                        if (isset($_SESSION['username'])) {
                                            // If logged in, allow booking
                                            echo '<a href="book1.php?room_id=' . $room['room_id'] . '"><button class="book-now-btn">Book Now</button></a>';
                                            echo '<button class="book-now-btn"><a class="view-360" data-url="web/web/index.php?360_image_url=' . urlencode($room['360_image_url']) . '" >View 360</a></button>';
                                        } else {
                                            // If not logged in, redirect to login
                                            echo '<a href="login.php"><button class="book-now-btn">Log in to Book</button></a>';
                                        }?>
                                        </div>
                                        
                                        <script>
                                            // Add event delegation to handle dynamically added elements
                                            document.addEventListener('click', function (event) {
                                                if (event.target.classList.contains('view-360')) {
                                                    event.preventDefault(); // Prevent default behavior (if necessary)
                                                    const url = event.target.dataset.url; // Get the URL from the data attribute
                                                    const popupWidth = 800;
                                                    const popupHeight = 600;

                                                    // Calculate the center position for the popup
                                                    const left = (window.screen.width / 2) - (popupWidth / 2);
                                                    const top = (window.screen.height / 2) - (popupHeight / 2);

                                                    // Open the popup window
                                                    window.open(
                                                        url,
                                                        '360ViewPopup',
                                                        `width=${popupWidth},height=${popupHeight},left=${left},top=${top},resizable=yes,scrollbars=yes`
                                                    );
                                                }
                                            });

                                        </script>

                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No room types available for this hotel.</p>
                        <?php endif; ?>

                    </div>

                </body>
                </html>
                <?php
                } else {
                    echo "<p>Room details not found.</p>";
                }
            } else {
                echo "<p>Invalid Room ID.</p>";
            }
            mysqli_close($conn);
            ?>
  <?php include './includes/footer.php'; ?>
</body>
</html>