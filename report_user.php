<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crowdfund";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve reporting user details from session if available
    $reportingUserID = $_SESSION['user_id'] ?? '';
    $reportingUserName = $_SESSION['username'] ?? '';
    $reportingUserPhone = $_SESSION['user_phone'] ?? '';
    $reportingUserEmail = $_SESSION['user_email'] ?? '';

    // Retrieve reported user details from POST data if available
    $reportedUserID = isset($_POST['reported_user_id']) ? $_POST['reported_user_id'] : '';
    $reportedUserName = isset($_POST['reported_user_name']) ? $_POST['reported_user_name'] : '';
    $reportedUserPhone = isset($_POST['reported_user_phone']) ? $_POST['reported_user_phone'] : '';
    $reportedUserEmail = isset($_POST['reported_user_email']) ? $_POST['reported_user_email'] : '';

    // Validate form data
    $reportType = $_POST['report_type'] ?? '';
    $reportDescription = $_POST['report_description'] ?? '';

    if (empty($reportingUserID) || empty($reportedUserID) || empty($reportType) || empty($reportDescription)) {
        echo "Error: Please fill in all required fields.";
        exit();
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO UserReports (ReportID, ReportingUserID, ReportingUserName, ReportingUserPhone, ReportingUserEmail, ReportedUserID, ReportedUserName, ReportedUserPhone, ReportedUserEmail, ReportType, ReportDescription, ReportDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $reportID, $reportingUserID, $reportingUserName, $reportingUserPhone, $reportingUserEmail, $reportedUserID, $reportedUserName, $reportedUserPhone, $reportedUserEmail, $reportType, $reportDescription, $reportDate);

    // Generate a unique report ID
    $reportID = uniqid();

    // Get current date and time
    $reportDate = date('Y-m-d H:i:s');

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect to the landing page of the reporting user
        header("Location: landing_page.php"); // Replace with the correct landing page URL
        exit();
    } else {
        // Handle error
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="%23333"><path d="M7.406 9.422L12 13.997l4.594-4.575 1.406 1.405L12 16.808 5 10.828l1.406-1.406z"/></svg>');
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: center;
        }

        textarea {
            height: 120px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Report User</h2>
        <form id="reportForm" method="post">
            <label for="reporting_user_id">Reporting User ID:</label>
            <input type="text" id="reporting_user_id" name="reporting_user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" readonly required>

            <label for="reporting_user_name">Reporting User Name:</label>
            <input type="text" id="reporting_user_name" name="reporting_user_name" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly required>

            <label for="reporting_user_phone">Reporting User Phone:</label>
            <input type="text" id="reporting_user_phone" name="reporting_user_phone" value="<?php echo isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : ''; ?>" readonly required>

            <label for="reporting_user_email">Reporting User Email:</label>
            <input type="email" id="reporting_user_email" name="reporting_user_email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" readonly required>

            <label for="reported_user_id">Reported User ID:</label>
            <input type="text" id="reported_user_id" name="reported_user_id" value="<?php echo $reportedUserID; ?>" readonly required>

            <label for="reported_user_name">Reported User Name:</label>
            <input type="text" id="reported_user_name" name="reported_user_name" value="<?php echo $reportedUserName; ?>" readonly required>

            <label for="reported_user_phone">Reported User Phone:</label>
            <input type="text" id="reported_user_phone" name="reported_user_phone" value="<?php echo $reportedUserPhone; ?>" readonly required>

            <label for="reported_user_email">Reported User Email:</label>
            <input type="email" id="reported_user_email" name="reported_user_email" value="<?php echo $reportedUserEmail; ?>" readonly required>

            <label for="report_type">Report Type:</label>
            <select id="report_type" name="report_type" required>
                <option value="" disabled selected>Select report type</option>
                <option value="Spam">Spam</option>
                <option value="Abuse">Abuse</option>
                <option value="Inappropriate Content">Inappropriate Content</option>
                <option value="Fraud">Fraud</option>
                <option value="Impersonation">Impersonation</option>
                <option value="Privacy Violation">Privacy Violation</option>
                <option value="Fake Account">Fake Account</option>
                <option value="Cyber Bullying">Cyber Bullying</option>
                <option value="Phishing">Phishing</option>
                <option value="Identity Theft">Identity Theft</option>
                <option value="Child Exploitation">Child Exploitation</option>
                <option value="Stalking">Stalking</option>
                <option value="Online Harassment">Online Harassment</option>
                <option value="Scam">Scam</option>
                <option value="Malware">Malware</option>
                <option value="Hacking">Hacking</option>
                <option value="Fraudulent Activity">Fraudulent Activity</option>
                <option value="Hate Crime">Hate Crime</option>
                <option value="Violence">Violence</option>
                <option value="False Information">False Information</option>
                <option value="Other">Other</option>
            </select>

            <label for="report_description">Report Description:</label>
            <textarea id="report_description" name="report_description" required></textarea>

            <input type="submit" value="Submit Report">
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("reportForm");

            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default form submission

                var formData = new FormData(form);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "report_user.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert("Report submitted successfully");
                            form.reset(); // Reset the form
                        } else {
                            alert("Error: " + xhr.responseText);
                        }
                    }
                };

                xhr.send(new URLSearchParams(formData));
            });
        });
    </script>
</body>
</html>
