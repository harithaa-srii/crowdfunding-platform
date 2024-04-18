<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['donor_user_name']) || !isset($_SESSION['donor_user_id'])) {
    header('Location: donor_login.php');
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

// Get the donor ID from the session
$user_id = $_SESSION['donor_user_id'];

// Get posts for the logged-in donor
$query = "SELECT * FROM queries WHERE user_id = :user_id ORDER BY date_questioned DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Donor Questions Management</title>
    <link rel="icon" href="../images/urbanlink-logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .back-button {
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            background-color: orange;
            cursor: pointer;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    
    <div class="container">
        <h2>Donor Questions Management</h2>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <ul class="post-list">
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $post) : ?>
                    <li class="post-item">
                        <div class="post-info">
                            <div class="public-q-description">
                                <p class="ur-q">Your Question:</p>
                                <p class="pch-desc"> <?php echo $post['description']; ?></p>
                            </div>
                            <div class="admin-ans-description">
                                <p class="ur-ans">Admin Answer:</p>
                                <p class="pch-admin-reply"><?php echo $post['admin_reply']; ?></p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li class="post-item">No Questions Asked👍. <a href="donor_question_form.php" class="ask-q">Feel Free to Clear Your Doubts by clicking here!!!</a> </li>
            <?php endif; ?>
        </ul>
    </div>
</body>

</html>
