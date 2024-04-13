<?php
session_start(); // Start the session

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // If logged in, destroy the session and redirect to login page
    session_destroy();
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Admin- HOME</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel='stylesheet' href="admin_landing_style.css">
  <style>
    .main-header {
    margin-right: 20px; /* Adjust the margin as needed */
  }

  .common-post {
    width: 100%; /* Adjust the width as needed */
    height: auto; /* Adjust the height as needed */
  }
  </style>
</head>

<body>
<!-- partial:index.partial.html -->
<button class="icon-button e-dark-mode-button u-animation-click" id="darkMode" aria-label="Dark Mode"><span class="icon" aria-hidden="true">🌜</span></button>
<div class="common-structure">
  <header class="main-header u-flex">
    <div class="start u-flex">
       <a class="logo"></a>
       <div class="search-box-wrapper">
         <input type="search" class="search-box" placeholder="Search">
         <span class="icon-search" aria-label="hidden">🔎</span>
      </div>
    </div>
    
    
<nav class="main-nav">
<ul class="main-nav-list u-flex">
<?php
$category = isset($_GET['category']) ? $_GET['category'] : '';// Get the current category from the URL query parameter
$categories = array('admin_posts', 'donor_posts', 'ngo_posts', 'logistics_posts');// Define an array of categories
foreach ($categories as $cat) {
    $isSelected = $category == $cat ? 'is-selected' : ''; // Check if the current category matches the navigation item
?>

    <li class="main-nav-item u-only-wide">
        <a href="?category=<?php echo $cat; ?>" aria-label="<?php echo ucfirst(str_replace('_', ' ', $cat)); ?>" class="main-nav-button alt-text <?php echo $isSelected; ?>">
            <span class="icon"><i class="fa-solid <?php echo getIconForCategory($cat); ?> fa-xl"></i></span>
        </a>
    </li>

<?php
}

// Function to get the icon class based on category
function getIconForCategory($category) {
    switch ($category) {
        case 'admin_posts':
            return 'fa-user-gear';
        case 'donor_posts':
            return 'fa-hand-holding-dollar';
        case 'ngo_posts':
            return 'fa-hand-holding-hand';
        case 'logistics_posts':
            return 'fa-truck';
        default:
            return '';
    }
}
?>
</ul>
</nav>


  <!-- script for menu -->
  <script>
function selectNavItem(element, event) {
  event.preventDefault();
  // Remove is-selected class from all links
  var links = document.querySelectorAll('.main-nav-button');
  links.forEach(function(link) {
    link.classList.remove('is-selected');

  });
  
  // Add is-selected class to the clicked link
  element.classList.add('is-selected');
}
</script>

  <!-- end -->
   
  <div class="end"></div>
  </header>
  <aside class="side-a">
    <section class="common-section">
      <h2 class="section-title u-hide">User Navigation</h2>
      <ul class="common-list">
        <li class="common-list-item">
          <a href="admin_prof_manage.php" class="common-list-button">

            <div class="nav-button"><i class="fas fa-user fa-xl"></i><span>Profile</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_post_creation.php" class="common-list-button">

            <div class="nav-button"><i class="fas fa-plus fa-xl"></i><span>Create Post</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_manage_post.php" class="common-list-button">

            <div class="nav-button"><i class="fas fa-bars-progress fa-xl"></i><span>Manage Posts</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_manageall_post.php" class="common-list-button">

            <div class="nav-button"><i class="fas fa-eye fa-xl"></i><span>Manage all posts</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_user_management.php" class="common-list-button">

            <div class="nav-button"><i class="fas fa-users-gear fa-xl"></i><span>Manage all users</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="logistics_acc_req.php" class="common-list-button">

          <div class="nav-button"><i class="fas fa-truck-fast fa-xl"></i><span>Logistics Account Request</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="ngo_acc_req.php" class="common-list-button">

          <div class="nav-button"><i class="fas fa-building-ngo fa-xl"></i><span>NGO Account Request</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_manage_userreports.php" class="common-list-button">

          <div class="nav-button"><i class="fas fa-flag fa-xl"></i><span>Reported Users</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_help.php" class="common-list-button">

          <div class="nav-button"><i class="fas fa-clipboard-question fa-xl"></i><span>Queries</span></div>
          </a>
        </li>
        <li class="common-list-item">
          <a href="admin_login.php" class="common-list-button">
            <div class="nav-button"><i class="fas fa-sign-out-alt fa-xl"></i><span>Logout</span></div>
          </a>
        </li>
      </ul>  
  </aside>
  
  
  <main class="main-feed">
    <ul class="main-feed-list">
      <?php
          // Database connection parameters
          $servername = "localhost";
          $username = "root"; // Your MySQL username
          $password = ""; // Your MySQL password
          $dbname = "crowdfund"; // Your MySQL database name

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Fetch posts from the database
            // Fetch posts from the database based on the selected category
$category = isset($_GET['category']) ? $_GET['category'] : 'admin_posts'; // Default category is admin_posts
$sql = "SELECT * FROM $category ORDER BY id DESC"; // Assuming 'admin_posts' is your table name
$result = $conn->query($sql);

// Display posts
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<li class="main-feed-item">';
        echo '<article class="common-post">';
        echo '<header class="common-post-header u-flex">';
        echo '<div class="common-post-info">';
        echo '<div class="user-and-group u-flex">';
        echo '<p>' . $row["username"] . '</p>'; // Display the username
        echo '</div>';
        echo '</div>';
        echo '</header>';
        echo '<div class="common-post-content common-content">';
        echo '<p>' . $row["content"] . '</p>'; // Display the post content
        echo '<div class="image-container">';
        echo '<img src="../images/' . $row['media'] . '" alt="Uploaded Image" width="300" height="300">';
        echo '</div>';
        echo '</div>';
        echo '<section class="actions-buttons">';
        echo '<ul class="actions-buttons-list u-flex">';
        echo '<li class="actions-buttons-item"><a href="https://donate.stripe.com/test_5kA9BU4Qj49F9IkeUV"><button class="actions-buttons-button"><span class="icon">💌</span><span class="text">Contribute</span></button></a></li>';
        echo '<li class="actions-buttons-item"><button class="actions-buttons-button"><span class="icon"><i class="fas fa-times"></i></span><span class="text">Report</span></button></li>';
        echo '</ul>';
        echo '</section>';
        echo '</article>';
        echo '</li>';
    }
} else {
    echo "No posts found";
}


          // Close connection
          $conn->close();
      ?>
      </ul>
  </main>

</div>
<!-- partial -->
  <script>
    $(document).ready(function() {
    $("#menuButton").on("click", function(){
        $(".side-a").toggleClass("is-open");
        $("html").toggleClass("is-nav-open");
    });
      $("#darkMode").on("click", function(){
        $("html").toggleClass("is-dark");
    });
});
  </script>

<script>
function clickCounter() {
  if (typeof(Storage) !== "undefined") {
    if (localStorage.clickcount) {
      localStorage.clickcount = Number(localStorage.clickcount)+1;
    } else {
      localStorage.clickcount = 1;
    }
    document.getElementById("result").innerHTML = localStorage.clickcount ;
  } else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support web storage...";
  }
}
</script>
</body>
</html>
