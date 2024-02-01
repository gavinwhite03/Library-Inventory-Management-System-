<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Checkout</title>
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
        <header class="d-flex justify-content-between my-4">
            <h1 style="color: white;">Checkout</h1>
        </header>
        <form class="mb-4" method="get" action="">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by Name, Author, or ISBN" name="search">
                <button class="btn btn-secondary" type="submit">Search</button>
            </div>
        </form>
        <div class="table-container" style="overflow: auto; max-height: 400px;">
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
        <br>
        <div class="table-container" style="overflow:auto; max-height: 400px">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Checkout_ID</th>
                        <th>Return Date</th>
                        <th>ISBN</th>
                        <th>Customer_ID</th>
                        <th>Is_returned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT checkout_id, end_time, book_ISBN, customer_id, is_returned FROM Checkouts";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['checkout_id'] . '</td>';
                            echo '<td>' . $row['end_time'] . '</td>';
                            echo '<td>' . $row['book_ISBN'] . '</td>';
                            echo '<td>' . $row['customer_id'] . '</td>';
                            echo '<td>' . ($row['is_returned'] ? 'No' : 'Yes') . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">0 results</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <form action="" method="post">
            <div class="form-element">
                <label for="ISBN" style="color: white">ISBN:</label>
                <input type="text" class="form-control" name="ISBN" required>
            </div>
            <div class="form-element">
                <label for="customerID" style="color: white">Customer ID:</label>
                <input type="text" class="form-control" name="customerID" required>
            </div>
            <div class="form-element">
                <input type="submit" class="btn btn-success" name="checkout" value="Checkout">
            </div>
        </form>
        <form action="" method="post">
            <div class="form-element">
                <label for="return_ISBN" style="color: white">ISBN:</label>
                <input type="text" class="form-control" name="return_ISBN" required>
            </div>
            <div class="form-element">
                <label for="return_customerID" style="color: white">Customer ID:</label>
                <input type="text" class="form-control" name="return_customerID" required>
            </div>
            <div class="form-element">
                <input type="submit" class="btn btn-success" name="return" value="Return">
            </div>
        </form>
        <?php
        if (isset($_POST["checkout"])) {
            $ISBN = $_POST["ISBN"];
            $customerID = $_POST["customerID"];
            $checkoutDate = date('Y-m-d'); // Current date
            $returnDate = date('Y-m-d', strtotime($checkoutDate . ' + 14 days'));
            $ISBN = mysqli_real_escape_string($conn, $ISBN);
            $customerID = mysqli_real_escape_string($conn, $customerID);

            // Check if the book is available
            $checkAvailabilityQuery = "SELECT * FROM BookCopies WHERE Book_ISBN = ? AND is_available = 1 LIMIT 1";

            $stmt = mysqli_prepare($conn, $checkAvailabilityQuery);
            mysqli_stmt_bind_param($stmt, "s", $ISBN);
            mysqli_stmt_execute($stmt);
            $availabilityResult = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($availabilityResult) > 0) {
                $checkoutQuery = "INSERT INTO Checkouts (start_time, end_time, book_ISBN, customer_id, is_returned) VALUES (?, ?, ?, ?, 1)";

                $stmt = mysqli_prepare($conn, $checkoutQuery);
                mysqli_stmt_bind_param($stmt, "ssss", $checkoutDate, $returnDate, $ISBN, $customerID);
                $checkoutResult = mysqli_stmt_execute($stmt);

                if ($checkoutResult) {
                    $updateAvailabilityQuery = "UPDATE BookCopies SET is_available = 0 WHERE Book_ISBN = ? AND is_available = 1 LIMIT 1";

                    $stmt = mysqli_prepare($conn, $updateAvailabilityQuery);
                    mysqli_stmt_bind_param($stmt, "s", $ISBN);
                    mysqli_stmt_execute($stmt);

                    echo "<p class='text-success'>Checkout successful!</p>";
                } else {
                    echo "<p class='text-danger'>Error during checkout: " . mysqli_stmt_error($stmt) . "</p>";
                }
            } else {
                echo "<p class='text-danger'>Book not available for checkout.</p>";
            }
            mysqli_stmt_close($stmt);
        }

        if (isset($_POST["return"])) {
            $ISBN = $_POST["return_ISBN"];
            $customerID = $_POST["return_customerID"];
        
            $ISBN = mysqli_real_escape_string($conn, $ISBN);
            $customerID = mysqli_real_escape_string($conn, $customerID);
        
            $checkReturnEligibilityQuery = "SELECT * FROM Checkouts WHERE book_ISBN = ? AND customer_id = ? AND is_returned = 1 LIMIT 1";
        
            $stmt = mysqli_prepare($conn, $checkReturnEligibilityQuery);
            mysqli_stmt_bind_param($stmt, "ss", $ISBN, $customerID);
            mysqli_stmt_execute($stmt);
            $returnEligibilityResult = mysqli_stmt_get_result($stmt);
        
            if (mysqli_num_rows($returnEligibilityResult) > 0) {
                $returnQuery = "UPDATE BookCopies SET is_available = 1 WHERE Book_ISBN = ?";
        
                $stmt = mysqli_prepare($conn, $returnQuery);
                mysqli_stmt_bind_param($stmt, "s", $ISBN);
                $returnResult = mysqli_stmt_execute($stmt);
        
                if ($returnResult) {
                    $updateReturnStatusQuery = "UPDATE Checkouts SET is_returned = 0 WHERE book_ISBN = ? AND customer_id = ? AND is_returned = 1 LIMIT 1";
        
                    $stmt = mysqli_prepare($conn, $updateReturnStatusQuery);
                    mysqli_stmt_bind_param($stmt, "ss", $ISBN, $customerID);
                    mysqli_stmt_execute($stmt);
        
                    echo "<p class='text-success'>Return successful!</p>";
                } else {
                    echo "<p class='text-danger'>Error during return: " . mysqli_stmt_error($stmt) . "</p>";
                }
            } else {
                echo "<p class='text-danger'>Book not eligible for return.</p>";
            }
            mysqli_stmt_close($stmt);
        }
        

                
        ?>
    </div>
</body>
</html>


