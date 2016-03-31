
<html>
<head>

	<link href="css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
	<h1> Search Results</h1>
</body>
</html><?php
$isbn13 = $_GET["isbn13"];
$cardno = $_GET["cardno"];
$borrfname = $_GET["borrfname"];
$borrlname = $_GET["borrlname"];


$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


  $query = "SELECT
    Loan_id,
    ISBN13,
    Book.title bname,
    Fname,
    Lname,
    Card_no,
    Date_out,
    Due_date
FROM
    checked_out_books
        JOIN
    BORROWER USING (card_no)
        JOIN
    book USING (ISBN13)
WHERE
    ISBN13 LIKE '%$isbn13%' AND Card_no LIKE '%$cardno%'
        AND Fname LIKE '%$borrfname%'
        AND Lname LIKE '%$borrlname%'";


  $result = $con->query($query);

  if ($result && $result->num_rows > 0) {
    echo '<table><tr><th>Loan ID</th><th>ISBN13</th><th>Title</th><th>Borrower Name</th><th>Card No</th><th>Date Checked Out</th><th>Date Due</th></tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<tr><td><a href = "checkin2.php?loanid='.$row["Loan_id"].'">'.$row["Loan_id"].'</a></td><td>'.$row["ISBN13"].'</td><td>'.$row["bname"].'</td><td>'.$row["Fname"]." ".$row["Lname"].'</td><td>'.$row["Card_no"].'</td><td>'.$row["Date_out"].'</td><td>'.$row["Due_date"].'</td></tr>';
    }
    echo "</table>";
} else {
    echo "0 results";
}
  $con->close();

 ?>
