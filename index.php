<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html>
<head>
	<title>My website</title>
</head>
<body>

	<a href="logout.php">Logout</a>
	<h1>This is the index page</h1>

	<br>
	Hello, <?php echo $user_data['user_name']; ?>
 <!-- Add a button to view booking details -->
    <a href="booking_details.php"><button>View Booking Details</button></a>
	
</body>
</html>


    <!-- HTML Form -->
    <form method="post" action="search_page.php">
        <label for="search_query">Search by address or city:</label>
        <input type="text" name="search_query" id="search_query" placeholder="Enter address or city" required>
        <button type="submit">Search</button>
    </form>

</body>
</html>
