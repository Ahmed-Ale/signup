<?php
    session_start();
    if(!isset($_SESSION["user"])) {
        header("Location: sign-in.php");
    } else {
        require_once("database.php");
        $username = $_SESSION['user'];
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        // echo "<br>";
        // echo $user['firstname'];
        // echo "<br>";
        // echo $user['username'];
        // echo "<br>";
        // echo $user['email'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <div class="container">
    <form action="profile.php" method="post">
        <?php
        if(isset($_POST["submit"])) {
            $password = $_POST["password"];
            if(password_verify($password, $user["password"])) {
                header("location: edit.php");
            } else {
                echo "wrong password";
            }
        }
        ?>
        
        <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value=<?php echo isset($user["firstname"]) ? $user["firstname"] : ""; ?> disabled>
        </div>

        <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value=<?php echo isset($user["lastname"]) ? $user["lastname"] : ""; ?> disabled>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value=<?php echo isset($user["username"]) ? $user["username"] : ""; ?> disabled>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"  value=<?php echo isset($user["email"]) ? $user["email"] : ""; ?> disabled>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?> >
        </div>

        <div class="form-btn">
                <input type="submit" name="submit" value="Submit">
            </div>

            <p><a href="index.php">Back to main page</a></p>
    </form>

</body>
</html>
