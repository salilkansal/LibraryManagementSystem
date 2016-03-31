<html>
<head>

	<link href="css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
	<h1> View Borrowers</h1>
</body>
</html>
<?php


$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


  $query = "SELECT  Card_no, Ssn, Fname, Lname, EmailID, Address, City, State, Phone FROM books.BORROWER";


  $result = $con->query($query);

  if ($result && $result->num_rows > 0) {
    echo '<table><tr><th>Card No</th><th>SSN</th><th>First Name</th><th>Last Name</th><th>Email ID</th><th>Address</th><th>City</th><th>State</th><th>Phone</th></tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<tr><td>'.$row["Card_no"].'</td><td>'.$row["Ssn"].'</td><td>'.$row["Fname"].'</td><td>'.$row["Lname"].'</td><td>'.$row["EmailID"].'</td><td>'.$row["Address"].'</td><td>'.$row["City"].'</td><td>'.$row["State"].'</td><td>'.$row["Phone"].'</td></tr>';
    }
    echo "</table>";
  } else {
    echo "0 results";
  }

$con->close();


 ?>
