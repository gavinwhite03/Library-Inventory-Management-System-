<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Registration</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <style>
            .navbar-custom {
                background-color: #1d2630;
            }
            .navbar-custom .navbar-brand,
            .navbar-custom .navbar-nav .nav-link {
                color: white;
            }
        </style>
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="#">Library System</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="Customer.php">Book List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php
            if (isset($_POST["submit"])) {
                $fullname = $_POST["fullname"];
                $email = $_POST["email"];
                $street = $_POST["street"];
                $city = $_POST["city"];
                $errors = array();
                if (empty($fullname) || empty($email)) {
                    array_push($errors,"Name and Email fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors,"Email is not valid");
                }
                
                if(count($errors)>0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    // inserting into database
                    require_once "database.php";
                    $sql = "SELECT * FROM Customer WHERE Email='$email'";
                    $result = mysqli_query($conn, $sql);
                    $rowCount = mysqli_num_rows($result);
                    if ($rowCount > 0) {
                        echo "<div class='alert alert-danger'>Email already exists!</div>";
                    } else {
                        $query = "INSERT INTO Customer(FullName, Email, Street, Registration_date, City) VALUES (?, ?, ?, NOW(), ?) ";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt, $query);
                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $street, $city);
                            mysqli_stmt_execute($stmt);
                            echo "<div class='alert alert-success'>Customer Registered Successfully.</div>";
                        } else {
                            die("Something went wrong.");
                        }
                    }
                }
            }
            ?>
            <header class="d-flex justify-content-between my-4">
                <h1 style="color: white;">Register As New Customer</h1>
                <div>
                    <a href="Customer.php" class="btn btn-primary">Back</a>
                </div>
            </header>
            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email:">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="street" placeholder="Street:">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="city" placeholder="City:">
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-GLhlTQ8iE7NtL7MqQl6dApxQKTtw+Yx2L6i2Buq3SNAi1bSd4+e3q5Aq8aqq9a0Z" crossorigin="anonymous" async defer></script>
    </body>
</html>