<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

// Initialize variables
$houseDetails = [];
$bookingSuccess = false;

// Check if the house ID is provided in the URL
if (isset($_GET['id'])) {
    $houseId = $_GET['id'];

    // Using prepared statement to prevent SQL injection
    $stmtHouse = $con->prepare("SELECT * FROM homes WHERE id = ?");
    $stmtHouse->bind_param("i", $houseId);
    $stmtHouse->execute();
    $resultHouse = $stmtHouse->get_result();

    if ($resultHouse->num_rows > 0) {
        $houseDetails = $resultHouse->fetch_assoc();
    }

    $stmtHouse->close();
}

// Handle the booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $bookingName = $_POST['booking_name'];
    $bookingContact = $_POST['booking_contact'];
    $bookingDate = $_POST['booking_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Using prepared statement to prevent SQL injection
    $stmtBooking = $con->prepare("INSERT INTO bookings (booking_name, booking_contact, booking_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    $stmtBooking->bind_param("sssss", $bookingName, $bookingContact, $bookingDate, $startTime, $endTime);
    
    // Execute the statement and check for success
    if ($stmtBooking->execute()) {
        $bookingSuccess = true;

        // Redirect to index.php after successful booking
        header("Location: index.php");
        exit(); // Ensure script execution stops after the redirect
    } else {
        // If there is an error, display the error message
        echo "Error: " . $stmtBooking->error;
    }

    $stmtBooking->close();
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Booking</title>
</head>
<body>

    <a href="logout.php">Logout</a>
    <h1>This is the booking page</h1>

    <br>
    Hello, <?php echo $user_data['user_name']; ?>

    <!-- Display House Details -->
    <?php if (!empty($houseDetails)) : ?>
        <h2>House Details:</h2>
        <p>Name: <?= $houseDetails["name"] ?><br>
           Address: <?= $houseDetails["address"] ?><br>
           City: <?= $houseDetails["city"] ?><br>
         

        <!-- Booking Form -->
        <h2>Booking Form:</h2>
        <?php if ($bookingSuccess) : ?>
            <p>Booking successful!</p>
        <?php else : ?>
            <form method="post" action="">
                <!-- Your booking form fields go here -->
                <label for="booking_name">Name:</label>
                <input type="text" name="booking_name" id="booking_name" required>

                <label for="booking_contact">Contact:</label>
                <input type="text" name="booking_contact" id="booking_contact" required>

                <label for="booking_date">Date:</label>
                <input type="date" name="booking_date" id="booking_date" required>

                <label for="booking_time">Start Time:</label>
                <input type="time" name="start_time" id="start_time" required>

                <label for="booking_time">End Time:</label>
                <input type="time" name="end_time" id="end_time" required>

                <button type="submit">Book Now</button>
            </form>
        <?php endif; ?>
    <?php else : ?>
        <p>No details found for the selected house.</p>
    <?php endif; ?>

</body>
</html>
