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

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <form action="edit.php" method="post">

    
    <?php
        if(isset($_POST["submit"])) {
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if(empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password)) {
                array_push($errors, "all fields are required.");
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid.");
            }

            if(strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters.");
            }

            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
                array_push($errors, "password must contain at least one special character
                , one uppercase letter
                , one lowercase letter and one number.");
            }

            require_once("database.php");
            $sql = "SELECT * from users WHERE username = '$username'";
            $result  = mysqli_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);
            if($_POST["username"] !== $user["username"]) {
                if($rowcount>0) {
                    array_push($errors,"Username already exists.");
                }
            }
            $sql = "SELECT * from users WHERE email = '$email'";
            $result  = mysqli_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);
            if($_POST["email"] !== $user["email"]) {
                if($rowcount>0) {
                    array_push($errors,"Email already exists.");
                }
            }

            if (count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $test = $_SESSION["user"];
                $sql = "UPDATE users
                SET firstname = '$firstname', lastname = '$lastname', username = '$username', email = '$email', password = '$password'
                WHERE username = '$test';";
                echo "<div class='alert alert-success'>Your data has been updated successfully</div>";
            }
        }
        ?>


        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value=<?php echo isset($user["firstname"]) ? $user["firstname"] : ""; ?> >
        </div>

        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value=<?php echo isset($user["lastname"]) ? $user["lastname"] : ""; ?> >
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value=<?php echo isset($user["username"]) ? $user["username"] : ""; ?> >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"  value=<?php echo isset($user["email"]) ? $user["email"] : ""; ?> >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="New Password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?> >
        </div>


        <div class="form-btn">
            <input type="submit" name="submit" value="Edit">
        </div>
            <p><a href="profile.php">Back to profile</a></p>
        
    </form>
    </div>
</body>
</html>