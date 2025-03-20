<?php
// MySQL server configuration
$servername = "capstone-database.cfukyi2mibhq.ap-south-1.rds.amazonaws.com";
$username = "Capstoneuser";
$password = "Akash123";
$dbname = "Capstone";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username entered by the user
$userInput = $_POST['Name'];

// Prepare the SQL statement to check username validity
$stmt = $conn->prepare("SELECT * FROM Capstone.customers WHERE Name = ?");
$stmt->bind_param("s", $userInput);
$stmt->execute();
$result = $stmt->get_result();

// Check if the username exists
if ($result->num_rows > 0) {
    include 'thankyou.html';
} else {
    echo "Username is invalid.";
}

// Close the connection
$conn->close();
?>
