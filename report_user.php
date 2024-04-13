<?php
// submit_report.php

// Database connection parameters
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "crowdfund"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$reporting_user_id = $_POST['reporting_user_id'];
$reporting_user_name = $_POST['reporting_user_name'];
$reporting_user_phone = $_POST['reporting_user_phone'];
$reported_user_id = $_POST['reported_user_id'];
$reported_user_name = $_POST['reported_user_name'];
$reported_user_phone = $_POST['reported_user_phone'];
$reported_user_location = $_POST['reported_user_location'];
$report_type = $_POST['report_type'];

// Prepare SQL statement to insert data into the table
$sql = "INSERT INTO reported_users (id, reporting_user_id, reporting_user_name, reporting_user_phone, reported_user_id, reported_user_name, reported_user_phone, reported_user_location, report_type) 
        VALUES (UUID(), '$reporting_user_id', '$reporting_user_name', '$reporting_user_phone', '$reported_user_id', '$reported_user_name', '$reported_user_phone', '$reported_user_location', '$report_type')";

if ($conn->query($sql) === TRUE) {
    echo "Report submitted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
<!-- donor_report_user.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report User</title>
    <style>
        /* donor_report_style.css */
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-heading {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-heading">Report User</h2>
        <form action="submit_report.php" method="POST">
            <input type="hidden" name="reporting_user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"> <!-- Assuming the reporting user's ID is known -->
            <div class="form-group">
                <label for="reporting_user_name">Reporting User Name:</label>
                <input type="text" id="reporting_user_name" name="reporting_user_name" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="reporting_user_phone">Reporting User Phone:</label>
                <input type="tel" id="reporting_user_phone" name="reporting_user_phone" required>
            </div>
            <div class="form-group">
                <label for="reported_user_id">Reported User ID:</label>
                <input type="text" id="reported_user_id" name="reported_user_id" required>
            </div>
            <div class="form-group">
                <label for="reported_user_name">Reported User Name:</label>
                <input type="text" id="reported_user_name" name="reported_user_name" required>
            </div>
            <div class="form-group">
                <label for="reported_user_phone">Reported User Phone:</label>
                <input type="tel" id="reported_user_phone" name="reported_user_phone" required>
            </div>
            <div class="form-group">
                <label for="reported_user_location">Reported User Location:</label>
                <input type="text" id="reported_user_location" name="reported_user_location" required>
            </div>
            <div class="form-group">
                <label for="report_type">Report Type:</label>
                <input type="text" id="report_type" name="report_type" required>
            </div>
            <button type="submit" class="submit-button">Submit Report</button>
        </form>
    </div>
</body>
</html>
