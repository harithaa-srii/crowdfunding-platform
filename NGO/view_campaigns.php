<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO CAMPAIGNS</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap");
        *,
        *:after,
        *:before {
            box-sizing: border-box;
        }

        body {
            font-family: "Lexend", sans-serif;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #393232;
            margin: 0; /* Added to remove default margin */
            padding: 0; /* Added to remove default padding */
        }

        img {
            max-width: 100%;
            display: block;
        }

        .card-list {
            display: flex;
            width:90%;
            justify-content: flex-start; /* Align cards to the left */
            margin-left: -10px; /* Adjust margin to move cards a bit leftwards */
        }
        .card {
            background-color: #FFF;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05), 0 20px 50px 0 rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            padding: 1.25rem;
            position: relative;
            transition: 0.15s ease-in;
            margin-bottom: 20px; /* Added margin between cards */
            width: calc(33.33% - 20px); /* Adjusted width to fit three cards in a row */
            max-width: calc(33.33% - 20px); /* Added max-width for responsiveness */
            margin-right: 20px; /* Added space between cards */
        }


        .card:hover,
        .card:focus-within {
            box-shadow: 0 0 0 2px #16C79A, 0 10px 60px 0 rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .card-image {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header a {
            font-weight: 600;
            font-size: 1.375rem;
            line-height: 1.25;
            padding-right: 1rem;
            text-decoration: none;
            color: inherit;
            will-change: transform;
        }

        .card-header a:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
        }

        .icon-button {
            border: 0;
            background-color: #fff;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            font-size: 1.25rem;
            transition: 0.25s ease;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05), 0 3px 8px 0 rgba(0, 0, 0, 0.15);
            z-index: 1;
            cursor: pointer;
            color: #565656;
        }

        .icon-button svg {
            width: 1em;
            height: 1em;
        }

        .icon-button:hover,
        .icon-button:focus {
            background-color: #EC4646;
            color: #FFF;
        }

        .card-footer {
          margin-top: 1.25rem;
          border-top: 1px solid #ddd;
          padding-top: 1.25rem;
          display: flex;
          align-items: center;
          flex-wrap: wrap;
          justify-content: space-between; /* Align elements in a row with space between them */
      }

        .card-meta {
            display: flex;
            align-items: center;
            color: #787878;
        }

        .card-meta:nth-child(2):before {
            content: "•"; /* Add dot between views and date */
            margin-right: 8px;
            color: #787878;
        }

        .card-meta:nth-child(3):before {
            content: "•"; /* Add dot between views and date */
            margin-right: 8px;
            color: #787878;
        }

        .card-meta svg {
    flex-shrink: 0;
    width: 1em;
    height: 1em;
    margin-right: 0.25em;
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
<div class="card-list">
    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crowdfund";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch recent campaigns from the database
    $sql = "SELECT * FROM campaigns ORDER BY id DESC LIMIT 5"; // Assuming 'campaigns' is your table name
    $result = $conn->query($sql);

    // Display recent campaigns
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Dynamically set the width of the campaign box based on content length
            $content_length = strlen($row["description"]);
            $box_width = $content_length * 10; // Adjust multiplier as needed
            echo '<article class="card" style="width: ' . $box_width . 'px;">';
            echo '<div class="brief-desc">' . $row["description"] . '</div>'; // Display the campaign title as the brief description
            echo '<div class="card-header">';
            echo '<a href="#">' . $row["title"] . '</a>'; // Display the campaign title again as a link
            echo '<button class="icon-button">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" display="block" id="Heart">';
            echo '<path d="M7 3C4.239 3 2 5.216 2 7.95c0 2.207.875 7.445 9.488 12.74a.985.985 0 0 0 1.024 0C21.125 15.395 22 10.157 22 7.95 22 5.216 19.761 3 17 3s-5 3-5 3-2.239-3-5-3z" />';
            echo '</svg>';
            echo '</button>';
            echo '</div>';
            echo '<div class="card-footer">';
            echo '<div class="card-meta card-meta--money">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">';
            echo '<path d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/>';
            echo '</svg>';
            echo '₹' . $row["goal"]; // Display the campaign goal
            echo '</div>';
            echo '<div class="card-meta card-meta--date">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">';
            echo '<path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64C28.7 64 0 92.7 0 128v16 48V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H344V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM48 192H400V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192z"/>';
            echo '</svg>';
            echo $row["created_at"]; // Display the date of creation
            echo '</div>';
            echo '<div class="card-meta card-meta--user">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">';
            echo '<path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z"/>';
            echo '</svg>';
            echo $row["username"]; // Display the username
            echo '</div>';
            echo '</div>';
            echo '</article>';
        }
    } else {
        echo "No campaigns found";
    }

    // Close connection
    $conn->close();
    ?>
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
            window.location.href = 'ngo_landing.php'; // Adjust the path as needed
            });

</script>
</body>
</html>
