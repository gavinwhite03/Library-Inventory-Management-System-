<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            .navbar-custom {
                background-color: #1d2630;
            }
            .navbar-custom .navbar-brand,
            .navbar-custom .navbar-nav .nav-link {
                color: white;
            }
            .table-container {
                max-height: 500px;
                overflow-y: auto;
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
                            <a class="nav-link" href="Customer.php">Book List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="CustomerRegistration.php">Register As Customer</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1 style="color: white;">Book List</h1>
        </header>
        <form class="mb-4" method="get" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by Name, Author, or ISBN" name="search">
                <button class="btn btn-secondary" type="submit">Search</button>
            </div>
        </form>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Rental Price</th>
                        <th>Available Copies</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                include("database.php");

                // check if a search query is provided
                if (isset($_GET["search"])) {
                    $searchTerm = $_GET["search"];
                    $sql = "SELECT Book.*, Author.Name AS AuthorName FROM Book JOIN Author ON Book.Author_id = Author.Author_id WHERE Title LIKE ? OR Author.Name LIKE ? OR ISBN LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $searchPattern = "%$searchTerm%";
                    $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $sql = "SELECT Book.*, Author.Name AS AuthorName FROM Book JOIN Author ON Book.Author_id = Author.Author_id";
                    $result = mysqli_query($conn, $sql);
                }



                while ($row = mysqli_fetch_array($result)) {
                    $authorId = $row["Author_id"];
                    $authorQuery = "SELECT Name FROM Author WHERE Author_id = ?";

                    $authorStmt = $conn->prepare($authorQuery);
                    $authorStmt->bind_param("i", $authorId);
                    $authorStmt->execute();
                    $authorStmt->bind_result($authorName);
                    $authorStmt->fetch();
                    $authorStmt->close();

                    // Display book details
                    ?>
                    <tr>
                        <td><?php echo $row["ISBN"]; ?></td>
                        <td><?php echo $row["Title"]; ?></td>
                        <td><?php echo $authorName; ?></td>
                        <td><?php echo $row["Category"]; ?></td>
                        <td><?php echo $row["Rental_price"]; ?></td>
                        <td><?php echo $row["available_copies"]; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="" async defer></script>
    <?php

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ?>

</body>
</html>