<?php
// Collect form data
$firstname = $_POST['firstname'];
$lastname  = $_POST['lastname'];
$email     = $_POST['email'];
$pass1     = $_POST['pass'];
$gender    = $_POST['gender'];
$contact   = $_POST['contact'];
$houseno   = $_POST['hno'];
$block     = $_POST['block'];
$street    = $_POST['street'];

// Database connection
$servername = "sql200.infinityfree.com";
$username   = "if0_39853898";
$password   = "miniteam8";
$database   = "if0_39853898_team8";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("❌ Connection failed: " . $con->connect_error);
}

// Generate unique Citizen ID (CIVIQ + 5 random digits)
$citizen_id = "CIVIQ" . rand(10000, 99999);

// Insert into database
$sql = "INSERT INTO citizen (citizen_id, firstname, lastname, email, pass, gender, contact, hno, block, street) 
        VALUES ('$citizen_id', '$firstname', '$lastname', '$email', '$pass1', '$gender', '$contact', '$houseno', '$block', '$street')";

if ($con->query($sql) === TRUE) {
    header("Location: login.html");
    exit();
} else {
    echo "❌ Registration failed: " . $con->error;
}

$con->close();
?>
