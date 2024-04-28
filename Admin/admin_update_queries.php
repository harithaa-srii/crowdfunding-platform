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

// Function to fetch a specific query from the database based on its ID
function fetchQuery($id)
{
    global $conn;
    $query = "SELECT * FROM queries WHERE query_id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

// Function to update a query with admin's reply
function updateQuery($id, $reply)
{
    global $conn;
    $reply = $conn->real_escape_string($reply);
    $query = "UPDATE queries SET admin_reply = '$reply' WHERE query_id = '$id'";
    $result = $conn->query($query);
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query_id = $_POST['query_id'];
    $admin_reply = $_POST['admin_reply'];

    // Update the query with admin's reply
    $updateResult = updateQuery($query_id, $admin_reply);
    if ($updateResult) {
        // Redirect to the page showing all queries after update
        header("Location: admin_help.php");
        exit();
    }
}

// Check if query ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect to admin_help.php if ID is not provided
    header("Location: admin_help.php");
    exit();
}

$id = $_GET['id'];
$queryData = fetchQuery($id);

// Redirect to admin_help.php if no query is found with the provided ID
if (!$queryData) {
    header("Location: admin_help.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Query - Admin Panel</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Paste CSS styles here */
        body {
            align-items: center;
            background-color: #808097;
            display: flex;
            justify-content: center;
            height: 100vh;
        }

        .form {
            background-color: #15172b;
            width: 500px;
            border-radius: 20px;
            box-sizing: border-box;
            height: 95%;
            padding: 20px;
        }

        .title {
            color: #eee;
            font-family: sans-serif;
            font-size: 36px;
            font-weight: 600;
            margin-top: 30px;
        }

        .subtitle {
            color: #eee;
            font-family: sans-serif;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }

        .input-container {
            margin-top: 30px;
        }

        .input {
            background-color: #303245;
            border-radius: 12px;
            border: 0;
            box-sizing: border-box;
            color: #eee;
            font-size: 18px;
            height: 50px;
            outline: 0;
            padding: 0 20px;
            width: 100%;
        }

        .placeholder {
            color: #65657b;
            font-family: sans-serif;
            font-size: 16px;
            font-weight: bold; /* Added */
            margin-bottom: 5px;
            display: block; /* Added */
        }

        .submit {
            background-color: #06b;
            border-radius: 12px;
            border: 0;
            box-sizing: border-box;
            color: #eee;
            cursor: pointer;
            font-size: 18px;
            height: 50px;
            margin-top: 38px;
            outline: 0;
            text-align: center;
            width: 100%;
        }

        .submit:active {
            background-color: #780d9b;
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
    <div class="form">
        <div class="title">Update Query</div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="query_id" value="<?php echo $queryData['query_id']; ?>">
            <div class="input-container">
                <label for="firstname" class="placeholder">User ID</label>
                <input id="firstname" class="input" type="text" value="<?php echo $queryData['user_id']; ?>" disabled>
            </div>
            <div class="input-container">
                <label for="lastname" class="placeholder">User Name</label>
                <input id="lastname" class="input" type="text" value="<?php echo $queryData['user_name']; ?>" disabled>
            </div>
            <div class="input-container">
                <label for="email" class="placeholder">User Email</label>
                <input id="email" class="input" type="text" value="<?php echo $queryData['user_email']; ?>" disabled>
            </div>
            <div class="input-container">
                <label for="question" class="placeholder">User Question</label>
                <input id="question" class="input" type="text" value="<?php echo $queryData['description']; ?>" disabled>
            </div>
            <div class="input-container">
                <label for="admin_reply" class="placeholder">Admin Reply</label>
                <textarea id="admin_reply" class="input" name="admin_reply" placeholder=" "></textarea>
            </div>
            <button type="submit" class="submit">Submit</button>
        </form>
    </div>
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
    window.location.href = 'admin_help.php'; // Adjust the path as needed
    });
</script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>
