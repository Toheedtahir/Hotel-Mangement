<?php

function check_login($con)
{

	if(isset($_SESSION['id']))
	{

		$id = $_SESSION['user_id'];
		$query = "select * from users where id = '$id' limit 1";

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: login.php");
	die;

}

function random_num($length)
{

	$text = "";
	if($length < 5)
	{
		$length = 5;
	}

	$len = rand(4,$length);

	for ($i=0; $i < $len; $i++) { 
		

		$text .= rand(0,9);
	}

	return $text;
}





function get_booking_details($con) {
    $bookingDetails = [];

    // Using prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM bookings");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookingDetails[] = $row;
        }
    }

    $stmt->close();

    return $bookingDetails;
}

// Other functions...

?>

