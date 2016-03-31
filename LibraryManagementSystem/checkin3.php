<?php
$loanid = $_GET["loanid"];
$date_in = $_GET["date_in"];

$servername = "127.0.0.1";
$username = "root";
$password = "tiger";
$dbname = "books";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE book_loans SET date_in='$date_in' WHERE loan_id=$loanid";

if ($conn->query($sql) === TRUE) {
    echo "Checked in Successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
