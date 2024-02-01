<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #1d2630;
            color: white;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .confirmation {
            margin-top: 20px;
            text-align: center;
        }
        .back-link {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        include("database.php");

        
        if (isset($_GET["ISBN"])) {
            $isbn = $_GET["ISBN"];
        } else {
            echo "<p>ISBN not provided.</p>";
            exit;
        }

        // delete relatd records in BookCopies
        $deleteBookCopiesQuery = "DELETE FROM BookCopies WHERE book_ISBN = $isbn";

        if (mysqli_query($conn, $deleteBookCopiesQuery)) {
            $deleteBookQuery = "DELETE FROM Book WHERE ISBN = $isbn";

            if (mysqli_query($conn, $deleteBookQuery)) {
                echo "<h2 class='text-success'>Book deleted successfully!</h2>";
            } else {
                echo "<p class='text-danger'>Error deleting book: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p class='text-danger'>Error deleting book copies: " . mysqli_error($conn) . "</p>";
        }

        mysqli_close($conn);
        ?>
        <div class="confirmation">
            <a href="BookList.php" class="btn btn-primary">Back to Book List</a>
        </div>
    </div>
</body>
</html>


