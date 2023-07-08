<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: sign-in.php");
    exit();
} else {
    // Connect to the database.
    $conn = mysqli_connect("localhost", "root", "", "signup_signin");
    // Check connection.
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // Retrieve user data from the database.
    $username = $_SESSION["user"];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if (isset($_POST["submit"])) {
        if (password_verify($_POST["password"], $user["password"])) {
            header("Location: edit.php");
            exit();
        } else {
            echo "Incorrect password. Please try again.";
        }
    }
    // Close the connection to the database.
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <div>
                <br>
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" disabled>
                <br>
            </div>

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" disabled>
            <br>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" disabled>
            <br>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>" disabled>
            <br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <br>
            <br>

            <input type="submit" value="Submit" name="submit">
        </form>
        <p><a href="index.php">Back to main page</a></p>
    </div>
</body>
</html>
