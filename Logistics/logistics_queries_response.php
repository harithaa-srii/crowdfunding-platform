
<?php
session_start();

// Check if the user is logged in
if ( !isset($_SESSION['logistics_id'])) {
    header('Location: logistics_login.php');
    exit();
}

// Include database configuration
// Database configuration
$dbHost = 'localhost';
$dbName = 'crowdfund';
$dbUser = 'root';
$dbPass = '';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Get the logistics user ID from the session
$logistics_id = $_SESSION['logistics_id'];

// Get queries for the logged-in logistics user
$query = "SELECT * FROM queries WHERE user_id = :logistics_id ORDER BY date_questioned DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':logistics_id', $logistics_id);
$stmt->execute();
$queries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logistics Questions Management</title>
    <link rel="icon" href="../images/urbanlink-logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 80px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .post-list {
            list-style: none;
            padding: 0;
        }

        .post-item {
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #ffffff;
        }

        .post-info {
            margin-bottom: 20px;
        }

        .public-q-description,
        .admin-ans-description {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .ur-q,
        .ur-ans {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .pch-desc,
        .pch-admin-reply {
            color: #666;
            font-size: 16px;
        }

        .ask-q {
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            background-color: orange;
            cursor: pointer;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .ask-q:hover {
            background-color: #0088cc;
            transition: 0.2s linear;
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
        <h2>Logistics Questions Management</h2>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <ul class="post-list">
            <?php if (!empty($queries)) : ?>
                <?php foreach ($queries as $query) : ?>
                    <li class="post-item">
                        <div class="post-info">
                            <div class="public-q-description">
                                <p class="ur-q">Your Question:</p>
                                <p class="pch-desc"><?php echo $query['description']; ?></p>
                            </div>
                            <div class="admin-ans-description">
                                <p class="ur-ans">Admin Answer:</p>
                                <p class="pch-admin-reply"><?php echo $query['admin_reply']; ?></p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li class="post-item">No Queries Asked👍. <a href="logistics_question_form.php" class="ask-q">Feel Free to Ask Questions Here!</a></li>
            <?php endif; ?>
        </ul>
    </div>
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
            window.location.href = 'logistics_faq.php'; // Adjust the path as needed
            });

</script>
</body>

</html>
