<?php
// Connect to the database.
$conn = mysqli_connect("localhost", "root", "", "test7");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted.
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update the user data in the database.
    $sql = "UPDATE users SET firstname = ?, lastname = ?, username = ?, email = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $firstname, $lastname, $username, $email, $id);
        mysqli_stmt_execute($stmt);
        echo "Data updated successfully.";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

// Close the connection to the database.
mysqli_close($conn);
?>
