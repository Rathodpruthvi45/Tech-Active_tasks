<?php


// Establish connection to the database
$con = mysqli_connect("localhost", "root", "", "second_task");

// Check if the connection was successful
if (!$con) {
    die("Database connection failed");
}

// Function to filter users by search term
function filterUsers($con, $searchTerm)
{
    $filteredUsers = array();

    // Construct the SQL query with search conditions
    $sql = "SELECT * FROM users WHERE user_First_Name LIKE '%$searchTerm%' OR user_email LIKE '%$searchTerm%'";

    // Execute the query
    $result = mysqli_query($con, $sql);

    // Check if the query was successful
    if ($result) {
        // Fetch and store the rows in the $filteredUsers array
        while ($row = mysqli_fetch_assoc($result)) {
            $filteredUsers[] = array(
                "firstName" => $row['user_First_Name'],
                "lastName" => $row['user_last_name'],
                "email" => $row['user_email']
            );
        }
    }

    return $filteredUsers;
}

// Function to paginate users
function paginateUsers($users, $page, $perPage)
{
    $totalUsers = count($users);
    $totalPages = ceil($totalUsers / $perPage);

    // Calculate start index and end index for the current page
    $startIndex = ($page - 1) * $perPage;
    $endIndex = min($startIndex + $perPage - 1, $totalUsers - 1);

    // Extract the users for the current page
    $pagedUsers = array_slice($users, $startIndex, $endIndex - $startIndex + 1);

    return array(
        "total" => $totalUsers,
        "page" => $page,
        "perPage" => $perPage,
        "totalPages" => $totalPages,
        "data" => $pagedUsers
    );
}

// Get parameters from the request
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Filter users based on search term
if (!empty($searchTerm)) {
    $filteredUsers = filterUsers($con, $searchTerm);
} else {
    // If search term is empty, fetch all users
    $sql = "SELECT * FROM users";
    $result = mysqli_query($con, $sql);

    // Check if the query was successful
    if ($result) {
        // Fetch and store all rows in the $filteredUsers array
        while ($row = mysqli_fetch_assoc($result)) {
            $filteredUsers[] = array(
                "firstName" => $row['user_First_Name'],
                "lastName" => $row['user_last_name'],
                "email" => $row['user_email']
            );
        }
    } else {
        die("Error fetching users");
    }
}

// Paginate the filtered users
$paginatedUsers = paginateUsers($filteredUsers, $page, $perPage);

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Return JSON response
echo json_encode($paginatedUsers);

// Close the database connection
mysqli_close($con);
?>