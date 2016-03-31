<?php

$cardno =  $_GET["cardno"];

$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
  or die ('Could not connect to the database server' . mysqli_connect_error());
$update = "UPDATE fines set paid = 1 where loan_id in
(select Loan_id from book_loans where card_no = '".$cardno."')";
if ($con->query($update) === TRUE) {
echo "Fine paid recorded Successfully";
} else {
echo "Error: " . $insertQuery . "<br>" . mysqli_error($con);
}
 ?>
