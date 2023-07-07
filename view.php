<?php
// Connect to the database.
$conn = mysqli_connect("localhost", "root", "", "signup_signin");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user data from the database.
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Display user data in a table.
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Username</th><th>Email</th><th>Action</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['firstname'] . "</td>";
        echo "<td>" . $row['lastname'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td><a href='testedit.php?id=" . $row['id'] . "'>Edit</a></td>"; // Edit link

        echo "<td>";
        echo "<form action='view.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<input type='text' name='firstname' placeholder='First Name' value='" . $row['firstname'] . "'>";
        echo "<input type='text' name='lastname' placeholder='Last Name' value='" . $row['lastname'] . "'>";
        echo "<input type='text' name='username' placeholder='Username' value='" . $row['username'] . "'>";
        echo "<input type='text' name='email' placeholder='Email' value='" . $row['email'] . "'>";
        echo "<input type='submit' value='Update' name='submit'>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}

// Close the connection to the database.
mysqli_close($conn);
?>
