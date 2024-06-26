<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['donor_user_name']) || !isset($_SESSION['donor_user_email'])) {
        // Redirect to the login page
        header("Location: donor-login.php");
        exit();
    }

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form values
        $user_id = $_SESSION['donor_user_id']; // Change 'user_id' to 'donor_user_id'
        $username = $_SESSION['donor_user_name']; // Change 'username' to 'donor_user_name'
        $phoneNumber = $_POST['phoneNumber'];
        $email = $_POST['email'];
        $description = $_POST['description'];
        $date = $_POST['date'];

        // Connect to the database
        $servername = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbname = "crowdfund";

        try {
            // Create a PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Generate a unique query ID
            $queryID = uniqid();

            // Prepare and execute the query
            $stmt = $conn->prepare("INSERT INTO queries (query_id, user_id, user_name, user_phone, user_email, description, date_questioned) VALUES (:queryID, :user_id, :username, :phoneNumber,:email, :description, :date)");
            $stmt->bindParam(':queryID', $queryID);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->execute();

            // Display the success message and redirect after 3 seconds
            $successMessage = "QUESTION  SUBMITTED SUCCESSFULLY. QUESTION ID: $queryID";
            echo '<div id="popup" class="popup">
                    <h3>Success!</h3>
                    <p>' . $successMessage . '</p>
                </div>';
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "donor_landing.php";
                    }, 3000);
                </script>';
        } catch (PDOException $e) {
            // Handle database connection or query errors
            $errorMessage = "Database error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Help - Ask Question </title>
    <link rel="icon" href="../images/urbanlink-logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* CSS styles for the popup */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
        }

        .popup h3 {
            margin-top: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #AFDCEC;
            height: auto;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 10px;
            margin-top: 50px;
            backdrop-filter: blur(10px);
            border-radius: 5px;
            background-color: #f8f9fa;
            box-shadow: 0px 0px 10px rgba(1, 1, 1, 2);
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            position: relative;
            top: -30px;
        }

        .column {
            width: 70%;
            padding: 50px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 2px solid #cccccc;
            color: black;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #cccccc;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            display: block;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid white;
            border-radius: 10px;
            cursor: pointer;
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
        <div class="column">
            <h2>Question Request</h2>
            <form method="POST" action="donor_question_form.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="text" id="user_id" name="user_id" value="<?php echo isset($_SESSION['donor_user_id']) ? $_SESSION['donor_user_id'] : ''; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['donor_user_name']) ? $_SESSION['donor_user_name'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" oninput="validatePhoneNumber(this)" value="<?php echo isset($_SESSION['donor_user_phone']) ? $_SESSION['donor_user_phone'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Your Doubt 🤔:</label>
                    <textarea id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your E-mail" value="<?php echo isset($_SESSION['donor_user_email']) ? $_SESSION['donor_user_email'] : ''; ?>" required>
                </div>
                <div class=" form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group" style="text-align: center; width: 100%;">
                    <input type="submit" value="Submit Question">
                </div>
            </form>
        </div>
        <script>
            // Display the popup
            document.addEventListener("DOMContentLoaded", function() {
                var popup = document.getElementById("popup");
                popup.style.display = "block";
            });
        </script>
        <script>
            function validatePhoneNumber(input) {
                // Remove non-numeric characters
                input.value = input.value.replace(/\D/g, '');

                // Limit the length to 10 characters
                if (input.value.length > 10) {
                    input.value = input.value.slice(0, 10);
                }
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
            window.location.href = 'donor_faq.php'; // Adjust the path as needed
            });

</script>
</body>

</html>
