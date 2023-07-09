<?php
    session_start();
    if(isset($_SESSION["user"])) {
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <div class="container">
    <form action="signup.php" method="post">
            <h1>Sign Up</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

        <?php
        if(isset($_POST["submit"])) {
            require_once("database.php");

            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeated_password = $_POST["repeated_password"];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if(empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password) || empty($repeated_password)) {
                array_push($errors, "all fields are required.");
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid.");
            }

            if(strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters.");
            }

            if($password !== $repeated_password) {
                array_push($errors, "Passwords do not match");
            }

            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
                array_push($errors, "password must contain at least one special character
                , one uppercase letter
                , one lowercase letter and one number.");
            }

            $sql = "SELECT * from users WHERE username = '$username'";
            $result  = mysqli_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);
            if($rowcount>0) {
                array_push($errors,"Username already exists.");
            }

            $sql = "SELECT * from users WHERE email = '$email'";
            $result  = mysqli_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);
            if($rowcount>0) {
                array_push($errors,"Email already exists.");
            }

            if (count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users (firstname, lastname, username, email, password) VALUES(?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $stmt_prepare = mysqli_stmt_prepare($stmt,$sql);
                if($stmt_prepare) {
                    mysqli_stmt_bind_param($stmt,"sssss",$firstname,$lastname,$username,$email,$pass_hash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>you are registered successfully</div>";
                } else {
                    die("something went wrong");
                }
            }
        }
        ?>

            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" value=<?php echo isset($_POST["firstname"]) ? $_POST["firstname"] : ""; ?> >
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" value=<?php echo isset($_POST["lastname"]) ? $_POST["lastname"] : ""; ?> >
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" value=<?php echo isset($_POST["username"]) ? $_POST["username"] : ""; ?> >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value=<?php echo isset($_POST["email"]) ? $_POST["email"] : ""; ?> >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?> >
            </div>

            <div class="form-group">
                <label for="repeated_password">Repeat Password</label>
                <input type="password" id="repeated_password" name="repeated_password" placeholder="Repeat your password" value=<?php echo isset($_POST["repeated_password"]) ? $_POST["repeated_password"] : ""; ?> >
            </div>

            <div class="form-btn">
                <input type="submit" name="submit" value="Sign up">
            </div>
            <p>Already a member?</p> <a href="sign-in.php">Sign in here</a>
        </form>
    </div>
</body>
</html>
