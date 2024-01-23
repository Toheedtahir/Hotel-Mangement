<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

// Retrieve and display booking details
$bookingDetails = get_booking_details($con);

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
</head>
<body>

    <a href="logout.php">Logout</a>
    <h1>Booking Details</h1>

    <br>
    Hello, <?php echo $user_data['user_name']; ?>

    <!-- Display Booking Details -->
    <?php if (!empty($bookingDetails)) : ?>
        <h2>All Booking Details:</h2>
        <?php foreach ($bookingDetails as $booking) : ?>
            <p>Name: <?= $booking["booking_name"] ?><br>
               Contact: <?= $booking["booking_contact"] ?><br>
               Date: <?= $booking["booking_date"] ?><br>
               Start Time: <?= $booking["start_time"] ?><br>
               End Time: <?= $booking["end_time"] ?></p>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No booking details found.</p>
    <?php endif; ?>

</body>
</html>
