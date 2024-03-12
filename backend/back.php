<?php
// Establishing connection to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$database = "car_booking_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching cars data
$carsQuery = "SELECT * FROM cars";
$carsResult = $conn->query($carsQuery);

if ($carsResult->num_rows > 0) {
    // Creating an array to store cars data
    $cars = array();
    while ($row = $carsResult->fetch_assoc()) {
        $cars[] = $row;
    }
    echo json_encode($cars);
} else {
    echo "No cars found.";
}

// Fetching bookings data based on selected car
$selectedCarId = isset($_GET['car_id']) ? $_GET['car_id'] : null;
if ($selectedCarId) {
    $bookingsQuery = "SELECT bookings.*, customers.name AS customer_name FROM bookings
                        INNER JOIN customers ON bookings.customer_id = customers.customer_id
                        WHERE bookings.car_id = $selectedCarId";
    $bookingsResult = $conn->query($bookingsQuery);

    if ($bookingsResult->num_rows > 0) {
        // Creating an array to store bookings data
        $bookings = array();
        while ($row = $bookingsResult->fetch_assoc()) {
            $bookings[] = $row;
        }
        // Display bookings as HTML
        foreach ($bookings as $booking) {
            echo "<div>{$booking['customer_name']} (Booking Date: {$booking['booking_date']})</div>";
        }
    } else {
        echo "No bookings found for this car.";
    }
}

// Closing MySQL connection
$conn->close();
?>
