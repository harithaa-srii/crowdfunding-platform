<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin post management</title>
    <link rel="icon" href="../images/logo-light-theme.png" type="image/icon type">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .post-content {
            margin-bottom: 10px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .button {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .donate {
            background-color: #4caf50;
            color: #fff;
        }

        .donate:hover {
            background-color: #45a049;
        }

        .report {
            background-color: #f44336;
            color: #fff;
        }

        .report:hover {
            background-color: #d32f2f;
        }

        /* Add style for bold username */
        .username {
            font-weight: bold;
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
        <h2>Posts</h2>
        <?php
        // Database connection details
        $servername = "localhost"; // Change this if your database is hosted on a different server
        $username = "root"; // Change this to your database username
        $password = ""; // Change this to your database password
        $dbname = "crowdfund"; // Change this to your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if form is submitted for update or delete
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
            if ($_POST['action'] == 'update' && isset($_POST['post_id'])) {
                // Redirect to admin_post_update.php for updating
                header("Location: admin_post_update.php?post_id=" . $_POST['post_id']);
                exit();
            } elseif ($_POST['action'] == 'delete' && isset($_POST['post_id'])) {
                $postId = $_POST['post_id'];

                // Delete post from the database
                $sql = "DELETE FROM admin_posts WHERE id = $postId";
                if ($conn->query($sql) === TRUE) {
                    echo "Post deleted successfully";
                } else {
                    echo "Error deleting post: " . $conn->error;
                }
            }
        }

        // Fetch posts from the database (sorted by id in descending order to display latest first)
        $sql = "SELECT * FROM admin_posts ORDER BY id DESC";
        $result = $conn->query($sql);

        // Display each post
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<div class='post-content'>";
                // Display username in bold
                echo "<p><strong class='username'>" . $row['username'] . "</strong></p>";
                echo "<p>" . $row['content'] . "</p>";
                echo '<img src="../images/' . $row['media'] . '" alt="Uploaded Image" width="700" height="300">';
                echo "</div>";
                echo "<div class='buttons'>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='post_id' value='" . $row['id'] . "'>";
                echo "<input type='hidden' name='username' value='" . $row['username'] . "'>"; // Added hidden field for username
                echo "<input type='hidden' name='content' value='" . $row['content'] . "'>"; // Added hidden field for content
                echo "<input type='hidden' name='action' value='update'>";
                echo "<button class='button donate' type='submit'>Update</button>";
                echo "</form>";
                echo "<form method='post' action='' onsubmit='return confirmDelete(" . $row['id'] . ")'>";
                echo "<input type='hidden' name='post_id' value='" . $row['id'] . "'>";
                echo "<input type='hidden' name='action' value='delete'>";
                echo "<button class='button report'>Delete</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "No posts found.";
        }

        // Close connection
        $conn->close();
        ?>

        <script>
            function confirmDelete(postId) {
                if (confirm('Are you sure you want to delete this post?')) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', window.location.href, true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            location.reload();
                        } else {
                            alert('Error deleting post.');
                        }
                    };
                    xhr.send('action=delete&post_id=' + postId);
                }
                return false;
            }
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
            window.location.href = 'admin_landing.php'; // Adjust the path as needed
            });

        </script>
    </div>
</body>
</html>
