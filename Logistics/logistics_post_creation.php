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
    $username = $_SESSION['logistics_username'];
    $content = $_POST['content'];

    // Handle file upload
    $file_name = $_FILES['media']['name'];
    $tempname = $_FILES["media"]["tmp_name"];
    $folder = '../images/'.$file_name;
    move_uploaded_file($tempname, $folder);

    // Insert post into the database
    $sql = "INSERT INTO logistics_posts (username, content, media, created_at) VALUES (?, ?, ?, NOW())";

    // Prepare and bind statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $content, $folder);

    if ($stmt->execute()) {
        // Post submitted successfully
        echo "<script>alert('Post submitted successfully');</script>";
        header("Location: logistics_landing.php"); // Redirect back to the same page
    } else {
        // Error in submitting post
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
            /* Import Google Font - Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
body{
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: background-color:#780d9b ;
  background-image: linear-gradient(316deg, #780d9b 0%, #a594f9 74%);
}
::selection{
  color: #fff;
  background: #780d9b;
}
.container{
  width: 500px;
  height: 480px;
  overflow: hidden;
  background: #fff;
  border-radius: 10px;
  transition: height 0.2s ease;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
}
.container.active{
  height: 590px;
}
.container .wrapper{
  width: 1000px;
  display: flex;
}
.container .wrapper section{
  width: 500px;
  background: #fff;
}
.container img{
  cursor: pointer;
}
.container .post{
  transition: margin-left 0.18s ease;
}
.container.active .post{
  margin-left: -500px;
}
.post header{
  font-size: 22px;
  font-weight: 600;
  padding: 17px 0;
  text-align: center;
  border-bottom: 1px solid #bfbfbf;
}
.post form{
  margin: 20px 25px;
}
.post form .content,
.audience .list li .column{
  display: flex;
  align-items: center;
}
.post form .content img{
  cursor: default;
  max-width: 52px;
}
.post form .content .details{
  margin: -3px 0 0 12px;
}
form .content .details p{
  font-size: 17px;
  font-weight: 500;
}
.content .details .privacy{
  display: flex;
  height: 25px;
  cursor: pointer;
  padding: 0 10px;
  font-size: 11px;
  margin-top: 3px;
  border-radius: 5px;
  align-items: center;
  max-width: 98px;
  background: #E4E6EB;
  justify-content: space-between;
}
.details .privacy span{
  font-size: 13px;
  margin-top: 1px;
  font-weight: 500;
}
.details .privacy i:last-child{
  font-size: 13px;
  margin-left: 1px;
}
form :where(textarea, button){
  width: 100%;
  outline: none;
  border: none;
}
form textarea{
  resize: none;
  font-size: 18px;
  margin-top: 30px;
  min-height: 100px;
}
form textarea::placeholder{
  color: #858585;
}
form textarea:focus::placeholder{
  color: #b3b3b3;
}
form :where(.theme-emoji, .options){
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.theme-emoji img:last-child{
  max-width: 24px;
}
form .options{
  height: 57px;
  margin: 15px 0;
  padding: 0 15px;
  border-radius: 7px;
  border: 1px solid #B9B9B9;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}
form .options p{
  color: #595959;
  font-size: 15px;
  font-weight: 500;
  cursor: default;
}
form .options .list{
  list-style: none;
  display: flex;
}
.options .list li{
  height: 42px;
  width: 42px;
  margin: 0 -1px;
  cursor: pointer;
  border-radius: 50%;
}
.options .list li:hover{
  background: #f0f1f4;
}
.options .list li img{
  width: 23px;
}
form button{
  height: 53px;
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  cursor: pointer;
  color: #BCC0C4;
  cursor: no-drop;
  border-radius: 7px;
  background: #e2e5e9;
  transition: all 0.3s ease;
}
form textarea:valid ~ button{
  color: #fff;
  cursor: pointer;
  background:#a594f9;
}
form textarea:valid ~ button:hover{
  background: #780d9b;
}
.container .audience{
  opacity: 0;
  transition: opacity 0.12s ease;
}
.container.active .audience{
  opacity: 1;
}
.audience header{
  padding: 17px 0;
  text-align: center;
  position: relative;
  border-bottom: 1px solid #bfbfbf;
}
.audience header .arrow-back{
  position: absolute;
  left: 25px;
  width: 35px;
  height: 35px;
  cursor: pointer;
  font-size: 15px;
  color: #747474;
  border-radius: 50%;
  background: #E4E6EB;
}
.audience header p{
  font-size: 22px;
  font-weight: 600;
}
.audience .content{
  margin: 20px 25px 0;
}
.audience .content p{
  font-size: 17px;
  font-weight: 500;
}
.audience .content span{
  font-size: 14px;
  color: #65676B;
}
.audience .list{
  margin: 15px 16px 20px;
  list-style: none;
}
.audience .list li{
  display: flex;
  cursor: pointer;
  margin-bottom: 5px;
  padding: 12px 10px;
  border-radius: 7px;
  align-items: center;
  justify-content: space-between;
}
.list li.active,
.audience .list li.active:hover{
  background: #E5F1FF;
}
.audience .list li:hover{
  background: #f0f1f4;
}
.audience .list li .column .icon{
  height: 50px;
  width: 50px;
  color: #333;
  font-size: 23px;
  border-radius: 50%;
  background: #E4E6EB;
}
.audience .list li.active .column .icon{
  background: #cce4ff;
}
.audience .list li .column .details{
  margin-left: 15px;
}
.list li .column .details p{
  font-weight: 500;
}
.list li .column .details span{
  font-size: 14px;
  color: #65676B;
}
.list li .radio{
  height: 20px;
  width: 20px;
  border-radius: 50%;
  position: relative;
  border: 1px solid #707070;
}
.list li.active .radio{
  border: 2px solid #4599FF;
}
.list li .radio::before{
  content: "";
  width: 12px;
  height: 12px;
  border-radius: inherit;
}
.list li.active .radio::before{
  background: #4599FF;
}
    </style>
</head>
<body>
<div class="container">
  <div class="wrapper">
    <section class="post">
      <header>Create Post</header>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="content">
          <img src="../images/Logo.png" alt="logo" style="border-radius:50%;">
          <div class="details">
            <p><b?><?php echo $_SESSION['logistics_username']; ?></b></p>
          </div>
        </div>
        <textarea name="content" placeholder="What's on your mind?" spellcheck="false" required></textarea>
        <div class="theme-emoji">
          <img src="../images/theme.svg" alt="theme">
        </div>
        <div class="options">
          <ul class="list">
            <li onclick="document.getElementById('media').click();"><img src="../images/gallery.svg" alt="gallery"></li>
            <input type="file" id="media" name="media" style="display:none;">
            <li><img src="../images/more.svg" alt="gallery"></li>
          </ul>
          <div id="image-details" style="display:none;">
            <p id="file-name"></p>
          </div>
        </div>
        <button type="submit" name="submit">Post</button>
      </form>
    </section>
  </div>
</div>
<script>
  document.getElementById('media').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const fileName = file.name;

    document.getElementById('file-name').textContent = 'File Name: ' + fileName;

    document.getElementById('image-details').style.display = 'block';
  });
</script>
</body>
</html>
