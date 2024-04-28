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
if (isset($_POST['update_logistics'])) {
    $logistics_id = $_POST['logistics_id'];
    $logistics_user_name = $_POST['logistics_user_name'];
    $logistics_user_position = $_POST['logistics_user_position'];
    $logistics_user_phone = $_POST['logistics_user_phone'];
    $logistics_user_email = $_POST['logistics_user_email'];
    $logistics_user_pwd = $_POST['logistics_user_pwd'];
    $logistics_org_name = $_POST['logistics_org_name'];
    $logistics_org_place = $_POST['logistics_org_place'];
    $logistics_org_phone = $_POST['logistics_org_phone'];
    $logistics_org_mail = $_POST['logistics_org_mail'];
    $childhood_pet = $_POST['childhood_pet'];
    $vacation_destination = $_POST['vacation_destination'];
    $favourite_hobby = $_POST['favourite_hobby'];

    $sql = "UPDATE logistics_details SET 
            logistics_user_name='$logistics_user_name', 
            logistics_user_position='$logistics_user_position', 
            logistics_user_phone='$logistics_user_phone', 
            logistics_user_email='$logistics_user_email', 
            logistics_user_pwd='$logistics_user_pwd', 
            logistics_org_name='$logistics_org_name', 
            logistics_org_place='$logistics_org_place', 
            logistics_org_phone='$logistics_org_phone', 
            logistics_org_mail='$logistics_org_mail', 
            childhood_pet='$childhood_pet', 
            vacation_destination='$vacation_destination', 
            favourite_hobby='$favourite_hobby' 
            WHERE logistics_id='$logistics_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record updated successfully');</script>";
        echo "<script>window.location.href = 'admin_manage_logistics.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch logistics details based on ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logistics_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM logistics_details WHERE logistics_id = '$logistics_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>User not found.</p>";
        exit; // Stop further execution if user not found
    }
} else {
    echo "<p>Logistics ID not provided.</p>";
    exit; // Stop further execution if logistics ID not provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Logistics Account</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
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
        <h2>Update Logistics Account</h2>
        <form method="post" action="update_logistics.php">
            <input type="hidden" name="logistics_id" value="<?php echo $row['logistics_id']; ?>">
            <div>
                <label for="logistics_user_name">Name:</label>
                <input type="text" name="logistics_user_name" id="logistics_user_name" value="<?php echo $row['logistics_user_name']; ?>">
            </div>
            <div>
                <label for="logistics_user_position">Position:</label>
                <input type="text" name="logistics_user_position" id="logistics_user_position" value="<?php echo $row['logistics_user_position']; ?>">
            </div>
            <div>
                <label for="logistics_user_phone">Phone:</label>
                <input type="text" name="logistics_user_phone" id="logistics_user_phone" value="<?php echo $row['logistics_user_phone']; ?>">
            </div>
            <div>
                <label for="logistics_user_email">Email:</label>
                <input type="email" name="logistics_user_email" id="logistics_user_email" value="<?php echo $row['logistics_user_email']; ?>">
            </div>
            <div>
                <label for="logistics_user_pwd">Password:</label>
                <input type="password" name="logistics_user_pwd" id="logistics_user_pwd" value="<?php echo $row['logistics_user_pwd']; ?>">
                <div class="show-password">
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label>
                </div>
            </div>
            <div>
                <label for="logistics_org_name">Organization Name:</label>
                <input type="text" name="logistics_org_name" id="logistics_org_name" value="<?php echo $row['logistics_org_name']; ?>">
            </div>
            <div>
                <label for="logistics_org_place">Organization Place:</label>
                <input type="text" name="logistics_org_place" id="logistics_org_place" value="<?php echo $row['logistics_org_place']; ?>">
            </div>
            <div>
                <label for="logistics_org_phone">Organization Phone:</label>
                <input type="text" name="logistics_org_phone" id="logistics_org_phone" value="<?php echo $row['logistics_org_phone']; ?>">
            </div>
            <div>
                <label for="logistics_org_mail">Organization Email:</label>
                <input type="email" name="logistics_org_mail" id="logistics_org_mail" value="<?php echo $row['logistics_org_mail']; ?>">
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
            <button type="submit" name="update_logistics">Update Logistics</button>
        </form>
    </div>

    <script>
        // JavaScript for show/hide password
        const passwordField = document.getElementById("logistics_user_pwd");
        const showPasswordCheckbox = document.getElementById("showPassword");

        showPasswordCheckbox.addEventListener("change", function () {
            if (this.checked) {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        });
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
    window.location.href = 'admin_manage_logistics.php'; // Adjust the path as needed
    });
</script>
</body>
</html>
