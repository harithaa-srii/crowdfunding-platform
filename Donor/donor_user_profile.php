<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['donor_user_id'])) {
    header("Location: donor-login.php");
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

// Get donor user ID
$donor_user_id = $_SESSION['donor_user_id'];

// Fetch donor details from the database
$sql = "SELECT * FROM donor_details WHERE donor_user_id = '$donor_user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Extract donor details
    $fullName = $row['donor_user_name'];
    $email = $row['donor_user_email'];
    $phone = $row['donor_user_phone'];
    $streetAddress = $row['donor_user_street'];
    $city = $row['donor_user_city'];
    $state = $row['donor_user_state'];
    $zipCode = $row['donor_user_zip'];
    $country = $row['donor_user_country'];
    $password = $row['donor_user_pwd'];
    $dob = $row['dob'];
    $gender = $row['donor_user_gender'];
    $fundingType = $row['fundingType'];
    $frequency = $row['frequency'];
    $donationAmount = $row['donationAmount'];
    $paymentType = $row['paymentType'];
    $upiId = $row['upiId'];
    $cardNumber = $row['cardNumber'];
    $expiry = $row['expiry'];
    $cvCode = $row['cvCode'];
    $childhoodPet = $row['childhood_pet'];
    $vacationDestination = $row['vacation_destination'];
    $favouriteHobby = $row['favourite_hobby'];
} else {
    echo "No donor details found!";
}

// Update donor details if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipCode'];
    $country = $_POST['country'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $fundingType = $_POST['fundingType'];
    $frequency = isset($_POST['frequency']) ? $_POST['frequency'] : NULL;
    $donationAmount = isset($_POST['amount']) ? $_POST['amount'] : NULL;
    $paymentType = $_POST['paymentType'];
    $upiId = isset($_POST['upiId']) ? $_POST['upiId'] : NULL;
    $cardNumber = isset($_POST['cardNumber']) ? $_POST['cardNumber'] : NULL;
    $expiry = isset($_POST['expiry']) ? $_POST['expiry'] : NULL;
    $cvCode = isset($_POST['cvCode']) ? $_POST['cvCode'] : NULL;
    $childhoodPet = $_POST['childhood_pet'];
    $vacationDestination = $_POST['vacation_destination'];
    $favouriteHobby = $_POST['favourite_hobby'];

    // Prepare SQL statement to update donor details
    $sql = "UPDATE donor_details SET 
            donor_user_name = '$fullName', 
            donor_user_email = '$email', 
            donor_user_phone = '$phone', 
            donor_user_street = '$streetAddress', 
            donor_user_city = '$city', 
            donor_user_state = '$state', 
            donor_user_zip = '$zipCode', 
            donor_user_country = '$country', 
            donor_user_pwd = '$password', 
            dob = '$dob', 
            donor_user_gender = '$gender', 
            fundingType = '$fundingType', 
            frequency = '$frequency', 
            donationAmount = '$donationAmount', 
            paymentType = '$paymentType', 
            upiId = '$upiId', 
            cardNumber = '$cardNumber', 
            expiry = '$expiry', 
            cvCode = '$cvCode', 
            childhood_pet = '$childhoodPet', 
            vacation_destination = '$vacationDestination', 
            favourite_hobby = '$favouriteHobby' 
            WHERE donor_user_id = '$donor_user_id'";

    // Execute SQL statement
if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Profile updated successfully!");</script>';
    // Redirect to donor_landing.php
    header("Location: donor_landing.php");
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
    <title>Donor Profile</title>
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
            font-size: 30px;
        }

        .panel-title {
            font-size: 25px;
            font-weight: 500;
            position: relative;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .toggle-password {
            position: absolute;
            top: 74%;
            left: 60%;
            color: #000;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #555;
        }


        .form-group label {
            font-weight:bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="number"],
        .form-group input[type="password"],
        .form-group select {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="tel"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="password"]:focus,
        .form-group select:focus {
            border-color: #9b59b6;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            height: 45px;
            margin: 35px 0;
            width: 100%;
            border-radius: 5px;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(-135deg, #71b7e6, #9b59b6);
        }


        .security-questions {
            margin-bottom: 10px; /* Adjust the margin as needed */
        }


        .security-questions p {
            font-size: 18px; /* Adjust the font size as needed */
        }


        @keyframes swap-out {
            0% {
                transform: translateX(0);
                opacity: 1;
            }

            50% {
                transform: translateX(50%);
                opacity: 0;
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Additional styles for input fields */
        .input-field {
            position: relative;
        }

        .input-field input {
            width: calc(50% - 15px);
            height: 60px;
            border-radius: 6px;
            font-size: 18px;
            padding: 0 15px;
            border: 2px solid #212121;
            background: transparent;
            color: #212121;
            outline: none;
        }

        .input-field input:focus {
            border: 2px solid #780d9b;
        }

        .input-field input:focus~label,
        .input-field input:valid~label {
            top: 0;
            left: 15px;
            font-size: 16px;
            padding: 0 2px;
            background: #ffffff;
        }

        /* Flashing note style */
        .note-flash {
            border: 1px solid white;
            border-radius: 5px;
            padding: 5px 10px;
            color: #4CAF50;
            animation: flash-note 1s infinite;
        }

        @keyframes flash-note {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
   
    <div class="container">
        <h1 class="main-heading">Donor Profile</h1>
        <form id="profile-form" action="donor_user_profile.php" method="post">
        <div class="form-group">
            <label for="donor_user_id">Donor User ID:</label>
            <input type="text" id="donor_user_id" name="donor_user_id" class="form-control" value="<?php echo $donor_user_id; ?>" disabled>
        </div>
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" class="form-control" value="<?php echo $fullName; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo $phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="streetAddress">Street Address:</label>
                <input type="text" id="streetAddress" name="streetAddress" class="form-control" value="<?php echo $streetAddress; ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php echo $city; ?>" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" class="form-control" value="<?php echo $state; ?>" required>
            </div>
            <div class="form-group">
                <label for="zipCode">Zip Code:</label>
                <input type="text" id="zipCode" name="zipCode" class="form-control" value="<?php echo $zipCode; ?>" required>
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" class="form-control" value="<?php echo $country; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>" required>
                <i class="fas fa-eye toggle-password"></i>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $dob; ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fundingType">Funding Type:</label>
                <select id="fundingType" name="fundingType" class="form-control" required>
                    <option value="Fixed" <?php if ($fundingType == 'fixed') echo 'selected'; ?>>Fixed</option>
                    <option value="Flexible" <?php if ($fundingType == 'flexible') echo 'selected'; ?>>Flexible</option>
                </select>
            </div>
            <div class="form-group">
                <label for="frequency">Frequency:</label>
                <select id="frequency" name="frequency" class="form-control" required>
                    <option value="Weekly" <?php if ($frequency == 'Weekly') echo 'selected'; ?>>Weekly</option>
                    <option value="Monthly" <?php if ($frequency == 'Monthly') echo 'selected'; ?>>Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Donation Amount:</label>
                <input type="number" id="amount" name="amount" class="form-control" value="<?php echo $donationAmount; ?>">
            </div>
            <div class="form-group">
                <label for="paymentType">Payment Type:</label>
                <select id="paymentType" name="paymentType" class="form-control" required>
                    <option value="UPI" <?php if ($paymentType == 'UPI') echo 'selected'; ?>>UPI</option>
                    <option value="Card" <?php if ($paymentType == 'Card') echo 'selected'; ?>>Card</option>
                </select>
            </div>
            <div class="form-group">
                <label for="upiId">UPI ID:</label>
                <input type="text" id="upiId" name="upiId" class="form-control" value="<?php echo $upiId; ?>">
            </div>
            <div class="form-group">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" class="form-control" value="<?php echo $cardNumber; ?>">
            </div>
            <div class="form-group">
                <label for="expiry">Expiry Date:</label>
                <input type="text" id="expiry" name="expiry" class="form-control" value="<?php echo $expiry; ?>">
            </div>
            <div class="form-group">
                <label for="cvCode">CV Code:</label>
                <input type="text" id="cvCode" name="cvCode" class="form-control" value="<?php echo $cvCode; ?>">
            </div>
            <div class="form-group">
                <label for="childhood_pet">Childhood Pet:</label>
                <input type="text" id="childhood_pet" name="childhood_pet" class="form-control" value="<?php echo $childhoodPet; ?>" required>
            </div>
            <div class="form-group">
                <label for="vacation_destination">Vacation Destination:</label>
                <input type="text" id="vacation_destination" name="vacation_destination" class="form-control" value="<?php echo $vacationDestination; ?>" required>
            </div>
            <div class="form-group">
                <label for="favourite_hobby">Favourite Hobby:</label>
                <input type="text" id="favourite_hobby" name="favourite_hobby" class="form-control" value="<?php echo $favouriteHobby; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
</div>
<script>
        const passwordInput = document.getElementById('user_password');
        const togglePassword = document.querySelector('.toggle-password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
</script>
<script>
        document.getElementById("profile-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Fetch the form data
            var formData = new FormData(this);

            // Send an AJAX request to the server
            fetch("donor_user_profile.php", {
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
            