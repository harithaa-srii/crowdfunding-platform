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

// Check if the update form is submitted
if (isset($_POST['update_ngo'])) {
    $ngo_id = $_POST['ngo_id'];
    $ngo_user_name = $_POST['ngo_user_name'];
    $ngo_user_position = $_POST['ngo_user_position'];
    $ngo_user_phone = $_POST['ngo_user_phone'];
    $ngo_user_email = $_POST['ngo_user_email'];
    $ngo_user_pwd = $_POST['ngo_user_pwd'];
    $ngo_org_name = $_POST['ngo_org_name'];
    $ngo_org_place = $_POST['ngo_org_place'];
    $ngo_org_phone = $_POST['ngo_org_phone'];
    $ngo_org_mail = $_POST['ngo_org_mail'];
    $childhood_pet = $_POST['childhood_pet'];
    $vacation_destination = $_POST['vacation_destination'];
    $favourite_hobby = $_POST['favourite_hobby'];

    $sql = "UPDATE ngo_details SET 
            ngo_user_name='$ngo_user_name', 
            ngo_user_position='$ngo_user_position', 
            ngo_user_phone='$ngo_user_phone', 
            ngo_user_email='$ngo_user_email', 
            ngo_user_pwd='$ngo_user_pwd', 
            ngo_org_name='$ngo_org_name', 
            ngo_org_place='$ngo_org_place', 
            ngo_org_phone='$ngo_org_phone', 
            ngo_org_mail='$ngo_org_mail', 
            childhood_pet='$childhood_pet', 
            vacation_destination='$vacation_destination', 
            favourite_hobby='$favourite_hobby' 
            WHERE ngo_id='$ngo_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record updated successfully');</script>";
        echo "<script>window.location.href = 'admin_manage_ngo.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch NGO details based on ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $ngo_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM ngo_details WHERE ngo_id = '$ngo_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>NGO not found.</p>";
        exit; // Stop further execution if NGO not found
    }
} else {
    echo "<p>NGO ID not provided.</p>";
    exit; // Stop further execution if NGO ID not provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update NGO Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Show/hide password styles */
        .show-password {
            position: relative;
            margin-top: -10px;
            cursor: pointer;
        }

        .show-password input[type="checkbox"] {
            display: none;
        }

        .show-password label {
            font-size: 14px;
            cursor: pointer;
        }

        .show-password label::before {
            content: '\25BE';
            font-size: 12px;
            position: absolute;
            right: 0;
            top: 0;
            padding: 5px;
            color: #aaa;
        }

        .show-password input[type="checkbox"]:checked + label::before {
            content: '\25B4';
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update NGO Account</h2>
        <form method="post" action="update_ngo.php">
            <input type="hidden" name="ngo_id" value="<?php echo $row['ngo_id']; ?>">
            <div>
                <label for="ngo_user_name">Name:</label>
                <input type="text" name="ngo_user_name" id="ngo_user_name" value="<?php echo $row['ngo_user_name']; ?>">
            </div>
            <div>
                <label for="ngo_user_position">Position:</label>
                <input type="text" name="ngo_user_position" id="ngo_user_position" value="<?php echo $row['ngo_user_position']; ?>">
            </div>
            <div>
                <label for="ngo_user_phone">Phone:</label>
                <input type="text" name="ngo_user_phone" id="ngo_user_phone" value="<?php echo $row['ngo_user_phone']; ?>">
            </div>
            <div>
                <label for="ngo_user_email">Email:</label>
                <input type="email" name="ngo_user_email" id="ngo_user_email" value="<?php echo $row['ngo_user_email']; ?>">
            </div>
            <div>
                <label for="ngo_user_pwd">Password:</label>
                <input type="password" name="ngo_user_pwd" id="ngo_user_pwd" value="<?php echo $row['ngo_user_pwd']; ?>">
                <div class="show-password">
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label>
                </div>
            </div>
            <div>
                <label for="ngo_org_name">Organization Name:</label>
                <input type="text" name="ngo_org_name" id="ngo_org_name" value="<?php echo $row['ngo_org_name']; ?>">
            </div>
            <div>
                <label for="ngo_org_place">Organization Place:</label>
                <input type="text" name="ngo_org_place" id="ngo_org_place" value="<?php echo $row['ngo_org_place']; ?>">
            </div>
            <div>
                <label for="ngo_org_phone">Organization Phone:</label>
                <input type="text" name="ngo_org_phone" id="ngo_org_phone" value="<?php echo $row['ngo_org_phone']; ?>">
            </div>
            <div>
                <label for="ngo_org_mail">Organization Email:</label>
                <input type="email" name="ngo_org_mail" id="ngo_org_mail" value="<?php echo $row['ngo_org_mail']; ?>">
            </div>
            <div>
                <label for="childhood_pet">Childhood Pet:</label>
                <input type="text" name="childhood_pet" id="childhood_pet" value="<?php echo $row['childhood_pet']; ?>">
            </div>
            <div>
                <label for="vacation_destination">Vacation Destination:</label>
                <input type="text" name="vacation_destination" id="vacation_destination" value="<?php echo $row['vacation_destination']; ?>">
            </div>
            <div>
                <label for="favourite_hobby">Favourite Hobby:</label>
                <input type="text" name="favourite_hobby" id="favourite_hobby" value="<?php echo $row['favourite_hobby']; ?>">
            </div>
            <button type="submit" name="update_ngo">Update NGO</button>
        </form>
    </div>

    <script>
        // JavaScript for show/hide password
        const passwordField = document.getElementById("ngo_user_pwd");
        const showPasswordCheckbox = document.getElementById("showPassword");

        showPasswordCheckbox.addEventListener("change", function () {
            if (this.checked) {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        });
    </script>
</body>
</html>
