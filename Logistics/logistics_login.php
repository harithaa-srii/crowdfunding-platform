<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['location'];
    $organization = $_POST['organization'];
    $organizationemail = $_POST['organizationemail'];
    $userphone = $_POST['userphone'];

    // Database connection
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "crowdfund";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM logistics_details WHERE logistics_user_name = ? AND logistics_user_email = ? AND logistics_user_pwd = ? AND logistics_org_place = ? AND logistics_org_mail= ? AND logistics_user_phone = ?");
    $stmt->bind_param("ssssss", $username, $email, $password, $location, $organizationemail, $userphone);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();
        $logisticsId = $row['logistics_id'];
        $accReqStatus = $row['logistics_account_req_status'];

        // Check account request status
        if ($accReqStatus == 'Pending' || $accReqStatus == 'Rejected') {
            echo '<script>';
            echo 'alert("Your account request is pending or rejected. Please wait for Approval!! You will be redirected to Home page after 5 seconds.");';
            echo 'setTimeout(function() { window.location.href = "../index.php"; }, 5000);'; // Redirect after 5 seconds
            echo '</script>';
        } elseif ($accReqStatus == 'Approve') {
            // Save values in session variables
            $_SESSION['logistics_id'] = $logisticsId;
            $_SESSION['logistics_username'] = $username;
            $_SESSION['logistics_useremail'] = $email;
            $_SESSION['logistics_userphone'] = $userphone;
            $_SESSION['logistics_organization'] = $organization;
            $_SESSION['logistics_organizationemail'] = $organizationemail;
            $_SESSION['logistics_location'] = $location;

            // Redirect to logistics_landing.php
            header("Location: logistics_landing.php");
            exit();
        }
    } else {
        // Invalid credentials, display error message
        $errorMessage = "Invalid username, email, password, or location";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logistics Login</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, minimum-scale=0.1">
    <style>
        body{
            background-color:#ffffff;
            background-image: url("../images/login-background.jpg");
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover; 
        }

        .toggle-password {
            position: relative;
            
            /* transform: translateY(-50%); */
            color: white;
            background-color: plum;
            cursor: pointer;
            padding: 5px;
            border-radius: 10px;
            z-index: 1;
        }

        .toggle-password:hover {
            color: #ff512f;
        }

        *,
        *:before,
        *:after{
            padding: 0;
            margin: 0;
        }
        .background{
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%,-50%);
            left: 50%;
            top: 50%;
        }
        
        form {
        height: 980px; /* Reduced height to allow space at the bottom */
        width: 420px;
        background-color: rgba(120,13,170,0.46);
        position: absolute;
        transform: translate(-50%,-50%);
        top: 68%;
        left: 50%;
        bottom: 20px; /* Adjusted bottom space */
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.1);
        box-shadow: 0 0 40px rgba(8,7,16,0.6);
        padding: 10px 30px;
    }

        form *{
            font-family: 'Poppins',sans-serif;
            color: #ffffff;
            letter-spacing: 0.7px;
            outline: none;
            border: none;
        }
        form h3{
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label{
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }
        input{
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255,255,255,0.07);
            border-radius: 3px;
            padding: 0px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }
        ::placeholder{
            color: #e5e5e5;
        }
        button{
            margin-top: 20px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
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
<div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="back-button">
    <div class="arrow-wrap">
        <span class="arrow-part-1"></span>
        <span class="arrow-part-2"></span>
        <span class="arrow-part-3"></span>
    </div>
</div>
    <div class="container">
        <form method="POST" action="logistics_login.php">
        <h3>Logistics Login</h3>
            <div class="posi">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your User Name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your UserEmail" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your Password" required>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <div class="form-group">
                    <label for="userphone">User Phone Number:</label>
                    <input type="tel" id="userphone" name="userphone" placeholder="Enter your User Phone Number" required>
                </div>
                <div class="form-group">
                    <label for="organization">Organization:</label>
                    <input type="text" id="organization" name="organization" placeholder="Enter your Org-Name" required>
                </div>
                <div class="form-group">
                    <label for="organizationemail">Organization Email:</label>
                    <input type="email" id="organizationemail" name="organizationemail" placeholder="Enter your Organization Email" required>
                </div>
                <div class="form-group ">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <br>
                <a href="logistics_forgot_pwd.php">Forgot Password?</a>
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
        <!-- Add this inside the <form> tag, below the submit button -->
        <div id="error-message-container" class="error-message" style="display: none;"></div>

        <?php if (isset($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.querySelector('.toggle-password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessageContainer = document.getElementById('error-message-container');

            <?php if (!empty($errorMessage)) : ?>
                errorMessageContainer.innerHTML = '<?php echo $errorMessage; ?>';
                errorMessageContainer.style.display = 'block';

                // Hide the error message after 3 seconds
                setTimeout(function() {
                    errorMessageContainer.innerHTML = '';
                    errorMessageContainer.style.display = 'none';
                }, 3000);
            <?php endif; ?>
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
            window.location.href = '../index.php'; // Adjust the path as needed
            });

</script>
</body>

</html>
