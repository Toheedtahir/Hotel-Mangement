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
        // read from database
        $query = "SELECT id, user_name, password FROM users WHERE user_name = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $user_name);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if (password_verify($password, $user_data['password'])) {
                    $_SESSION['user_id'] = $user_data['id'];
                    header("Location: index.php");
                    die;
                }
            }

            mysqli_stmt_close($stmt);
        }

        echo "Wrong username or password!";
    } else {
        echo "Wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        <div style="font-size: 20px;margin: 10px;color: white;">Login</div>

        <input id="text" type="text" name="user_name"><br><br>
        <input id="text" type="password" name="password"><br><br>

        <input id="button" type="submit" value="Login"><br><br>

        <a href="signup.php">Click to Signup</a><br><br>
    </form>
</div>
</body>
</html>
