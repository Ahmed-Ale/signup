<?php
    session_start();
    if(!isset($_SESSION["user"])) {
        header("Location: sign-in.php");
    }

    $conn = mysqli_connect("localhost", "root", "", "signup_signin");
    $username = $_SESSION["user"];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $username = $user["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $username; ?></h1>
        <h3>You have successfully signed in.</h3>
        <h3><a href="profile.php">View your profile</a></h3>
        <h3><a href="signout.php">Sign Out</a></h3>
    </div>
</body>
</html>
