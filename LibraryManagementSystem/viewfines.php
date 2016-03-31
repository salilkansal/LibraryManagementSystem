<html>
<head>

	<link href="css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
	<h1> View Fines</h1>
</body>
</html><?php

$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());
  # Card_no, Borrower_Name, Total_Fine


$query = "SELECT Card_no, Borrower_name, Total_Fine from borrower_fine";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
  echo '<table align="center"><tr><th>Card No</th><th>Borrower Name</th><th>Total Fine Due</th></tr>';
  // output data of each row
  while($row = $result->fetch_assoc()) {
      echo '<tr><td><a href = "viewfines2.php?cardno='.$row["Card_no"].'">'.$row["Card_no"].'</a></td><td>'.$row["Borrower_name"].'</td><td>'.$row["Total_Fine"].'</td>';
  }
  echo "</table>";
} else {
  echo "No fines are there";
}



$con->close();



 ?>
