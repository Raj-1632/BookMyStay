<?php
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
