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

// Function to fetch all NGO details from the database
function fetchAllUsers()
{
    global $conn;

    $query = "SELECT * FROM ngo_queries";
    $result = $conn->query($query);

    $nchData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nchData[] = $row;
        }
    }
    return $nchData;
}

// Function to fetch all donor queries from the database
function fetchAllDonorQueries()
{
    global $conn;

    $query = "SELECT * FROM donor_queries";
    $result = $conn->query($query);

    $donorData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donorData[] = $row;
        }
    }
    return $donorData;
}

// Function to fetch all logistics queries from the database
function fetchAllLogisticsQueries()
{
    global $conn;

    $query = "SELECT * FROM logistics_queries";
    $result = $conn->query($query);

    $logisticsData = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logisticsData[] = $row;
        }
    }
    return $logisticsData;
}

// Check if the "delete" parameter is present in the URL
if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    $table = $_GET['table']; // Get the table name
    $deleteResult = deleteQuery($deleteID, $table);
    if ($deleteResult) {
        // Refresh the page to update the list after deletion
        echo "<script>window.location.href = 'admin_help.php';</script>";
        exit(); // Added exit() to prevent further execution
    }
}

// Function to delete a query based on its ID and table name
function deleteQuery($id, $table)
{
    global $conn;
    $query = "DELETE FROM $table WHERE id = '$id'";
    $result = $conn->query($query);
    return $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Review - Users Question </title>
    <link rel="icon" href="../images/urbanlink-logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4c43584236.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
    /* styles.css */

    body {
        font-family: Arial, sans-serif;
        background-color: #E5E4E2;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 95%;
        /* max-width: 1200px; */
        margin: 20px auto;
        background-color: rgba(255, 255, 255, 0.50);
        backdrop-filter: blur(5px);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
        top: 20px;
        left: -2px;
        overflow-x: scroll;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .btn {
        padding: 5px;
        background-color: #007bff;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.2s ease;
        color: white;
        margin: 7px;
        display: flex;
        flex-direction: column;
    }

    .btn:hover {
        background-color: rgb(132, 0, 255);
        color: #ffff;
    }

    .btn-2 {
        padding: 5px;
        background-color: #007bff;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.2s ease;
        color: white;
        margin: 7px;
        position: relative;
        top: 5px;
    }

    .btn-2:hover {
        background-color: rgb(132, 0, 255);
        color: #ffff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        margin-top: 20px;
    }

    th,
    td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:hover {
        background-color: #989191;
    }

    td {
        font-weight: 600;
    }

    td:hover {
        cursor: pointer;
        color: white;
    }

    a {
        text-decoration: none;
        color: #007bff;
    }

    a:hover {
        text-decoration: underline;
    }

    .go-back {
        padding: 10px;
        text-align: center;
        background-color: orange;
        cursor: pointer;
        color: white;
        text-decoration: none;
    }

    .go-back:hover {
        background-color: #0088cc;
        transition: 0.2s linear;
        text-decoration: none;
    }

    .back-button {
        position: relative;
        top: 20px;
        margin-left: 10px;
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 10px;
        position: relative;
        top: 440px;
    }

    .pagination a {
        padding: 8px 12px;
        border: 1px solid #007bff;
        border-radius: 4px;
        color: #007bff;
        text-decoration: none;
    }

    .pagination a.active {
        background-color: #007bff;
        color: #fff;
    }

    /* Responsive design using media queries */
    @media screen and (max-width: 600px) {
        table {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 400px) {
        body {
            background-image: none;
            background-color: white;
        }

        table {
            font-size: 12px;
        }
    }
    </style>
</head>

<body>
    <div class="dropdown">
        <label for="queries">View questions from:</label>
        <select id="queries" onchange="showTable(this.value)">
            <option value="ngo" selected>NGO</option>
            <option value="donor">Donors</option>
            <option value="logistics">Logistics</option>
        </select>
    </div>

    <!-- HTML content for NGO queries -->
    <div class="container" id="ngoquery">
        <h1>NGO Questions Review</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>User Question</th>
                <th>Date Questioned</th>
                <th>Answer Replied</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all NGOs from the database
            $nchData = fetchAllUsers();

            foreach ($nchData as $nch) {
                echo '<tr>';
                echo '<td>' . $nch['nch_user_id'] . '</td>';
                echo '<td>' . $nch['nch_user_name'] . '</td>';
                echo '<td>' . $nch['nch_user_phone'] . '</td>';
                echo '<td>' . $nch['nch_user_email'] . '</td>';
                echo '<td>' . $nch['nch_desc'] . '</td>';
                echo '<td>' . $nch['nch_date'] . '</td>';
                echo '<td>' . $nch['nch_admin_reply'] . '</td>';
                echo '<td class="two-btn">';
                echo '<div><a href="admin_nch_update_sep.php?id=' . $nch['nch_id'] . '">Update</a></div> | ';
                echo '<div><a href="?delete=' . $nch['nch_id'] . '&table=ngo" onclick="return confirmDelete();">Delete</a></div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <!-- HTML content for Donor queries -->
    <div class="container" id="donorquery" style="display: none;">
        <h1>Donor Questions Review</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>User Question</th>
                <th>Date Questioned</th>
                <th>Answer Replied</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all donor queries from the database
            $donorData = fetchAllDonorQueries();

            foreach ($donorData as $donor) {
                echo '<tr>';
                echo '<td>' . $donor['dch_user_id'] . '</td>';
                echo '<td>' . $donor['dch_user_name'] . '</td>';
                echo '<td>' . $donor['dch_user_phone'] . '</td>';
                echo '<td>' . $donor['dch_user_email'] . '</td>';
                echo '<td>' . $donor['dch_desc'] . '</td>';
                echo '<td>' . $donor['dch_date'] . '</td>';
                echo '<td>' . $donor['dch_admin_reply'] . '</td>';
                echo '<td class="two-btn">';
                echo '<div><a href="admin_dch_update_sep.php?id=' . $donor['dch_id'] . '">Update</a></div> | ';
                echo '<div><a href="?delete=' . $donor['dch_id'] . '&table=donor" onclick="return confirmDelete();">Delete</a></div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <!-- HTML content for Logistics queries -->
    <div class="container" id="logisticsquery" style="display: none;">
        <h1>Logistics Questions Review</h1>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>User Question</th>
                <th>Date Questioned</th>
                <th>Answer Replied</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch all logistics queries from the database
            $logisticsData = fetchAllLogisticsQueries();

            foreach ($logisticsData as $logistics) {
                echo '<tr>';
                echo '<td>' . $logistics['lch_user_id'] . '</td>';
                echo '<td>' . $logistics['lch_user_name'] . '</td>';
                echo '<td>' . $logistics['lch_user_phone'] . '</td>';
                echo '<td>' . $logistics['lch_user_email'] . '</td>';
                echo '<td>' . $logistics['lch_desc'] . '</td>';
                echo '<td>' . $logistics['lch_date'] . '</td>';
                echo '<td>' . $logistics['lch_admin_reply'] . '</td>';
                echo '<td class="two-btn">';
                echo '<div><a href="admin_lch_update_sep.php?id=' . $logistics['lch_id'] . '">Update</a></div> | ';
                echo '<div><a href="?delete=' . $logistics['lch_id'] . '&table=logistics" onclick="return confirmDelete();">Delete</a></div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <script>
        function showTable(selectedValue) {
            document.getElementById('ngoquery').style.display = (selectedValue === 'ngo') ? 'block' : 'none';
            document.getElementById('donorquery').style.display = (selectedValue === 'donor') ? 'block' : 'none';
            document.getElementById('logisticsquery').style.display = (selectedValue === 'logistics') ? 'block' : 'none';
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this Question From the user?");
        }
    </script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>
