<?php
    session_start();
    if(!isset($_SESSION["user"])) {
        header("Location: index.php");
    }
    require_once "database.php";
    $currentuser = $_SESSION["user"];
    $sql = "SELECT * from users WHERE username = '$currentuser'";
    $result  = mysqli_query($conn,$sql);
    $user = mysqli_fetch_array($result);

    if(isset($post["submit"])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);

        do {
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

            if($user["username"] !== $post["username"]) {
                $sql = "SELECT * from users WHERE username = '$username'";
                $result  = mysqli_query($conn,$sql);
                $rowcount = mysqli_num_rows($result);
                if($rowcount>0) {
                    array_push($errors,"Username already exists.");
                }
            }

            if($user["email"] !== $post["email"]) {
                $sql = "SELECT * from users WHERE email = '$email'";
                $result  = mysqli_query($conn,$sql);
                $rowcount = mysqli_num_rows($result);
                if($rowcount>0) {
                    array_push($errors,"Email already exists.");
                }
            }

            if (count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "UPDATE users ".
                "SET firstname = '$firstname', lastname = '$lastname', username = '$username', email = '$email', password = '$pass_hash'" . 
                "WHERE username = '$currentuser'";
                $result = $conn->query($sql);
            }
            echo "data updated successfully";
        } while (true);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form action="edit.php" method="post">

            <input type="hidden" value="<?php echo $id ?>">
            <br>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" value=<?php echo isset($_POST["firstname"]) ? $_POST["firstname"] : $user["firstname"]; ?> >
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" value=<?php echo isset($_POST["lastname"]) ? $_POST["lastname"] : $user["lastname"]; ?> >
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" value=<?php echo isset($_POST["username"]) ? $_POST["username"] : $user["username"]; ?> >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value=<?php echo isset($_POST["email"]) ? $_POST["email"] : $user["email"]; ?> >
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
                <input type="submit" name="submit" value="Update">
            </div>
            <p><a href="profile.php">Back to profile</a></p>
        </form>
    </div>
</body>
</html>
