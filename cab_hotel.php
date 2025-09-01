
    <?php
    include 'includes/database.php';
    include 'includes/header.php';
    $hotel_booking_id = $_GET['hotel_booking_id'];
    if (!$hotel_booking_id) {
        echo "<script> alert('Please provide a valid booking ID.'); setTimeout(function() { window.location.href = 'mybooking.php'; }, 1000); </script>"; 
        exit();
    }
    $result = $conn->query("SELECT * FROM hotel_booking WHERE booking_id='$hotel_booking_id'");
    $booking = $result->fetch_assoc();
    ?>
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('cabBookingModal').style.display='none';">&times;</span>
        <h2>Cab Booking</h2>
        <form action="book_cab_hotel.php" method="POST">
            <input type="text" id="booking_id" name="booking_id" value="<?php echo $booking['booking_id']; ?>" hidden>
            <label for="username">Name:</label>
            <input type="text" id="username" name="username" value="<?php echo $booking['username']; ?>" readonly><br><br>

            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" name="pickup" required>
            <button type="button" class="btn" onclick="getCurrentLocation()">Use Current Location</button><br><br>
            
            <label for="dropoff">Drop-off Location:</label>
            <input type="text" id="dropoff" name="dropoff" value="<?php echo $booking['hname']; ?>" readonly><br><br>
            
            <label for="date">Date:</label>
            <input type="date" id="date" name="date"  value="<?php echo $booking['checkin']; ?>" required><br><br>
            <script>
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0'); 
                const dd = String(today.getDate()).padStart(2, '0');
                const minDate = `${yyyy}-${mm}-${dd}`;
                document.getElementById('date').setAttribute('min', minDate);
            </script>
            
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required><br><br>
            
            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact" value="<?php echo $booking['userphno']; ?>" required><br><br>
            
            <button type="submit" class="btn">Book Cab</button>
            <a href="mybooking.php" class="btn">Back</a>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
<!-- CSS for Modal -->
<style>
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgb(255, 255, 255); 
    }
    .modal-content {
        background-color: #fefefe; 
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%; 
        border-radius: 10px;
        box-shadow: 0 4px 8px rgb(255, 255, 255);
    }
    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close-btn:hover,
    .close-btn:focus {
        color: #000;
        text-decoration: none;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn:hover {
        background-color: #45a049;
    }
</style>

<script>
    /*function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Use OpenCage API to get the address from coordinates
                const apiKey = '750867c0779f417aaa51c92f2340d4c4';
                const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${apiKey}`;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results && data.results.length > 0) {
                            const address = data.results[0].formatted;
                            document.getElementById('pickup').value = address;
                        } else {
                            alert('Unable to retrieve address.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while retrieving location.');
                    });
            }, function(error) {
                alert('Error retrieving location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }*/
    function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Use OpenCage API to get the address from coordinates
            const apiKey = '750867c0779f417aaa51c92f2340d4c4'; // Your API key
            const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${apiKey}&countrycode=in`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        const address = data.results[0].formatted;
                        document.getElementById('pickup').value = address;
                    } else {
                        alert('Unable to retrieve address. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while retrieving location. Please try again.');
                });
        }, function (error) {
            let errorMessage = '';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = 'User denied the request for Geolocation.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = 'Location information is unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage = 'The request to get user location timed out.';
                    break;
                case error.UNKNOWN_ERROR:
                default:
                    errorMessage = 'An unknown error occurred.';
                    break;
            }
            alert('Error retrieving location: ' + errorMessage);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

</script>
