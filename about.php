<?php
  error_reporting(0);
  session_start();
  $conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database Error");
  $result = mysqli_query($conn, "SELECT * FROM team");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - Book My Stay</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    h1{
        margin-top: 50px;
        margin-left: 50px;
    }
        /* About Section */
        .section-about {
      max-width: 900px;
      margin: 50px auto;
      background-color: white;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .section-about h1 {
      font-size: 36px;
      color: #333;
      margin-bottom: 20px;
    }

    .section-about p {
      font-size: 18px;
      color: #555;
      line-height: 1.8;
      margin-bottom: 20px;
    }

    .section-about .team {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 20px;
    }

    .team-member {
      flex: 1 1 200px;
      background-color: #f8f8f8;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .team-member img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
    }

    .team-member h3 {
      font-size: 20px;
      color: #333;
      margin-bottom: 10px;
    }

    .team-member p {
      font-size: 16px;
      color: #777;
      margin-bottom: -5px;
    }
    .team-member {
        transition: transform 0.3s ease;
    }
    .team-member:hover {
    transform: translateY(-10px);
    }


    /* Responsive Design */
    @media (max-width: 768px) {
      .team-member {
        flex: 1 1 100%;
      }
    }
    </style>
</head>
<body>
  <?php include './includes/header.php'; ?>
  
  <section class="section-about">
    <h1>About Us</h1>
    <p>Welcome to **Book My Stay**, your trusted platform for finding and booking the best hotels, resorts, and villas. Our mission is to make your travel planning easy, convenient, and enjoyable. With a wide range of properties to choose from, we ensure that your next trip will be memorable.</p>
    
    <h2>Our Team</h2>
    <div class="team">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="team-member">
          <img src="<?php echo htmlspecialchars($row['img_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p>[<?php echo htmlspecialchars($row['role']); ?>]</p>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <?php include './includes/footer.php'; ?>
</body>
</html>
