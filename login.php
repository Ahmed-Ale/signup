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
    <title>Sign In Form</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"> -->
    <link rel="stylesheet" href="style1.css">
    
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <p>Please fill in this form to login to your account.</p>
            <hr>
            <?php
                if (isset($_POST["submit"])) {
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $errors = array();

                    if (empty($email) || empty($password)) {
                        array_push($errors, "All fields are required");
                    }

                    if(count($errors)>0) {
                        foreach($errors as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    } else {
                        require_once "database.php";
                        $sql = "SELECT * from users WHERE email = '$email'";
                        $result  = mysqli_query($conn,$sql);
                        $user = mysqli_fetch_array($result);
                        if($user) {
                            if(password_verify($password, $user["password"])) {
                                session_start();
                                $_SESSION["user"] = $user["username"];
                                header("Location: index.php");
                                die();
                            } else {
                            echo "<div class='alert alert-danger'>Password does not match </div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Email does not match </div>";
                        }
                    }

                }
                ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email" value=<?php echo isset($_POST["email"]) ? $_POST["email"] : ""; ?>>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?>>
            </div>

            <div class="form-btn">
                <input type="submit" name="submit" value="Login">
            </div>

            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </form>
    </div>
</body>
</html>
