<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: sign-in.php");
    exit();
} else {
    // Connect to the database.
    $conn = mysqli_connect("localhost", "root", "", "signup_signin");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // Retrieve user data from the database.
    $username = $_SESSION["user"];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if (isset($_POST["update"])) {
        $errors = array();
        if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["repeated_password"])) {
            array_push($errors, "All fields are required.");
        }

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid.");
        }

        if (strlen($_POST["password"]) < 8) {
            array_push($errors, "Password must be at least 8 characters.");
        }

        if ($_POST["password"] !== $_POST["repeated_password"]) {
            array_push($errors, "Passwords do not match");
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $_POST["password"])) {
            array_push($errors, "Password must contain at least one special character, one uppercase letter, one lowercase letter, and one number.");
        }
        if ($_POST["username"] !== $user['username']) {
            $newusername = $_POST["username"];
            $sql = "SELECT * FROM users WHERE username = '$newusername'";
            $result  = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
                array_push($errors, "Username already exists.");
            }
        }

        if ($_POST["email"] !== $user['email']) {
            $newemail = $_POST["email"];
            $sql = "SELECT * FROM users WHERE email = '$newemail'";
            $result  = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
                array_push($errors, "Email already exists.");
            }
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $pass_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET firstname = ?, lastname = ?, username = ?, email = ?, password = ? WHERE username = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssss", $firstname, $lastname, $username, $email, $pass_hash, $user['username']);
                mysqli_stmt_execute($stmt);
                $_SESSION["user"] = $username;
                header("Location: updated.html");
            } else {
                echo "Error updating data: " . mysqli_error($conn);
            }
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
        <form action="edit.php" method="post">
            <br>
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>">
            <br>

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
            <br>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
            <br>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>">
            <br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <br>

            <label for="repeated_password">Repeated Password</label>
            <input type="password" id="repeated_password" name="repeated_password">
            <br>
            <br>

            <input type="submit" value="Update" name="update">
        </form>
        <p><a href="index.php">Back to main page</a></p>
    </div>
</body>
</html>
