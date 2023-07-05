<?php
    session_start();

    require_once "database.php";
    $user_id = $_SESSION['user'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>Profile</h1>
    <p>Name: <?php echo $user['fullname']; ?></p>
    <p>Email: <?php echo $user['username']; ?></p>
    <p>Username: <?php echo $user['email']; ?></p>
    <p>Bio: <?php echo $user['password']; ?></p>
    <a href="edit-profile.php">Edit Profile</a>
</body>
</html>
