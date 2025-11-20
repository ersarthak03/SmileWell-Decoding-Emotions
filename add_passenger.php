<?php
session_start();
include 'db_config.php'; // Include your database configuration

// Check if the driver is logged in
if (!isset($_SESSION['driver_id'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$driver_id = $_SESSION['driver_id'];
$driver_name = $_SESSION['driver_name'];

// Get number of passengers
$num_passengers = intval($_POST['num_passengers']);

// Generate a unique table name for this driver
$table_name = "passengers_" . $driver_id;

// Create a new table for the driver if it doesn't exist
$create_table_query = "
    CREATE TABLE IF NOT EXISTS `$table_name` (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        passenger_name VARCHAR(255) NOT NULL,
        passenger_email VARCHAR(255) NOT NULL,
        passenger_phone VARCHAR(15) NOT NULL,
        starting_point VARCHAR(255) NOT NULL,
        ending_point VARCHAR(255) NOT NULL
    )";

if ($conn->query($create_table_query) === TRUE) {
    // Prepare the insert statement
    $insert_query = "INSERT INTO `$table_name` (passenger_name, passenger_email, passenger_phone, starting_point, ending_point) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    for ($i = 0; $i < $num_passengers; $i++) {
        // Get passenger details from the form
        $passenger_name = $_POST['passenger_name'][$i];
        $passenger_email = $_POST['passenger_email'][$i];
        $passenger_phone = $_POST['passenger_phone'][$i];
        $starting_point = $_POST['starting_point'][$i];
        $ending_point = $_POST['ending_point'][$i];

        // Bind parameters and execute
        $stmt->bind_param("sssss", $passenger_name, $passenger_email, $passenger_phone, $starting_point, $ending_point);
        $stmt->execute();
    }

     echo "
        <script>
            alert('Passengers added successfully!');
            window.location.href = 'dashboard.php?driver_id=" . $driver_id . "';
        </script>
    ";
    exit();
} else {
    echo "Error creating table: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
