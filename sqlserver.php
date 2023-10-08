<?php
// server.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $jsonData = json_decode($data, true);

    if ($jsonData !== null) {
        // Extract sensor data
        $gasSensorValue = $jsonData['Gas Sensor'];
        $smokeSensorValue = $jsonData['Smoke Sensor'];
        $waterSensorValue = $jsonData['Water Level'];

        // Establish a MySQL database connection
        $servername = "localhost"; // Change to your database server name
        $username = "root"; // Change to your MySQL username
        $password = ""; // Change to your MySQL password
        $database = "demo1"; // Change to your database name

        $conn = new mysqli($servername, $username, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert sensor data into the MySQL database
        $sql = "INSERT INTO sensor_logs (gas_sensor, smoke_sensor, water_sensor, timestamp)
                VALUES ('$gasSensorValue', '$smokeSensorValue', '$waterSensorValue', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "Data inserted into MySQL database successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo 'Invalid JSON data.';
    }
} else {
    echo 'Invalid request method.';
}
?>
