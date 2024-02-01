<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Add New Book</title>
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
                            <a class="nav-link" href="index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php 
            include("database.php");
            if (isset($_POST["create"])) {
                $title = $_POST["title"];
                $auth = $_POST["author"];
                $category = $_POST["category"];
                $rentalPrice = $_POST["rental_price"];
                $availableCopies = $_POST["available_copies"];

                
                $title = mysqli_real_escape_string($conn, $title);
                $category = mysqli_real_escape_string($conn, $category);
                $auth = mysqli_real_escape_string($conn, $auth);
                $rentalPrice = mysqli_real_escape_string($conn, $rentalPrice);
                $availableCopies = mysqli_real_escape_string($conn, $availableCopies);

                
                $authorInsertQuery = "INSERT INTO Author (Name) VALUES ('$auth') ON DUPLICATE KEY UPDATE Author_id = LAST_INSERT_ID(Author_id)";
                if (mysqli_query($conn, $authorInsertQuery)) {
                    $authorID = mysqli_insert_id($conn);

                    
                    $bookInsertQuery = "INSERT INTO Book (Title, Author_id, Category, Rental_price, available_copies) VALUES ('$title', '$authorID', '$category', '$rentalPrice', '$availableCopies')";
                    if (mysqli_query($conn, $bookInsertQuery)) {
                        // Successful insertion
                        echo '<span style="color:#AFA;text-align:center;">"Book added successfully!"</span>';

                        
                        $bookISBN = mysqli_insert_id($conn); // Get the ISBN of the inserted book
                        for ($i = 1; $i <= $availableCopies; $i++) {
                            $bookCopiesInsertQuery = "INSERT INTO BookCopies (Book_ISBN, is_available) VALUES ('$bookISBN', 1)";
                            if (!mysqli_query($conn, $bookCopiesInsertQuery)) {
                                // Error in BookCopies insertion
                                echo "Error adding BookCopies: " . mysqli_error($conn);
                                break;
                            }
                        }
                    } else {
                      
                        echo "Error adding book: " . mysqli_error($conn);
                    }
                } else {
                    
                    echo "Error adding author: " . mysqli_error($conn);
                }

                
                mysqli_close($conn);
            }
        ?>
            <header class="d-flex justify-content-between my-4">
                <h1 style="color: white;">Add New Book</h1>
                <div>
                    <a href="BookList.php" class="btn btn-primary">Back</a>
                </div>
            </header>
            <form action="" method="post">
                <div class="form-element my-4">
                    <input type="text" class="form-control" name="title" placeholder="Book Title:">
                </div>
                <div class="form-element my-4">
                    <input type="text" class="form-control" name="author" placeholder="Author Name:">
                </div>
                <div class="form-element my-4">
                    <select name="category" class="form-control">
                        <option value="">Select Book Category</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Biography">Biography</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Cookbook">Cookbook</option>
                        <option value="Drama">Drama</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Fiction">Fiction</option>
                        <option value="History">History</option>
                        <option value="Humor">Humor</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Romance">Romance</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Science">Science</option>
                        <option value="Science Fiction">Science Fiction</option>
                        <option value="Self-Help">Self-Help</option>
                    </select>
                </div>
                <div class="form-element my-4">
                    <input type="text" class="form-control" name="rental_price" placeholder="Rental Price:">
                </div>
                <div class="form-element my-4">
                    <input type="text" class="form-control" name="available_copies" placeholder="Available Copies:">
                </div>
                <div class="form-element">
                    <input type="submit" class="btn btn-success" name="create" value="Add Book">
                </div>
            </form>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-GLhlTQ8iE7NtL7MqQl6dApxQKTtw+Yx2L6i2Buq3SNAi1bSd4+e3q5Aq8aqq9a0Z" crossorigin="anonymous" async defer></script>
    </body>
</html>