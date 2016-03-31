<?php

$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

  $query = "SELECT max(card_no) cardno FROM books.BORROWER";


  $result = $con->query($query);
  $row = $result->fetch_assoc();
  $cardno = substr($row["cardno"],2);
  $cardno= $cardno+1;
  $cardno = str_pad($cardno, 6, '0', STR_PAD_LEFT);
  $cardno = "ID".$cardno;


  $insertQuery = "INSERT into borrower values('".$cardno."','".$_GET["ssn"]."','".$_GET["fname"]."', '".$_GET["lname"]."', '".$_GET["email"]."', '".$_GET["address"]."', '".$_GET["city"]."', '".$_GET["state"]."', '".$_GET["phone"]."')";

  if ($con->query($insertQuery) === TRUE) {
echo "New Borrower Added Successfully with Card No: ".$cardno;
} else {
echo "Duplicate SSN Entry Found";
}



$con->close();



 ?>
