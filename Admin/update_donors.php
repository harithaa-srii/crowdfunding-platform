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
if (isset($_POST['update_donor'])) {
    $donor_user_id = $_POST['donor_user_id'];
    $donor_user_name = $_POST['donor_user_name'];
    $donor_user_email = $_POST['donor_user_email'];
    $donor_user_phone = $_POST['donor_user_phone'];
    $donor_user_street = $_POST['donor_user_street'];
    $donor_user_city = $_POST['donor_user_city'];
    $donor_user_state = $_POST['donor_user_state'];
    $donor_user_zip = $_POST['donor_user_zip'];
    $donor_user_country = $_POST['donor_user_country'];
    $donor_user_pwd = $_POST['donor_user_pwd'];
    $donor_user_gender = $_POST['donor_user_gender'];
    $fundingType = $_POST['fundingType'];
    $donationAmount = $_POST['donationAmount'];
    $paymentType = $_POST['paymentType'];
    $upiId = $_POST['upiId'];
    $cardNumber = $_POST['cardNumber'];
    $expiry = $_POST['expiry'];
    $cvCode = $_POST['cvCode'];
    $childhood_pet = $_POST['childhood_pet'];
    $vacation_destination = $_POST['vacation_destination'];
    $favourite_hobby = $_POST['favourite_hobby'];

    $sql = "UPDATE donor_details SET 
            donor_user_name='$donor_user_name', 
            donor_user_email='$donor_user_email', 
            donor_user_phone='$donor_user_phone', 
            donor_user_street='$donor_user_street', 
            donor_user_city='$donor_user_city', 
            donor_user_state='$donor_user_state', 
            donor_user_zip='$donor_user_zip', 
            donor_user_country='$donor_user_country', 
            donor_user_pwd='$donor_user_pwd', 
            donor_user_gender='$donor_user_gender', 
            fundingType='$fundingType', 
            donationAmount='$donationAmount', 
            paymentType='$paymentType', 
            upiId='$upiId', 
            cardNumber='$cardNumber', 
            expiry='$expiry', 
            cvCode='$cvCode', 
            childhood_pet='$childhood_pet', 
            vacation_destination='$vacation_destination', 
            favourite_hobby='$favourite_hobby' 
            WHERE donor_user_id='$donor_user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record updated successfully');</script>";
        echo "<script>window.location.href = 'admin_manage_donors.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch donor details based on ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $donor_user_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM donor_details WHERE donor_user_id = '$donor_user_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Donor not found.</p>";
        exit; // Stop further execution if donor not found
    }
} else {
    echo "<p>Donor ID not provided.</p>";
    exit; // Stop further execution if donor ID not provided
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Donor Account</title>
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
        <h2>Update Donor Account</h2>
        <form method="post" action="update_donors.php">
            <input type="hidden" name="donor_user_id" value="<?php echo $row['donor_user_id']; ?>">
            <div>
                <label for="donor_user_name">Name:</label>
                <input type="text" name="donor_user_name" id="donor_user_name" value="<?php echo $row['donor_user_name']; ?>">
            </div>
            <div>
                <label for="donor_user_email">Email:</label>
                <input type="email" name="donor_user_email" id="donor_user_email" value="<?php echo $row['donor_user_email']; ?>">
            </div>
            <div>
                <label for="donor_user_phone">Phone:</label>
                <input type="text" name="donor_user_phone" id="donor_user_phone" value="<?php echo $row['donor_user_phone']; ?>">
            </div>
            <div>
                <label for="donor_user_street">Street:</label>
                <input type="text" name="donor_user_street" id="donor_user_street" value="<?php echo $row['donor_user_street']; ?>">
            </div>
            <div>
                <label for="donor_user_city">City:</label>
                <input type="text" name="donor_user_city" id="donor_user_city" value="<?php echo $row['donor_user_city']; ?>">
            </div>
            <div>
                <label for="donor_user_state">State:</label>
                <input type="text" name="donor_user_state" id="donor_user_state" value="<?php echo $row['donor_user_state']; ?>">
            </div>
            <div>
                <label for="donor_user_zip">Zip:</label>
                <input type="text" name="donor_user_zip" id="donor_user_zip" value="<?php echo $row['donor_user_zip']; ?>">
            </div>
            <div>
                <label for="donor_user_country">Country:</label>
                <input type="text" name="donor_user_country" id="donor_user_country" value="<?php echo $row['donor_user_country']; ?>">
            </div>
            <div>
                <label for="donor_user_pwd">Password:</label>
                <input type="password" name="donor_user_pwd" id="donor_user_pwd" value="<?php echo $row['donor_user_pwd']; ?>">
                <div class="show-password">
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label>
                </div>
            </div>
            <div>
                <label for="donor_user_gender">Gender:</label>
                <input type="text" name="donor_user_gender" id="donor_user_gender" value="<?php echo $row['donor_user_gender']; ?>">
            </div>
            <div>
                <label for="fundingType">Funding Type:</label>
                <input type="text" name="fundingType" id="fundingType" value="<?php echo $row['fundingType']; ?>">
            </div>
            <div>
                <label for="donationAmount">Donation Amount:</label>
                <input type="text" name="donationAmount" id="donationAmount" value="<?php echo $row['donationAmount']; ?>">
            </div>
            <div>
                <label for="paymentType">Payment Type:</label>
                <input type="text" name="paymentType" id="paymentType" value="<?php echo $row['paymentType']; ?>">
            </div>
            <div>
                <label for="upiId">UPI ID:</label>
                <input type="text" name="upiId" id="upiId" value="<?php echo $row['upiId']; ?>">
            </div>
            <div>
                <label for="cardNumber">Card Number:</label>
                <input type="text" name="cardNumber" id="cardNumber" value="<?php echo $row['cardNumber']; ?>">
            </div>
            <div>
                <label for="expiry">Expiry:</label>
                <input type="text" name="expiry" id="expiry" value="<?php echo $row['expiry']; ?>">
            </div>
            <div>
                <label for="cvCode">CVV:</label>
                <input type="text" name="cvCode" id="cvCode" value="<?php echo $row['cvCode']; ?>">
            </div>
            <div>
                <label for="childhood_pet">Childhood Pet:</label>
                <input type="text" name="childhood_pet" id="childhood_pet" value="<?php echo $row['childhood_pet']; ?>">
            </div>
            <div>
                <label for="vacation_destination">Vacation Spot:</label>
                <input type="text" name="vacation_destination" id="vacation_destination" value="<?php echo $row['vacation_destination']; ?>">
            </div>
            <div>
                <label for="favourite_hobby">Hobby:</label>
                <input type="text" name="favourite_hobby" id="favourite_hobby" value="<?php echo $row['favourite_hobby']; ?>">
            </div>
            <div>
                <label for="submission_date">Account Created on:</label>
                <input type="text" name="submission_date" id="submission_date" value="<?php echo $row['submission_date']; ?>" readonly>
            </div>
            <button type="submit" name="update_donor">Update Donor</button>
        </form>
    </div>

    <script>
        // JavaScript for show/hide password
        const passwordField = document.getElementById("donor_user_pwd");
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
