<?php
// Replace with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crowdfund";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all queries from the database
function fetchAllQueries()
{
    global $conn;

    $query = "SELECT * FROM queries";
    $result = $conn->query($query);

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Check if the "delete" parameter is present in the URL
if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    $deleteResult = deleteQuery($deleteID);
    if ($deleteResult) {
        // Refresh the page to update the list after deletion
        echo "<script>window.location.href = 'admin_help.php';</script>";
        exit(); // Added exit() to prevent further execution
    }
}

// Function to delete a query based on its ID
function deleteQuery($id)
{
    global $conn;
    $query = "DELETE FROM queries WHERE query_id = '$id'";
    $result = $conn->query($query);
    return $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Review - Users Question </title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4c43584236.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        /* styles.css */

        body {
            font-family: Arial, sans-serif;
            background-color: #E5E4E2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            /* max-width: 1200px; */
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.50);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            top: 60px;
            left: -2px;
            overflow-x: scroll;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .btn {
            padding: 5px;
            background-color: #007bff;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s ease;
            color: white;
            margin: 7px;
            display: flex;
            flex-direction: column;
        }

        .btn:hover {
            background-color: rgb(132, 0, 255);
            color: #ffff;
        }

        .btn-2 {
            padding: 5px;
            background-color: #007bff;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s ease;
            color: white;
            margin: 7px;
            position: relative;
            top: 5px;
        }

        .btn-2:hover {
            background-color: rgb(132, 0, 255);
            color: #ffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #989191;
        }

        td {
            font-weight: 600;
        }

        td:hover {
            cursor: pointer;
            color: white;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .go-back {
            padding: 10px;
            text-align: center;
            background-color: orange;
            cursor: pointer;
            color: white;
            text-decoration: none;
        }

        .go-back:hover {
            background-color: #0088cc;
            transition: 0.2s linear;
            text-decoration: none;
        }

        .back-button {
            position: relative;
            top: 20px;
            margin-left: 10px;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
            position: relative;
            top: 440px;
        }

        .pagination a {
            padding: 8px 12px;
            border: 1px solid #007bff;
            border-radius: 4px;
            color: #007bff;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }

        /* Responsive design using media queries */
        @media screen and (max-width: 600px) {
            table {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 400px) {
            body {
                background-image: none;
                background-color: white;
            }

            table {
                font-size: 12px;
            }
        }

        .back-button {
            width: 50px;
            height: 50px;
            position: absolute;
            top: 20px; /* Adjust the top position as needed */
            left: 20px; /* Adjust the left position as needed */
            border-radius: 50%;
            border: #000 1px solid;
            overflow: hidden;
            transition: background 0.3s ease;
            z-index: 1000; /* Ensure the back button stays above other elements */
            }


            .back-button.back .arrow-wrap {
            left: -50%;
            }

            .back-button:hover {
            background: #fff;
            color:#000;
            }

            .back-button:hover .arrow-wrap span {
            background: #000;
            }

            .back-button .arrow-wrap {
            display: block;
            position: absolute;
            height: 70%;
            width: 70%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: left 0.3s ease;
            }

            .back-button .arrow-wrap span {
            height: 1px;
            left: 0;
            top: 50%;
            background: #000;
            position: absolute;
            display: block;
            transition: background 0.3s ease;
            }

            .back-button .arrow-wrap .arrow-part-1 {
            width: 100%;
            transform: translate(0, -50%);
            }

            .back-button .arrow-wrap .arrow-part-2 {
            width: 60%;
            transform: rotate(-45deg);
            transform-origin: 0 0;
            }

            .back-button .arrow-wrap .arrow-part-3 {
            width: 60%;
            transform: rotate(45deg);
            transform-origin: 0 0;
            }
    </style>
</head>

<body>
<div class="back-button">
          <div class="arrow-wrap">
            <span class="arrow-part-1"></span>
            <span class="arrow-part-2"></span>
            <span class="arrow-part-3"></span>
          </div>
</div>
    <div class="container">
        <h1>Users Questions Review</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>User Question</th>
                <th>Date Questioned</th>
                <th>Answer Replied</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all queries from the database
            $data = fetchAllQueries();

            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . $row['user_id'] . '</td>';
                echo '<td>' . $row['user_name'] . '</td>';
                echo '<td>' . $row['user_phone'] . '</td>';
                echo '<td>' . $row['user_email'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>' . $row['date_questioned'] . '</td>';
                echo '<td>' . $row['admin_reply'] . '</td>';
                echo '<td class="two-btn">';
                echo '<div><a href="admin_update_queries.php?id=' . $row['query_id'] . '">Update</a></div> | ';
                echo '<div><a href="?delete=' . $row['query_id'] . '" onclick="return confirmDelete();">Delete</a></div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this Question From the user?");
        }
    </script>
    <script>
    var backButton = document.querySelector('.back-button')
    function backAnim() {
    if (backButton.classList.contains('back')) {
        backButton.classList.remove('back');
    } else {
        backButton.classList.add('back');
        setTimeout(backAnim, 1000);
    }
    }

    backButton.addEventListener('click', function() {
    window.location.href = 'admin_landing.php'; // Adjust the path as needed
    });
</script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>
