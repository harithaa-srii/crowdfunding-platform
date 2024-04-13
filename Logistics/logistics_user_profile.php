<?php
session_start();

// Check if Logistics user is logged in
if (!isset($_SESSION['logistics_id'])) {
    header("Location: logistics_login.php");
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

// Get Logistics user ID
$logistics_id = $_SESSION['logistics_id'];

// Fetch Logistics details from the database
$sql = "SELECT * FROM logistics_details WHERE logistics_id = '$logistics_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Extract Logistics details
    $logistics_user_name = $row['logistics_user_name'];
    $logistics_user_position = $row['logistics_user_position']; // Corrected this line
    $logistics_user_phone = $row['logistics_user_phone'];
    $logistics_user_email = $row['logistics_user_email'];
    $logistics_user_pwd = $row['logistics_user_pwd'];
    $logistics_org_name = $row["logistics_org_name"]; // Corrected this line
    $logistics_org_place = $row["logistics_org_place"]; // Corrected this line
    $logistics_org_phone = $row["logistics_org_phone"]; // Corrected this line
    $logistics_org_mail = $row["logistics_org_mail"]; // Corrected this line
    $childhood_pet = $row["childhood_pet"];
    $vacation_destination = $row["vacation_destination"];
    $favourite_hobby = $row["favourite_hobby"];
} else {
    echo "No Logistics details found!";
}

// Update Logistics details if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $logistics_user_name = $_POST['logistics_user_name'];
    $logistics_user_position = $_POST['logistics_user_position'];
    $logistics_user_phone = $_POST['logistics_user_phone'];
    $logistics_user_email = $_POST['logistics_user_email'];
    $logistics_user_pwd = $row['logistics_user_pwd'];
    $logistics_org_name = $_POST["logistics_org_name"];
    $logistics_org_place = $_POST["logistics_org_place"];
    $logistics_org_phone = $_POST["logistics_org_phone"];
    $logistics_org_mail = $_POST["logistics_org_mail"];
    $childhood_pet = $_POST["childhood_pet"];
    $vacation_destination = $_POST["vacation_destination"];
    $favourite_hobby = $_POST["favourite_hobby"];

    // Prepare SQL statement to update Logistics details
    $sql = "UPDATE logistics_details SET 
            logistics_user_name = '$logistics_user_name',
            logistics_user_position = '$logistics_user_position', 
            logistics_user_phone = '$logistics_user_phone', 
            logistics_user_email = '$logistics_user_email', 
            logistics_user_pwd = '$logistics_user_pwd',
            logistics_org_name = '$logistics_org_name',
            logistics_org_place = '$logistics_org_place',
            logistics_org_phone = '$logistics_org_phone', 
            logistics_org_mail = '$logistics_org_mail', 
            childhood_pet = '$childhood_pet', 
            vacation_destination = '$vacation_destination', 
            favourite_hobby = '$favourite_hobby' 
            WHERE logistics_id = '$logistics_id'";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        $_SESSION['profile_updated'] = true; // Set a session variable to indicate profile update
        header("Location: logistics_landing.php");
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
    <title>Logistics Profile</title>
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
    </style>
</head>

<body>
    <div class="container">
        <h2 class="main-heading">Logistics Profile</h2>
        <form id="profile-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="logistics_id">Logistics User ID:</label>
            <input type="text" id="logistics_id" name="logistics_id" class="form-control" value="<?php echo $logistics_id; ?>" disabled>
        </div>
            <div class="form-group">
                <label for="logistics_user_name">User Name</label>
                <input type="text" class="form-control" id="logistics_user_name" name="logistics_user_name" value="<?php echo $logistics_user_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_user_position">User Position</label>
                <input type="text" class="form-control" id="logistics_user_position" name="logistics_user_position" value="<?php echo $logistics_user_position; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_user_phone">User Phone</label>
                <input type="text" class="form-control" id="logistics_user_phone" name="logistics_user_phone" value="<?php echo $logistics_user_phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_user_email">User Email</label>
                <input type="email" class="form-control" id="logistics_user_email" name="logistics_user_email" value="<?php echo $logistics_user_email; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_user_pwd">Password:</label>
                <input type="password" id="logistics_user_pwd" name="logistics_user_pwd" class="form-control" value="<?php echo $logistics_user_pwd; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_org_name">Organization Name</label>
                <input type="text" class="form-control" id="logistics_org_name" name="logistics_org_name" value="<?php echo $logistics_org_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_org_place">Location</label>
                <input type="text" class="form-control" id="logistics_org_place" name="logistics_org_place" value="<?php echo $logistics_org_place; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_org_phone">Organization Phone</label>
                <input type="text" class="form-control" id="logistics_org_phone" name="logistics_org_phone" value="<?php echo $logistics_org_phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="logistics_org_email">Organization Email</label>
                <input type="email" class="form-control" id="logistics_org_email" name="logistics_org_email" value="<?php echo $logistics_org_mail; ?>" required> <!-- Corrected this line -->
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
            fetch("logistics_user_profile.php", {
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
</body>

</html>
