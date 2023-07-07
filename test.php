<?php
// Connect to the database.
if(isset($_POST["submit"])) {
    session_start();

    $conn = mysqli_connect("localhost", "root", "", "test7");

    // Get the user data from the form.
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Redirect the user to the home page.
    $sql = "INSERT INTO users (firstname, lastname, username, email) VALUES(?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    $stmt_prepare = mysqli_stmt_prepare($stmt,$sql);
    if($stmt_prepare) {
        mysqli_stmt_bind_param($stmt,"ssss",$firstname,$lastname,$username,$email);
        mysqli_stmt_execute($stmt);
        echo "data inserted successfully";
    } else {
    // Display an error message if the user data was not inserted successfully.
    echo "The user data could not be inserted.";
    }

    // Close the connection to the database.
    mysqli_close($conn);

}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Insert User Data</title>
</head>
<body>
  <form action="test.php" method="post">
    <input type="text" name="firstname" placeholder="First Name">
    <input type="text" name="lastname" placeholder="Last Name">
    <input type="text" name="username" placeholder="Username">
    <input type="text" name="email" placeholder="Email">
    <input type="submit" value="Submit" name="submit">
  </form>
</body>
</html>
