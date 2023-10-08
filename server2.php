<?php
// server1.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $jsonData = json_decode($data, true);

    if ($jsonData !== null) {

        echo "Received JSON: ";
        print_r($jsonData);
        
// Set the timezone to Manila 
$timezone = new DateTimeZone('Asia/Manila'); 

// Create a new DateTime object with the current time and the specified timezone
$date = new DateTime('now', $timezone);

// Format the date as desired (in this case, as a string in the format "Y-m-d H:i:s")
$timestamp = $date->format("Y-m-d H:i:s");

        // Add the timestamp to the received data
        $jsonData['Timestamp'] = $timestamp;
        
        // Read the existing data from the data.json file, if it exists
        $existingData = file_exists('data2.json') ? file_get_contents('data2.json') : '[]';

        // Parse the existing data as JSON and convert it to a PHP array
        $dataArray = json_decode($existingData, true);

        // Append new sensor data to the PHP array
        $dataArray[] = array(
            'label' => 'Gas Sensor',
            'value' => $jsonData['Gas Sensor'],
            'timestamp' => $jsonData['Timestamp']
        );

        $dataArray[] = array(
            'label' => 'Smoke Sensor',
            'value' => $jsonData['Smoke Sensor'],
            'timestamp' => $jsonData['Timestamp']
        );

        $dataArray[] = array(
            'label' => 'Water Sensor',
            'value' => $jsonData['Water Level'],
            'timestamp' => $jsonData['Timestamp']
        );

        // Convert the PHP array back to JSON
        $jsonString = json_encode($dataArray, JSON_PRETTY_PRINT);

        // Write the updated JSON data back to the data.json file
        file_put_contents('data2.json', $jsonString);

        // Add a newline after each entry in the response
        echo PHP_EOL;

        echo 'Data received and saved to data.json on the server.';
    } else {
        echo 'Invalid JSON data.';
    }
} else {
    echo 'Invalid request method.';
}

// Function to get the formatted timestamp
function getFormattedTimestamp() {
    date_default_timezone_set('UTC'); // Set the timezone to UTC
    return date('Y-m-d H:i:s', time());
}
?>
