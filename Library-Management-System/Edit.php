<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Edit</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
    <body style="background-color: #1d2630;">
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="#">Library System</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="registration.php">Add New Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="BookList.php">Book List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Checkout.php">Checkout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php 
            include("database.php");
            if (isset($_POST["edit"])) {
                $title = $_POST["title"];
                $category = $_POST["category"];
                $rentalPrice = $_POST["rental_price"];
                $availableCopies = $_POST["available_copies"];

                
                $title = mysqli_real_escape_string($conn, $title);
                $category = mysqli_real_escape_string($conn, $category);
                $rentalPrice = mysqli_real_escape_string($conn, $rentalPrice);
                $availableCopies = mysqli_real_escape_string($conn, $availableCopies);

                
                $authorName = null;
                $isbn = $_GET["ISBN"];
                $authorQuery = "SELECT a.Name FROM Author a JOIN Book b ON a.Author_id = b.Author_id WHERE b.ISBN = '$isbn'";
                $authorResult = mysqli_query($conn, $authorQuery);

                if ($authorResult) {
                    $authorRow = mysqli_fetch_assoc($authorResult);
                    if ($authorRow) {
                        $authorName = $authorRow["Name"];
                    }
                }

                
                if ($_POST["author"] !== $authorName) {
                    
                    $auth = $_POST["author"];
                    $authQuery = "INSERT INTO Author (Name) VALUES ('$auth') ON DUPLICATE KEY UPDATE Author_id = LAST_INSERT_ID(Author_id)";
                    if (mysqli_query($conn, $authQuery)) {
                        $authorID = mysqli_insert_id($conn);
                    } else {
                        echo "Error adding/updating author: " . mysqli_error($conn);
                        exit(); // Exit if there's an error
                    }
                } else {
                    
                    $authorIDQuery = "SELECT a.Author_id FROM Author a JOIN Book b ON a.Author_id = b.Author_id WHERE b.ISBN = '$isbn'";
                    $authorIDResult = mysqli_query($conn, $authorIDQuery);

                    if ($authorIDResult) {
                        $authorIDRow = mysqli_fetch_assoc($authorIDResult);
                        if ($authorIDRow) {
                            $authorID = $authorIDRow["Author_id"];
                        }
                    }
                }

                
                $bookUpdateQuery = "UPDATE Book SET Author_id = '$authorID', Category = '$category', Rental_price = '$rentalPrice', available_copies = '$availableCopies' WHERE ISBN = '$isbn'";

                if (mysqli_query($conn, $bookUpdateQuery)) {
                    // Successful update
                    echo '<font color="green">"Book updated successfully!"</font>';
                } else {
                    echo "Error updating book: " . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
            ?>

            <header class="d-flex justify-content-between my-4">
                <h1 style="color: white;">Edit Book</h1>
                <div>
                    <a href="BookList.php" class="btn btn-primary">Back</a>
                </div>
            </header>
            
            <?php
            
            $authorName = "";
            $isbn = $_GET["ISBN"];
            $authorQuery = "SELECT a.Name FROM Author a JOIN Book b ON a.Author_id = b.Author_id WHERE b.ISBN = '$isbn'";
            $authorResult = mysqli_query($conn, $authorQuery);

            if ($authorResult) {
                $authorRow = mysqli_fetch_assoc($authorResult);
                if ($authorRow) {
                    $authorName = $authorRow["Name"];
                }
            }
            ?>
            <?php
            if (isset($_GET["ISBN"])) {
                $isbn = $_GET["ISBN"];
                include("connect.php");
                $sql = "SELECT * FROM Book WHERE ISBN=$isbn";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                } else {
                    echo "Error fetching book details: " . mysqli_error($conn);
                }
            }
            ?>
            
            <form action="" method="post">
    <div class="form-element my-4">
        <input type="text" class="form-control" name="title" value="<?php echo isset($row["Title"]) ? $row["Title"] : ''; ?>" placeholder="Book Title">
    </div>
    <div class="form-element my-4">
        <!-- Display author's name by default -->
        <input type="text" class="form-control" name="author" value="<?php echo $authorName; ?>" placeholder="Author Name">
    </div>
    <div class="form-element my-4">
        <select name="category" class="form-control">
            <option value="">Select Book Category</option>
            <option value="Adventure" <?php if ($row['Category'] == "Adventure") echo "selected"; ?>>Adventure</option>
            <option value="Biography" <?php if ($row['Category'] == "Biography") echo "selected"; ?>>Biography</option>
            <option value="Comedy" <?php if($row['Category'] == "Comedy") echo "selected"; ?>>Comedy</option>
            <option value="Cookbook" <?php if($row['Category'] == "Comedy") echo "selected"; ?>>Cookbook</option>
            <option value="Drama" <?php if($row['Category'] == "Drama") echo "selected"; ?>>Drama</option>
            <option value="Fantasy" <?php if($row['Category'] == "Fantasy") echo "selected"; ?>>Fantasy</option>
            <option value="Fiction" <?php if($row['Category'] == "Fiction") echo "selected"; ?>>Fiction</option>
            <option value="History" <?php if($row['Category'] == "History") echo "selected"; ?>>History</option>
            <option value="Humor" <?php if($row['Category'] == "Humor") echo "selected"; ?>>Humor</option>
            <option value="Mystery" <?php if($row['Category'] == "Mystery") echo "selected"; ?>>Mystery</option>
            <option value="Non-Fiction" <?php if($row['Category'] == "Non-Fiction") echo "selected"; ?>>Non-Fiction</option>
            <option value="Romance" <?php if($row['Category'] == "Romance") echo "selected"; ?>>Romance</option>
            <option value="Sci-Fi" <?php if($row['Category'] == "Sci-Fi") echo "selected"; ?>>Sci-Fi</option>
            <option value="Science" <?php if($row['Category'] == "Science") echo "selected"; ?>>Science</option>
            <option value="Science Fiction" <?php if($row['Category'] == "Science Fiction") echo "selected"; ?>>Science Fiction</option>
            <option value="Self-Help" <?php if($row['Category'] == "Self-Help") echo "selected"; ?>>Self-Help</option>
        </select>
    </div>
    <div class="form-element my-4">
        <input type="text" class="form-control" name="rental_price" value="<?php echo isset($row["Rental_price"]) ? $row["Rental_price"] : ''; ?>" placeholder="Rental Price">
    </div>
    <div class="form-element my-4">
        <input type="text" class="form-control" name="available_copies" value="<?php echo isset($row["available_copies"]) ? $row["available_copies"] : ''; ?>" placeholder="Available Copies">
    </div>
    <div class="form-element">
        <input type="submit" class="btn btn-success" name="edit" value="Edit Book">
    </div>
        </div>
        <script src="" async defer></script>
    </body>
</html>