<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "crowdfund"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the password change form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    // Handle password change process
    $email = $_POST["email"];
    $username = $_POST["username"];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure new password matches confirm password
    if ($new_password === $confirm_password) {
        // Update password in the database
        $sql = "UPDATE donor_details SET donor_user_pwd='$new_password' WHERE donor_user_email='$email' AND donor_user_name='$username'";
        if ($conn->query($sql) === TRUE) {
            echo "Password updated successfully";
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        echo "New password and confirm password do not match.";
    }

    // Close connection
    $conn->close();
    exit; // Exit the script after handling password change process
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];

    // Retrieve user details from database based on username and email
    $sql = "SELECT * FROM donor_details WHERE donor_user_email='$email' AND donor_user_name='$username'";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        // Handle query error
        echo "Error: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        // Handle successful query result
        $row = $result->fetch_assoc();

        // Check if the answers are correct
        $answers = array(
            "childhood_pet" => isset($_POST["childhood_pet"]) ? $_POST["childhood_pet"] : "",
            "vacation_destination" => isset($_POST["vacation_destination"]) ? $_POST["vacation_destination"] : "",
            "favourite_hobby" => isset($_POST["favourite_hobby"]) ? $_POST["favourite_hobby"] : ""
        );

        // Retrieve correct answers from the database
        $correct_answers = array(
            "childhood_pet" => $row["childhood_pet"],
            "vacation_destination" => $row["vacation_destination"],
            "favourite_hobby" => $row["favourite_hobby"]
        );

        $correct = true;
        foreach ($correct_answers as $key => $value) {
            if ($answers[$key] != $value) {
                $correct = false;
                break;
            }
        }

        if ($correct) {
            // Display password change form
            echo '<!DOCTYPE html>
                  <html lang="en">
                  <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Change Password</title>
                  <link rel="stylesheet" href="forgot_pwd_style.css">
                  </head>
                  <body>
                  <div class="login-box">
                      <h2>Change Password</h2>
                      <form method="post" action="' . $_SERVER["PHP_SELF"] . '">
                          <input type="hidden" name="email" value="' . $email . '">
                          <input type="hidden" name="username" value="' . $username . '">
                          <div class="user-box">
                              <input type="password" name="new_password" required>
                              <label>New Password</label>
                          </div>
                          <div class="user-box">
                              <input type="password" name="confirm_password" required>
                              <label>Confirm New Password</label>
                          </div>
                          <input type="submit" value="Change Password">
                      </form>
                  </div>
                  </body>
                  </html>';
            // Exit the script to prevent displaying the account recovery form again
            exit;
        }
    } else {
        // No matching user found
        echo "No user found with the provided username and email.";
    }
}

// Display account recovery form
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Recovery</title>
<link rel="stylesheet" href="forgot_pwd_style.css">
</head>
<body>
<div class="login-box">
    <h2>Account Recovery</h2>
    <?php
    if (isset($correct) && !$correct) {
        // Display error message if answers are incorrect
        echo '<div class="error">The answers submitted are wrong. Try Again.</div>';
    }
    ?>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="user-box">
            <input type="text" name="username" required>
            <label>Username</label>
        </div>
        <div class="user-box">
            <input type="text" name="email" required>
            <label>Email</label>
        </div>
        <div class="user-box">
            <input type="text" name="childhood_pet" required>
            <label>What was the name of your childhood pet?</label>
        </div>
        <div class="user-box">
            <input type="text" name="vacation_destination" required>
            <label>What is your dream vacation destination?</label>
        </div>
        <div class="user-box">
            <input type="text" name="favourite_hobby" required>
            <label>What is your favorite hobby?</label>
        </div>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
