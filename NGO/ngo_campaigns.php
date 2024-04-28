<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crowdfund";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user ID and username from session or authentication
    // Here, I assume you have some kind of authentication or session management
    // where you can get the user ID and username.
    // For now, I'll fetch the first user's details from the `ngo_details` table.
    $sql_user = "SELECT ngo_id, ngo_user_name FROM ngo_details LIMIT 1";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $user_data = $result_user->fetch_assoc();
        $userId = $user_data["ngo_id"];
        $username = $user_data["ngo_user_name"];
    } else {
        // If no user found, you may handle this situation accordingly.
        $userId = "User ID";
        $username = "Username";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Campaign</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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
        <h1>Create Campaign</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="userId">User ID:</label><br>
            <input type="text" id="userId" name="userId" value="<?php echo $userId; ?>" readonly><br>
            
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly><br>

            <!-- Other form fields -->
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br>
            
            <label for="keywords">Keywords:</label><br>
            <input type="text" id="keywords" name="keywords"><br>
            
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>
            
            <label for="goal">Goal:</label><br>
            <input type="number" id="goal" name="goal" required><br>
            
            <label for="duration">Duration (in days):</label><br>
            <input type="number" id="duration" name="duration" required><br>
            
            <input type="submit" value="Create Campaign">
        </form>
    </div>
    
    <?php
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $userId = $_POST['userId'];
        $username = $_POST['username'];
        $title = $_POST['title'];
        $keywords = $_POST['keywords'];
        $description = $_POST['description'];
        $goal = $_POST['goal'];
        $duration = $_POST['duration'];

        // Generate a random alphanumeric ID for the campaign
        function generateRandomID($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
        $id = generateRandomID();

        // SQL query to insert campaign data into the database
        $sql = "INSERT INTO campaigns (id, user_id, username, title, keywords, description, goal, duration) VALUES ('$id', '$userId', '$username', '$title', '$keywords', '$description', $goal, $duration)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Campaign created successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
    ?>
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
            window.location.href = 'ngo_landing.php'; // Adjust the path as needed
            });

</script>
</body>
</html>















