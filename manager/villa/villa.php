<?php
session_start();

// Database connection
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "bookmyroom"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$manager_property_id = $_SESSION['property_id'];

// Fetch villa name for the manager's property
$qry = "SELECT name FROM villas1 WHERE id = $manager_property_id";
$result = $conn->query($qry);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $res = $row['name']; // Fetch the villa name
} else {
    die("Villa not found");
}

// Query 1: Day-wise Booking Count
$sql1 = "SELECT vname, DATE(checkin) AS booking_day, COUNT(*) AS total_bookings 
         FROM villa_booking
         WHERE vname = '$res'
         GROUP BY vname, booking_day 
         ORDER BY booking_day ASC";
$result1 = $conn->query($sql1);

// Query 2: Month-wise Booking Ratio
$sql2 = "SELECT vname, DATE_FORMAT(checkin, '%Y-%m') AS booking_month, COUNT(*) AS total_bookings 
         FROM villa_booking
         WHERE vname = '$res'
         GROUP BY vname, booking_month 
         ORDER BY booking_month ASC";
$result2 = $conn->query($sql2);

// Query 3: Month with Highest Booking Ratio
$sql3 = "SELECT booking_month, (total_bookings / total_overall) * 100 AS booking_ratio FROM (
            SELECT DATE_FORMAT(checkin, '%Y-%m') AS booking_month, COUNT(*) AS total_bookings, 
                   (SELECT COUNT(*) FROM villa_booking WHERE vname = '$res') AS total_overall
            FROM villa_booking 
            WHERE vname = '$res'
            GROUP BY booking_month
         ) AS monthly_ratios 
         ORDER BY booking_ratio DESC LIMIT 1";
$result3 = $conn->query($sql3);

// Query 4: Month with Lowest Booking Ratio
$sql4 = "SELECT booking_month, (total_bookings / total_overall) * 100 AS booking_ratio FROM (
            SELECT DATE_FORMAT(checkin, '%Y-%m') AS booking_month, COUNT(*) AS total_bookings, 
                   (SELECT COUNT(*) FROM villa_booking WHERE vname = '$res') AS total_overall
            FROM villa_booking 
            WHERE vname = '$res'
            GROUP BY booking_month
         ) AS monthly_ratios 
         ORDER BY booking_ratio ASC LIMIT 1";
$result4 = $conn->query($sql4);

// Fetch data for charts
$dayWiseData = [];
while ($row = $result1->fetch_assoc()) {
    $dayWiseData[] = $row;
}

$monthWiseData = [];
while ($row = $result2->fetch_assoc()) {
    $monthWiseData[] = $row;
}

$highestBooking = $result3->fetch_assoc();
$lowestBooking = $result4->fetch_assoc();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Panel - villa Booking Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas { max-width: 800px; margin: 20px auto; display: block; }
        .head { margin-left: 300px; margin-top: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>

<h2 class="head">Day-wise Booking Report</h2>
<canvas id="dayWiseChart"></canvas>

<h2 class="head">Month-wise Booking Report</h2>
<canvas id="monthWiseChart"></canvas>

<h2 class="head">Booking Ratio Analysis</h2>
<canvas id="bookingRatioChart"></canvas>

<h2 class="head">Highest Booking Ratio</h2>
<canvas id="highestBookingChart"></canvas>

<h2 class="head">Lowest Booking Ratio</h2>
<canvas id="lowestBookingChart"></canvas>

<script>
    // Day-wise Booking Chart
    const dayWiseData = <?php echo json_encode($dayWiseData); ?>;
    const dayLabels = dayWiseData.map(item => item.booking_day);
    const dayBookings = dayWiseData.map(item => item.total_bookings);

    new Chart(document.getElementById("dayWiseChart"), {
        type: 'bar',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Total Bookings',
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                data: dayBookings
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Month-wise Booking Chart
    const monthWiseData = <?php echo json_encode($monthWiseData); ?>;
    const monthLabels = monthWiseData.map(item => item.booking_month);
    const monthBookings = monthWiseData.map(item => item.total_bookings);

    new Chart(document.getElementById("monthWiseChart"), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Total Bookings',
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                data: monthBookings,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Booking Ratio Chart
    const highestBooking = <?php echo json_encode($highestBooking); ?>;
    const lowestBooking = <?php echo json_encode($lowestBooking); ?>;

    new Chart(document.getElementById("bookingRatioChart"), {
        type: 'doughnut',
        data: {
            labels: [highestBooking.booking_month, lowestBooking.booking_month],
            datasets: [{
                label: 'Booking Ratio (%)',
                backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                data: [highestBooking.booking_ratio, lowestBooking.booking_ratio]
            }]
        },
        options: { responsive: true }
    });

    // Highest Booking Ratio Chart
    new Chart(document.getElementById("highestBookingChart"), {
        type: 'bar',
        data: {
            labels: [highestBooking.booking_month],
            datasets: [{
                label: 'Highest Booking Ratio (%)',
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: [highestBooking.booking_ratio]
            }]
        },
        options: { responsive: true }
    });

    // Lowest Booking Ratio Chart
    new Chart(document.getElementById("lowestBookingChart"), {
        type: 'bar',
        data: {
            labels: [lowestBooking.booking_month],
            datasets: [{
                label: 'Lowest Booking Ratio (%)',
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: [lowestBooking.booking_ratio]
            }]
        },
        options: { responsive: true }
    });
</script>

</body>
</html>
