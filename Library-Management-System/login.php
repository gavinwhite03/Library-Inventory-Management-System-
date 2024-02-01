<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // database connection

    $host = "ysjcs.net";
    $dbusername = "gavin.white";
    $dbpassword = "F5EKQPXR";
    $dbname = "gavinwhite_library";

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);

    }

    // Validate Login Details
    $query = "SELECT * FROM Manager WHERE username='$username' AND password='$password'";

    $result = $conn->query($query);

    if($result ->num_rows == 1){
        // login success
        header("Location: success.html");
        
    }
    else{
        // login failed
        header("Location: error.html");
        
    }
    $conn->close();
    exit();
    
}
