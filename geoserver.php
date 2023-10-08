<?php
//  session_start();
// if(!isset($_SESSION['username'])){
    
// }

// Replace these credentials with your MySQL database details
$host = 'localhost';
$dbname = 'demo1';
$username = 'root';
$password = '';

// Establish a connection to the MySQL database using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// if(isset($_SESSION['username'])){
//     $usn = $_SESSION['username'];
//     $qry = "SELECT * FROM residents1 where username = '$usn' ";
//     $query = mysqli_query($conn, $qry);
//     while($usndata = mysqli_fetch_assoc($query)){
//     $usid = $usndata['id'];
//     }
// }

// echo $usid;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Check if the ESP8266 is requesting data
if (isset($_GET['action']) && $_GET['action'] === 'fetchData') {
    // Define your SQL query here, for example:
    $sql = "SELECT geo_url, address, contact FROM residents1 WHERE id = 76";

    // Execute the query
    $result = $conn->query($sql);

    // Fetch all data from the result set as an associative array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Convert the data to JSON format and send it back to the ESP8266
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Handle other API actions or requests here, if needed
    // For example, you can implement other endpoints to handle data insertion, updates, or other operations.
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
