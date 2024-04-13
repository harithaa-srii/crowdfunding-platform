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

// Function to fetch Public details based on its ID
function getPCHById($lchID)
{
    global $conn;

    $query = "SELECT * FROM logistics_queries WHERE lch_id = '$lchID'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to update PCH details based on its ID
function updatePCH($lchID, $userData)
{
    global $conn;

    // Create an array to store the update statements
    $updateStatements = array();

    // Prepare the update statements for each key-value pair
    foreach ($userData as $key => $value) {
        $updateStatements[] = "$key = '$value'";
    }

    // Implode the update statements to form the complete query
    $updateQuery = "UPDATE logistics_queries SET " . implode(", ", $updateStatements) . " WHERE lch_id = '$lchID';";

    if ($conn->query($updateQuery) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the Public ID from the form submission
    $lchID = $_POST["lch_id"];

    // Get the user and organization details from the form submission
    $userData = array(
        "lch_user_name" => $_POST["lch_user_name"],
        "lch_user_phone" => $_POST["lch_user_phone"],
        "lch_user_email" => $_POST["lch_user_email"],
        "lch_desc" => $_POST["lch_desc"],
        "lch_date" => $_POST["lch_date"],
        "lch_admin_reply" => $_POST["lch_admin_reply"]
    );


    // Update the Public details in the database
    $updateResult = updatePCH($lchID, $userData);

    // Check if the update was successful
    if ($updateResult) {
        // Redirect back to the admin_Public_manage.php page with a success message
        header("Location: admin_help.php?message=success");
        exit();
    } else {
        // Redirect back to the admin_Public_manage.php page with an error message
        header("Location: admin_help.php?message=error");
        exit();
    }
}

// Check if the Public ID is provided in the URL
if (isset($_GET["id"])) {
    $lchID = $_GET["id"];
    // Fetch the Public details based on its ID
    $lchDetails = getPCHById($lchID);

    if (!$lchDetails) {
        // If the Public ID is not found, redirect back to admin_Public_manage.php
        header("Location: admin_help.php");
        exit();
    }
} else {
    // If the Public ID is not provided in the URL, redirect back to admin_Public_manage.php
    header("Location: admin_help.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Answer - User Questions</title>
    <link rel="icon" href="../images/urbanlink-logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-image: -webkit-repeating-linear-gradient(135deg, transparent, #101010 25%, #737373 25%, transparent 50%, transparent 50%), -webkit-repeating-radial-gradient(#616161, #222222);
            background-image: repeating-linear-gradient(-45deg, transparent, #101010 25%, #737373 25%, transparent 50%, transparent 50%);
            background-size: 6px 6px;
            background-color: #444;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 10px auto;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            height: 650px;

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
        }

        .back-button {
            position: relative;
            top: 20px;
            margin-left: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            position: relative;
            top: -30px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            position: relative;
            top: -30px;
        }

        label {
            font-weight: bold;
            width: 200px;
            position: relative;
            left: -150px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        textarea {
            width: 80%;
            padding: 8px;
            margin-bottom: 15px;
    border: 2px solid white;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #007bff;
            width: 80%;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: cadetblue;
        }

        @media only screen and (min-width:601px) and (max-width: 900px) {
            .container {
                /* margin: 20px; */
                width: 80%;
                height: 800px;
            }

            form {
                max-width: 100%;
            }
        }

        @media only screen and (min-width:300px) and (max-width: 600px) {
            .container {
                position: relative;
                top: 25px;
                width: 80%;
                height: 650px;
                padding: 10px;
            }

            form {
                max-width: 100%;
            }

            form {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                position: relative;
                top: -30px;
            }

            label {
                font-weight: bold;
                width: 200px;
                position: relative;
                left: -50px;
            }
        }
    </style>
</head>

<body>
    <div class="back-button">
        <a href="admin_help.php" class="go-back">⬅️</a>
    </div>
    <div class="container">
        <h1>Answer Questions From Logistics</h1>
        <form method="post" action="">
            <input type="hidden" name="lch_id" value="<?php echo $lchID; ?>">
            <label for="lch_user_name">Logistics User Name:</label>
            <input type="text" name="lch_user_name" value="<?php echo $lchDetails["lch_user_name"]; ?>" readonly><br>

            <label for="lch_user_phone">Logistics User Phone:</label>
            <input type="tel" name="lch_user_phone" value="<?php echo $lchDetails["lch_user_phone"]; ?>" readonly><br>

            <label for="lch_user_email">NGO User Email:</label>
            <input type="email" name="lch_user_email" value="<?php echo $lchDetails["lch_user_email"]; ?>" readonly> <br>

            <label for="lch_desc">Question:</label>
            <input type="text" name="lch_desc" value="<?php echo $lchDetails["lch_desc"]; ?>" readonly><br>

            <label for="lch_date">Date Question Asked:</label>
            <input type="date" name="lch_date" value="<?php echo $lchDetails["lch_date"]; ?>" readonly><br>

            <label for="lch_admin_reply">Admin Reply:</label>
            <textarea id="lch_admin_reply" name="lch_admin_reply" rows="5" placeholder="Admin Reply here!!!" value="<?php echo $lchDetails["lch_admin_reply"]; ?>" required></textarea>


            <input type="submit" value="Update Reply">
        </form>
    </div>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>