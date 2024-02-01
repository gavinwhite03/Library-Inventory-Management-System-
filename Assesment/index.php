<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login Page</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            body{
                background-color: #1d2630;
            }
            .container {
                margin-top: 300px;
            }
            input {
                max-width: 300px;
                min-width: 300px;
            }
            
        </style>
    </head>

    <body>
        <div class="container">
            <header class="d-flex justify-content-center my-4">
                <h1 style="color: white;">Manager Login</h1>
            </header>
            <a href="Customer.php" class="btn btn-primary" style="position: absolute; top: 50px; right: 50px;">I am a customer</a>
        <?php
        session_start();
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

            $query = "SELECT * FROM Manager WHERE Username='$username' AND Password='$password'";
            $result = $conn->query($query);
            
            if($result->num_rows == 1){
                header("Location: registration.php");
                
            }
            else{
                $_SESSION['last_query'] = $query;
                $_SESSION['last_result'] = $result;
                header("Location: error.php");
                
            }
            $conn->close();
            exit();
            
        }
        ?>
            <div class="row justify-content-center">
                <div class="col-md-6 col-md-offset-6" align="center">
                    <form action="index.php" method="POST">
                        <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        placeholder="Enter Username"
                        /><br />
                        <input 
                        type="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Enter Password"
                        /><br />
                        <input type="submit" value="Login" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-GLhlTQ8iE7NtL7MqQl6dApxQKTtw+Yx2L6i2Buq3SNAi1bSd4+e3q5Aq8aqq9a0Z" crossorigin="anonymous" async defer></script>
    </body>
</html>