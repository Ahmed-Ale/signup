<?php
    $host = "localhost";
    $user = "root";
    $pass ="";
    $dbname = "signup_signin";

    $conn = mysqli_connect($host,$user,$pass,$dbname);

    if(!$conn) {
        die("Could not connect to the database");
    }




?>