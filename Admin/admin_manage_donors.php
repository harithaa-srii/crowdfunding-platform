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

$serialNumber = 1;

// Check if the "delete" parameter is present in the URL
if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];
    $deleteResult = deleteUser($deleteID);
    if ($deleteResult) {
        // Refresh the page to update the user list after deletion
        echo "<script>window.location.href = 'admin_manage_donors.php';</script>";
    }
}

// Function to delete a donor based on their ID
function deleteUser($userID)
{
    global $conn;

    $query = "DELETE FROM donor_details WHERE donor_user_id = '$userID'";
    $result = $conn->query($query);
    
    if ($result === TRUE) {
        return true; // Deletion successful
    } else {
        echo "Error deleting record: " . $conn->error;
        return false; // Deletion failed
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Account Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            font-family: "lato", sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 50px rgba(07, 04, 20, 1);
            position: relative;
            overflow-y: auto; /* Keep vertical scrollbar if needed */
            border: 2px solid black;
            scrollbar-width: thin;
            scrollbar-color: #a0a0a0 #f0f0f0;
            top: 50px;
        }

        .user-table-container {
            width: 100%;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 1);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 1);
            position: relative;
            top: 20px;
            left: -2px;
            overflow: scroll;
            margin-bottom: 50px;
            max-height: 800px !important;
        }

        h1 {
            text-align: center;
        }

        h2 {
            font-size: 26px;
            margin: 20px 0;
            text-align: center;
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
            background-color: #780d9b;
            font-size: 14px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        tr:hover {
            background-color: #d5cece;
        }

        .button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
        }

        .search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin: 20px auto;
            width: 50%;
        }

        .searchInput {
            padding: 10px;
            border: 2px solid black;
            border-radius: 5px;
            margin-right: 10px;
            flex-grow: 1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: background-color 0.3s, box-shadow 0.3s;
            transition: 0.3s ease-in;
        }

        .search-button,
        .clear-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }

        .search-button {
            border-radius: 5px;
            color: white;
            cursor: pointer;
            border: none !important;
            margin-right: 20px;
            transition: 0.3s ease-in;
            background-image: linear-gradient(to right, #0cc91c 45%, #007bff 55%);
            background-size: 220% 100%;
            background-position: 100% 50%;

            &:hover {
                background-position: 0% 50%;
            }
        }


        .clear-button {
            border-radius: 5px;
            color: white;
            cursor: pointer;
            border: none !important;
            margin-right: 20px;
            transition: 0.3s ease-in;
            background-image: linear-gradient(to right, #0cc91c 45%, #dc3545 55%);
            background-size: 220% 100%;
            background-position: 100% 50%;

            &:hover {
                background-position: 0% 50%;
            }
        }


        .searchInput:focus {
            background-color: #f2f2f2;
            box-shadow: 0 0 10px rgba(0, 123, 255, 1);
            color: #d72323;
            outline: none;
        }

        .searchInput::placeholder {
            color: #ccc;
        }

        .search-button:focus,
        .clear-button:focus {
            outline: none;
        }

        .search-button:active,
        .clear-button:active {
            transform: scale(0.95);
        }

        /* ------------------------------------------------------------------------- */
        .action-buttons {
            width: 100px;
        }

        .btn {
            padding: 5px;
            background-color: #007bff;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s ease;
            color: white;
            margin: 7px;
        }

        .btn:hover {
            background-color: rgb(132, 0, 255);
            color: #ffff;
        }

        .btn-2 {
            padding: 5px;
            background-color: red;
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

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            position: relative;
            top: 50%;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
        }

        .pagination a.active {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .pagination a:hover {
            background-color: red;
            border-color: red;
        }

        /* Media Queries */
        @media all and (max-width: 767px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th,
            td {
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <h1>Donor Account details</h1>
    <div class="search-container">
        <input type="text" id="searchInput" class="searchInput" placeholder="Search for names..">
        <button onclick="searchTable()" class="search-button">Search</button>
        <button onclick="clearSearch()" class="clear-button">Clear</button>
    </div>

    <!-- Donor Table -->
    <div class="user-table-container">
        <h2>Donors</h2>
        <table>
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Street</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Country</th>
                    <th>Password</th>
                    <th>Gender</th>
                    <th>Funding Type</th>
                    <th>Donation Amount</th>
                    <th>Payment Type</th>
                    <th>UPI ID</th>
                    <th>Card Number</th>
                    <th>Expiry</th>
                    <th>CVV</th>
                    <th>Childhood Pet</th>
                    <th>Vacation Spot</th>
                    <th>Hobby</th>
                    <th>Account Created on</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $result = $conn->query("SELECT * FROM donor_details");

                     if ($result->num_rows > 0) {
                         while ($row = $result->fetch_assoc()) {
                             echo "<tr>";
                             echo '<td>' . $serialNumber . '</td>';
                             $serialNumber++;
                             echo "<td>{$row['donor_user_id']}</td>";
                             echo "<td>{$row['donor_user_name']}</td>";
                             echo "<td>{$row['donor_user_email']}</td>";
                             echo "<td>{$row['donor_user_phone']}</td>";
                             echo "<td>{$row['donor_user_street']}</td>";
                             echo "<td>{$row['donor_user_city']}</td>";
                             echo "<td>{$row['donor_user_state']}</td>";
                             echo "<td>{$row['donor_user_zip']}</td>";
                             echo "<td>{$row['donor_user_country']}</td>";
                             echo "<td>{$row['donor_user_pwd']}</td>";
                             echo "<td>{$row['donor_user_gender']}</td>";
                             echo "<td>{$row['fundingType']}</td>";
                             echo "<td>{$row['donationAmount']}</td>";
                             echo "<td>{$row['paymentType']}</td>";
                             echo "<td>{$row['upiId']}</td>";
                             echo "<td>{$row['cardNumber']}</td>";
                             echo "<td>{$row['expiry']}</td>";
                             echo "<td>{$row['cvCode']}</td>";
                             echo "<td>{$row['childhood_pet']}</td>";
                             echo "<td>{$row['vacation_destination']}</td>";
                             echo "<td>{$row['favourite_hobby']}</td>";
                             echo "<td>{$row['submission_date']}</td>";
                             echo '<td class="two-btn">';
                             echo '<div><a href="admin_userprof_update_sep.php?id=' . $row['donor_user_id'] . '&table=donor_details" class="btn">Update</a> </div> ---';
                             echo '<div><a href="?delete=' . $row['donor_user_id'] . '" onclick="return confirmDelete();" class="btn-2">Delete</a></div>';;
                             echo '</td>';
                             echo "</tr>";
                         }
                     } else {
                         echo "<tr><td colspan='5'>No records found</td></tr>";
                     }
                     ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this user?");
    }

    function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();

        // Search in the table
        table = document.querySelector('.user-table-container table');
        tr = table.getElementsByTagName('tr');
        for (i = 1; i < tr.length; i++) {
            var found = false; // Flag to check if any column contains the search term
            for (var col = 1; col < tr[i].cells.length; col++) { // Start from 1 to skip User ID
                td = tr[i].getElementsByTagName('td')[col];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break; // Exit inner loop if found in this row
                    }
                }
            }
            if (found) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }

    function clearSearch() {
        // Clear the search input field
        document.getElementById('searchInput').value = '';

        // Show all rows in the table
        var tableRows = document.querySelectorAll('.user-table-container table tbody tr');
        for (var i = 0; i < tableRows.length; i++) {
            tableRows[i].style.display = '';
        }
    }
</script>

</body>
</html>
