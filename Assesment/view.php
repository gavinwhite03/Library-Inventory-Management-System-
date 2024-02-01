<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Book Details</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            .book-details {
                background-color: #1d2630;
                padding: 50px;
                color: white;
            }
            body{
                padding-top: 100px;
            }
        </style>
    </head>
    <body style="background-color: #1d2630;">
        <div class="container">
            <header class="d-flex justify-content-between my-4">
                <h1 style="color: white;">Book Details</h1>
                <div>
                    <a href="BookList.php" class="btn btn-primary">Back</a>
                </div>
            </header>
            <div class="book-details my-4">
            <?php
            if (isset($_GET["ISBN"])) {
                $isbn = $_GET["ISBN"];
                include("database.php");

                // prepared statement to prevent SQL injection
                $stmt = $conn->prepare("SELECT * FROM Book WHERE ISBN = ?");
                $stmt->bind_param("i", $isbn);
                $stmt->execute();

                if ($stmt->error) {
                    echo "Error in Book query: " . $stmt->error;
                }

                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if ($row) {
                    echo "<h2>Title</h2><p>{$row['Title']}</p>";
                    echo "<h2>Author</h2>";

                    // get author details from the Author table based on Author_id
                    $authorId = $row['Author_id'];
                    $authorStmt = $conn->prepare("SELECT * FROM Author WHERE Author_id = ?");
                    $authorStmt->bind_param("i", $authorId);
                    $authorStmt->execute();

                    if ($authorStmt->error) {
                        echo "Error in Author query: " . $authorStmt->error;
                    }

                    $authorResult = $authorStmt->get_result();
                    $authorRow = $authorResult->fetch_assoc();

                    if ($authorRow) {
                        echo "<p>{$authorRow['Name']}</p>";
                    } else {
                        echo "<p>Author not found</p>";
                    }

                    echo "<h2>Category</h2><p>{$row['Category']}</p>";
                    echo "<h2>Rental Price</h2><p>{$row['Rental_price']}</p>";
                    echo "<h2>Available Copies</h2><p>{$row['available_copies']}</p>";
                } else {
                    echo "<p>Book not found</p>";
                }

                $stmt->close();
                $authorStmt->close();
            } else {
                echo "<p>ISBN not provided</p>";
            }
            ?>


            </div>
        </div>
        
        <script src="" async defer></script>
    </body>
</html>
