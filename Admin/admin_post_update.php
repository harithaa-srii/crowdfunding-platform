<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
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

    // Retrieve post data from the form
    $postId = $_POST['post_id'];
    $content = $_POST['content'];

    // Handle file upload if media is provided
    if ($_FILES['media']['name'] != "") {
        $file_name = $_FILES['media']['name'];
        $tempname = $_FILES["media"]["tmp_name"];
        $folder = '../images/'.$file_name;
        move_uploaded_file($tempname, $folder);
        
        // Update media in the database
        $sql_media = "UPDATE admin_posts SET media=? WHERE id=?";
        $stmt_media = $conn->prepare($sql_media);
        $stmt_media->bind_param("si", $folder, $postId);
        $stmt_media->execute();
        $stmt_media->close();
    }

    // Update post content in the database
    $sql_content = "UPDATE admin_posts SET content=? WHERE id=?";
    $stmt_content = $conn->prepare($sql_content);
    $stmt_content->bind_param("si", $content, $postId);
    if ($stmt_content->execute()) {
        // Post updated successfully
        echo "<script>alert('Post updated successfully');</script>";
        header("Location: admin_landing.php"); // Redirect back to the landing page
    } else {
        // Error in updating post
        echo "<script>alert('Error: " . $stmt_content->error . "');</script>";
    }

    // Close statement and connection
    $stmt_content->close();
    $conn->close();
}

// Fetch the post content and media from the database based on the post_id provided in the URL
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

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

    // Fetch post content and media from the database
    $sql = "SELECT * FROM admin_posts WHERE id = ?";
    
    // Prepare and bind statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $content = $row['content'];
        $media = $row['media'];
    } else {
        echo "Post not found.";
        exit(); // Exit if post not found
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    exit(); // Exit if post_id is not provided in the URL
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
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
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Post</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
            <label for="post_id">Post ID:</label>
            <input type="text" id="post_id" name="post_id" value="<?php echo $postId; ?>" readonly><br>
            <label for="content">Content:</label>
            <textarea id="content" name="content" spellcheck="false" required><?php echo $content; ?></textarea><br>
            <label for="media">Update Media:</label>
            <input type="file" id="media" name="media"><br>
            <button type="submit" name="submit">Update Post</button>
        </form>
    </div>

    <script>
        // JavaScript to display dialog box after form submission
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var form = this;

            // Submit the form using AJAX
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // If the request was successful, show the dialog box
                    alert('Post Updated Successfully');
                    window.location.href = 'admin_landing.php'; // Redirect to admin_landing.php after clicking OK
                } else {
                    // If there was an error, display an error message
                    alert('Error: ' + xhr.responseText);
                }
            };
            xhr.onerror = function() {
                // If there was a network error, display an error message
                alert('Network Error');
            };
            xhr.send(formData);
        });
    </script>
    
</body>
</html>