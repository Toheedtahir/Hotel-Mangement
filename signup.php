<?php
session_start();

include("connection.php");
include("functions.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // something was posted
    $user_name = mysqli_real_escape_string($con, $_POST['user_name']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // save to database using prepared statement
        $user_id = random_num(20);
        $query = "INSERT INTO users (id, user_name, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sss', $user_id, $user_name, $hashed_password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: login.php");
            die;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Please enter some valid information!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>

<style type="text/css">
    #text {
        height: 25px;
        border-radius: 5px;
        padding: 4px;
        border: solid thin #aaa;
        width: 100%;
    }

    #button {
        padding: 10px;
        width: 100px;
        color: white;
        background-color: lightblue;
        border: none;
    }

    #box {
        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
    }
</style>

<div id="box">
    <form method="post">
        <div style="font-size: 20px;margin: 10px;color: white;">Signup</div>

        <input id="text" type="text" name="user_name"><br><br>
        <input id="text" type="password" name="password"><br><br>

        <input id="button" type="submit" value="Signup"><br><br>

        <a href="login.php">Click to Login</a><br><br>
    </form>
</div>
</body>
</html>
