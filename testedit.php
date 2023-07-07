<?php
// Connect to the database.
$conn = mysqli_connect("localhost", "root", "", "test7");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the ID is provided in the query parameter.
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the user data based on the ID.
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
    }

    // Check if the user exists.
    if (!$row) {
        echo "User not found.";
    }
}

// Close the connection to the database.
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Data</title>
</head>
<body>
    <h1>Edit User</h1>
    <form action="testupdate.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="firstname" placeholder="First Name" value="<?php echo $row['firstname']; ?>">
        <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $row['lastname']; ?>">
        <input type="text" name="username" placeholder="Username" value="<?php echo $row['username']; ?>">
        <input type="text" name="email" placeholder="Email" value="<?php echo $row['email']; ?>">
        <input type="submit" value="Update" name="submit">
    </form>
</body>
</html>
