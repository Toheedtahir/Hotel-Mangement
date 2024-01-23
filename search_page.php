<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

// Initialize variables
$searchResults = [];

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchQuery = $_POST['search_query'];

    // Using prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM homes WHERE address LIKE ? OR city LIKE ? OR name LIKE ?");
    $searchPattern = "%$searchQuery%";
    $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
    
    $stmt->close();
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Search</title>
</head>
<body>

    <a href="logout.php">Logout</a>
    <h1>This is the index page</h1>

    <br>
    Hello, <?php echo $user_data['user_name']; ?>

    <!-- HTML Form -->
    <form method="post" action="">
        <label for="search_query">Search by address, city, or name:</label>
        <input type="text" name="search_query" id="search_query" placeholder="Enter address, city, or name" required>
        <button type="submit">Search</button>
    </form>

    <!-- Display Search Results -->
    <?php if (!empty($searchResults)) : ?>
        <h2>Search Results:</h2>
        <?php foreach ($searchResults as $result) : ?>
            <p>Name: <?= $result["name"] ?><br>
               Address: <?= $result["address"] ?><br>
               City: <?= $result["city"] ?><br>
            
               <a href='booking.php?id=<?= $result["id"] ?>'>Book Now</a></p>
        <?php endforeach; ?>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <p>No results found.</p>
    <?php endif; ?>

</body>
</html>
