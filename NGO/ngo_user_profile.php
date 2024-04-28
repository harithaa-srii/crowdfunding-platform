<?php
session_start();

// Check if NGO user is logged in
if (!isset($_SESSION['ngo_id'])) {
    header("Location: ngo_login.php");
    exit();
}

// Database connection parameters
$servername = "localhost"; // Change this if your MySQL server is on a different host
$username = "root";
$password = "";
$database = "crowdfund";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get NGO user ID
$ngo_id = $_SESSION['ngo_id'];

// Fetch NGO details from the database
$sql = "SELECT * FROM ngo_details WHERE ngo_id = '$ngo_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Extract NGO details
    $ngo_user_name = $row['ngo_user_name'];
    $ngo_user_position = $row['ngo_user_position'];
    $ngo_user_phone = $row['ngo_user_phone'];
    $ngo_user_email = $row['ngo_user_email'];
    $ngo_user_pwd = $row['ngo_user_pwd'];
    $ngo_org_name = $row['ngo_org_name'];
    $ngo_org_place = $row['ngo_org_place'];
    $ngo_org_phone = $row['ngo_org_phone'];
    $ngo_org_mail = $row['ngo_org_mail'];
    $childhood_pet = $row['childhood_pet'];
    $vacation_destination = $row['vacation_destination'];
    $favourite_hobby = $row['favourite_hobby'];
} else {
    echo "No NGO details found!";
}

// Update NGO details if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
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

    // Prepare SQL statement to update NGO details
    $sql = "UPDATE ngo_details SET 
            ngo_user_name = '$ngo_user_name', 
            ngo_user_position = '$ngo_user_position', 
            ngo_user_phone = '$ngo_user_phone', 
            ngo_user_email = '$ngo_user_email', 
            ngo_user_pwd = '$ngo_user_pwd', 
            ngo_org_name = '$ngo_org_name', 
            ngo_org_place = '$ngo_org_place', 
            ngo_org_phone = '$ngo_org_phone', 
            ngo_org_mail = '$ngo_org_mail', 
            childhood_pet = '$childhood_pet', 
            vacation_destination = '$vacation_destination', 
            favourite_hobby = '$favourite_hobby' 
            WHERE ngo_id = '$ngo_id'";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        $_SESSION['profile_updated'] = true; // Set a session variable to indicate profile update
        header("Location: ngo_landing.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Profile</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <!-- Include Bootstrap CSS and any other necessary CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- Include your custom CSS if any -->
    <style>
        /* Custom CSS for styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #cdc1ff;
            background-image: linear-gradient(316deg, #cdc1ff 0%, #e5d9f2 74%);
            padding: 20px;
        }
         
        .container {
            max-width: 700px;
            width: 100%;
            background-color: #fff;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .main-heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }

        .btn-primary:hover {
            background-color: #5b47d6;
            border-color: #5b47d6;
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

</div>
<div class="back-button">
  <div class="arrow-wrap">
    <span class="arrow-part-1"></span>
    <span class="arrow-part-2"></span>
    <span class="arrow-part-3"></span>
  </div>
</div>
    <div class="container">
        <h2 class="main-heading">NGO Profile</h2>
        <form id="profile-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="ngo_id">NGO User ID:</label>
            <input type="text" id="ngo_id" name="ngo_id" class="form-control" value="<?php echo $ngo_id; ?>" disabled>
        </div>
            <div class="form-group">
                <label for="ngo_user_name">User Name</label>
                <input type="text" class="form-control" id="ngo_user_name" name="ngo_user_name" value="<?php echo $ngo_user_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_user_position">User Position</label>
                <input type="text" class="form-control" id="ngo_user_position" name="ngo_user_position" value="<?php echo $ngo_user_position; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_user_phone">User Phone</label>
                <input type="text" class="form-control" id="ngo_user_phone" name="ngo_user_phone" value="<?php echo $ngo_user_phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_user_email">User Email</label>
                <input type="email" class="form-control" id="ngo_user_email" name="ngo_user_email" value="<?php echo $ngo_user_email; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_user_pwd">Password:</label>
                <input type="password" id="ngo_user_pwd" name="ngo_user_pwd" class="form-control" value="<?php echo $ngo_user_pwd; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_org_name">Organization Name</label>
                <input type="text" class="form-control" id="ngo_org_name" name="ngo_org_name" value="<?php echo $ngo_org_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_org_place">Organization Place</label>
                <input type="text" class="form-control" id="ngo_org_place" name="ngo_org_place" value="<?php echo $ngo_org_place; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_org_phone">Organization Phone</label>
                <input type="text" class="form-control" id="ngo_org_phone" name="ngo_org_phone" value="<?php echo $ngo_org_phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="ngo_org_mail">Organization Email</label>
                <input type="email" class="form-control" id="ngo_org_mail" name="ngo_org_mail" value="<?php echo $ngo_org_mail; ?>" required>
            </div>
            <div class="form-group">
                <label for="childhood_pet">What was the name of your childhood pet?</label>
                <input type="text" class="form-control" id="childhood_pet" name="childhood_pet" value="<?php echo $childhood_pet; ?>" required>
            </div>
            <div class="form-group">
                <label for="vacation_destination">What is your dream vacation destination?</label>
                <input type="text" class="form-control" id="vacation_destination" name="vacation_destination" value="<?php echo $vacation_destination; ?>" required>
            </div>
            <div class="form-group">
                <label for="favourite_hobby">What is your favorite hobby?</label>
                <input type="text" class="form-control" id="favourite_hobby" name="favourite_hobby" value="<?php echo $favourite_hobby; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
    <script>
        document.getElementById("profile-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Fetch the form data
            var formData = new FormData(this);

            // Send an AJAX request to the server
            fetch("ngo_user_profile.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Display the response message
                    alert("Profile updated successfully!");
                })
                .catch(error => {
                    console.error("Error:", error);
                });
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
            window.location.href = 'ngo_landing.php'; // Adjust the path as needed
            });

</script>
</body>

</html>
