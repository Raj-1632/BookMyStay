<?php include 'includes/header.php'; 
session_start();
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = urlencode($_POST['city']);
    $property = $_POST['property'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

   
    if ($property === 'hotel') {
        header("Location: hotels.php?location=$city&checkin=$checkin&checkout=$checkout");
    } elseif ($property === 'villa') {
        header("Location: villas.php?location=$city&checkin=$checkin&checkout=$checkout");
    } elseif ($property === 'resort') {
        header("Location: resorts.php?location=$city&checkin=$checkin&checkout=$checkout");
    } else {
        echo "Invalid property type selected.";
    }
    exit();
}


?>


  <!-- Hero Section --> 
   
  <section class="hero">
    <h1></h1>
    <div class="hero-carousel"></div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("fetch_carousel.php")
            .then(response => response.json())
            .then(images => {
                const carousel = document.querySelector(".hero-carousel");

                if (images.length === 0) {
                    carousel.innerHTML = "<p>No images available.</p>";
                    return;
                }

                images.forEach((image, index) => {
                    let div = document.createElement("div");
                    div.classList.add("carousel-image");
                    if (index === 0) div.classList.add("active");
                    div.style.backgroundImage = `url('${image}')`;
                    carousel.appendChild(div);
                });

                startCarousel();
            })
            .catch(error => console.error("Error loading images:", error));

        function startCarousel() {
            let currentIndex = 0;
            const slides = document.querySelectorAll(".carousel-image");

            setInterval(() => {
                slides[currentIndex].classList.remove("active");
                currentIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].classList.add("active");
            }, 3000); // Change slide every 3 seconds
        }
    });
    </script>
  <!-- Search Bar Section -->
   
  <form method="POST">
  <div class="search-bar-container">
  
  <div class="form-group">
    <label for="city">City</label>
    <input type="text" placeholder="Search City" name="city" id="city">
  </div>

  <div class="form-group">
    <label for="city">Property</label>
    <select id="property" name="property" required>
        <option value="" disabled selected>~Select~</option>
        <option value="hotel">Hotel</option>
        <option value="villa">Villa</option>
        <option value="resort">Resort</option>
    </select>
  </div>
  <div class="form-group">
    <label for="check_in_date">Check-in</label>
    <input type="date" id="check_in_date" name="checkin" min="<?= date('Y-m-d'); ?>">
  </div>
  <div class="form-group">
    <label for="check_out_date">Check-out</label>
    <input type="date" id="check_out_date" name="checkout" min="<?= date('Y-m-d'); ?>">
  </div>
    <script>
        document.getElementById('check_in_date').addEventListener('change', function () {
            // Ensure check-out date is not earlier than check-in date
            const checkInDate = this.value;
            document.getElementById('check_out_date').setAttribute('min', checkInDate);
        });
    </script>
  <div class="form-group">
  <label for="search">Search</label>
  <button type="submit">Search</button></div>
</div>
</form>



    <script src="assets/js/script1.js"></script>
  </section>
  
  <?php
include 'includes/database.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fetch top 3 most booked hotels
$hotelQuery = "SELECT h.id, h.name, h.image_url, h.location 
               FROM hotels1 h
               JOIN (
                   SELECT hname, COUNT(*) AS booking_count 
                   FROM hotel_booking 
                   GROUP BY hname 
                   ORDER BY booking_count DESC 
                   LIMIT 3
               ) hb ON h.name = hb.hname
               ORDER BY booking_count DESC
               LIMIT 3";

$hotels = $conn->query($hotelQuery) or die("Hotel Query Failed: " . $conn->error);

// Fetch top 3 most booked resorts
$resortQuery = "SELECT r.id, r.name, r.image_url, r.location 
                FROM resorts1 r
                JOIN (
                    SELECT rname, COUNT(*) AS booking_count 
                    FROM resort_booking 
                    GROUP BY rname
                    ORDER BY booking_count DESC 
                    LIMIT 3
                ) rb ON r.name = rb.rname
                ORDER BY booking_count DESC
               LIMIT 3";

$resorts = $conn->query($resortQuery) or die("Resort Query Failed: " . $conn->error);

// Fetch top 3 most booked villas
$villaQuery = "SELECT v.id, v.name, v.image_url, v.location 
               FROM villas1 v
               JOIN (
                   SELECT vname, COUNT(*) AS booking_count 
                   FROM villa_booking 
                   GROUP BY vname
                   ORDER BY booking_count DESC 
                   LIMIT 3
               ) vb ON v.name = vb.vname
               ORDER BY booking_count DESC
               LIMIT 3";

$villas = $conn->query($villaQuery) or die("Villa Query Failed: " . $conn->error);
?>
<div class="container">
    <!-- Hotels Section -->
    <h1 class="hotel-title">Popular Hotels</h1>
    <div class="hotels-container">
        <?php 
        if ($hotels->num_rows > 0) {
            while ($hotel = $hotels->fetch_assoc()): ?>
                <div class="hotel-card">
                    <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="Hotel Image">
                    <div class="hotel-info">
                        <h2><?php echo htmlspecialchars($hotel['name']); ?></h2>
                        <p><?php echo htmlspecialchars($hotel['location']); ?></p>
                        <a href="hoteldetail.php?id=<?php echo htmlspecialchars($hotel['id']); ?>">
                            <button class="hotel-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No hotels found</p>";
        } ?>
    </div>

    <!-- Resorts Section -->
    <h1 class="resort-title">Popular Resorts</h1>
    <div class="resorts-container">
        <?php 
        if ($resorts->num_rows > 0) {
            while ($resort = $resorts->fetch_assoc()): ?>
                <div class="resort-card">
                    <img src="<?php echo htmlspecialchars($resort['image_url']); ?>" alt="Resort Image">
                    <div class="resort-info">
                        <h2><?php echo htmlspecialchars($resort['name']); ?></h2>
                        <p><?php echo htmlspecialchars($resort['location']); ?></p>
                        <a href="resortdetail.php?id=<?php echo htmlspecialchars($resort['id']); ?>">
                            <button class="resort-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No resorts found</p>";
        } ?>
    </div>

    <!-- Villas Section -->
    <h1 class="villa-title">Popular Villas</h1>
    <div class="villas-container">
        <?php 
        if ($villas->num_rows > 0) {
            while ($villa = $villas->fetch_assoc()): ?>
                <div class="villa-card">
                    <img src="<?php echo htmlspecialchars($villa['image_url']); ?>" alt="Villa Image">
                    <div class="villa-info">
                        <h2><?php echo htmlspecialchars($villa['name']); ?></h2>
                        <p><?php echo htmlspecialchars($villa['location']); ?></p>
                        <a href="villadetail.php?id=<?php echo htmlspecialchars($villa['id']); ?>">
                            <button class="villa-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No villas found</p>";
        } ?>
    </div>
</div>
     
  <!-- facilitiess -->

    <h1 class="fac-head">FACILITY</h1>
    <div class="facility">
    <ul>
        <li>
          <div class="content">
              <h2> RESTAURANT </h2>
              <p> Fun. Fast. Testy. Delicious Food. </p>
          </div>
        </li>
        
        <li>
          <div class="content">
            <h2> POOL </h2>
            <p> Swim, Chil, Thril.</p>
        </li>
        
        <li>
          <div class="content">
            <h2> GYM </h2>
            <p style="color:#ecdfcc;"> Fight To Be Fit </p>
        </div>
        </li>
        
        <li> 
          <div class="content">
            <h2> BEAUTY SALON </h2>
            <p> It's your Time To Shine. </p>
        </div>
        </li>
        
        <li>
          <div class="content">
            <h2> CLUB HOUSE </h2>
            <p> Talent Wins Games </p>
          </div>
        </li>
    </ul>
</div>
    <!--TESTIMONIALS-->
    <div class="testimonials-section">
  <h2>TESTIMONIALS</h2>
  <div class="testimonial-cards">
    <div class="testimonial-card left">
      <div class="user-info">
        <img src="assets/images/user.png" alt="User" class="user-image">
        <span class="user-name">Devansh</span>
      </div>
      <p class="testimonial-text">
          "Effortlessly book your perfect stay with a sleek, user-friendly design and tailored options!".
      </p>
      <div class="rating">
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
      </div>
    </div>

    <div class="testimonial-card center">
      <div class="user-info">
        <img src="assets/images/user.png" alt="User" class="user-image">
        <span class="user-name">Advika</span>
      </div>
      <p class="testimonial-text">
      "Find your dream getaway with ease – fast, intuitive, and reliable!"
      </p>
      <div class="rating">
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
      </div>
    </div>

    <div class="testimonial-card right">
      <div class="user-info">
        <img src="assets/images/user.png" alt="User" class="user-image">
        <span class="user-name">Ishaan</span>
      </div>
      <p class="testimonial-text">
      "Great site for finding and booking stays!"
      </p>
      <div class="rating">
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
        <span>⭐</span>
      </div>
    </div>
  </div>
</div>


    <!-- Room Accommodation
<h1 class="shadow">Rooms Accommodation</h1>
  <div class="room-title">
  
</div>

<div class="room-card">
    <div class="card">
       <img src = "assets/images/club.jpg" width="100%">
        <div class="info">
          <h1> CLUB ROOM</h1>
          <p>Single Bed</p>
          <a href="web/index.html" class="btn">360&deg; view </a>
        </div>
    </div>

    <div class="card">
        <img src = "assets/images/classic.jpg">
        <div class="info">
            <h1> CLASSIC ROOM</h1>
            <p>TWO BED ROOM<br> King Size</p>
            <a href="web/classic.html" class="btn">360&deg; view</a>
        </div>
    </div>
  
    <div class="card">
        <img src = "assets/images/family.jpg">
        <div class="info">
          <h1>FAMILY SUITE ROOM</h1>
          <p>family Room</p>
          <a href="web/last.html" class="btn">360&deg; view</a>
        </div>
    </div>
</div> -->

<!-- Services -->
  <h1 class="service-title">Services</h1>
  <div class="services">
   
  <div class="img-container">
    
    <div class="wifi">
      <img src="assets/images/wifi.png" class="img1">
      <p> FREE WIFI </p>
    </div>

    <div class="wifi">
      <img src="assets/images/room-services.png">
      <p> ROOM SERVICE </p>
    </div>

    <div class="wifi">
      <img src="assets/images/bar-counter.png">
      <p> COOKTAIL BAR </p>
    </div>

    <div class="wifi">
      <img src="assets/images/key.png">
      <p>PRIVATE ROOM</p>
    </div>

    <div class="wifi">
      <img src="assets/images/car.png">
      <p>PARKING</p>
    </div> 
    <div class="wifi">
      <img src="assets/images/housekeeper.png">
      <p>HOUSE-<br>KEEPER </p>
    </div>

    <div class="wifi">
      <img src="assets/images/drying.png">
      <p>LAUNDRY <br> AND <br> DRY CLEANING</p>
    </div>

    <div class="wifi">
      <img src="assets/images/taxi.png">
      <p>TRANSPORT</p>
    </div>
  
  </div>
</div>

<!-- FAQ -->
<div class="faq-section">
    <h2 class="faq-header">Frequently Asked Questions</h2>
    <div class="faq-item">
      <h3 class="faq-question">How do I book a Hotel/Resorts/Villas on Book My Stay?</h3>
      <p class="faq-answer">You can search for Hotels,Resorts & Villas, select your preferred stay, and complete the booking process through our secure payment gateway.</p>
    </div>
    <div class="faq-item">
      <h3 class="faq-question">What is the cancellation policy?</h3>
      <p class="faq-answer">You can cancel your booking up to 48 hours before your arrival. You will be refunded 90% if you cancel your booking before 48 hours of check-in date.</p>
    </div>
    <div class="faq-item">
      <h3 class="faq-question">Are there any hidden charges?</h3>
      <p class="faq-answer">No, all charges are transparently displayed during the booking process. Taxes and fees are included in the final price.</p>
    </div>
    <div class="faq-item">
      <h3 class="faq-question">Can I modify my booking after confirmation?</h3>
      <p class="faq-answer">No, Only you can cancel the booking.</p>
    </div>
    <div class="faq-item">
      <h3 class="faq-question">How do I contact customer support?</h3>
      <p class="faq-answer">You can reach us via email at support@bookmystay.com or call us at +91 7041332005 / +91 8238388031.<a href="contact.php">Contact Us</a></p>
    </div>
  </div>

  <script>
    // JavaScript for Accordion Effect
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
      question.addEventListener('click', () => {
        // Toggle the active class
        question.classList.toggle('active');
        // Show/Hide the answer
        const answer = question.nextElementSibling;
        if (answer.style.display === 'block') {
          answer.style.display = 'none';
        } else {
          answer.style.display = 'block';
        }
      });
    });
  </script>
<!-- Reach Us -->
<section class="reach-us-section">
    <h2>REACH US</h2>
    <div class="reach-us-container">
      <!-- Google Maps Embed -->
      <div class="map">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1919.5529235861354!2d72.15220328584932!3d21.755889011949343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395f5a6e052be6b7%3A0x6c83a1f2434e62ba!2sSwaminarayan%20Gurukul!5e0!3m2!1sen!2sin!4v1733136834835!5m2!1sen!2sin" 
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>

      <div class="info-container">
        <!-- Contact Info -->
        <div class="info-card">
          <h3>Call us</h3>
          <p>📞 +918238388031</p>
          <p>📞 +917041332005</p>
        </div>
        
        <!-- Social Media Links -->
        <div class="info-card" id="social-media">
          <h3 class="abc">Follow us</h3>
          
          <p align="center">
            <a href="https://www.facebook.com/share/17qoWYErrG/" target="_blank" style="text-decoration: none; color: inherit;">
               <img src="assets/images/facebook.png" alt="Facebook" style="width: 20px; height: 20px;">Facebook
            </a>
           </p>
           <p align="center">
              <a href="https://www.instagram.com/ssccs_bvn_official/" target="_blank" style="text-decoration: none; color: inherit;">
                 <img src="assets/images/instagram.png" alt="Instagram" style="width: 20px; height: 20px;">Instagram
              </a>
           </p>
        </div>
      </div>
    </div>
  </section>
    
      <!-- Footer Section -->
      <?php include 'includes/footer.php'; ?>
