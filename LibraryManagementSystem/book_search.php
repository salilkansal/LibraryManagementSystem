
<html>
<head>

	<link href="css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
	<h1> Search Results</h1>
</body>
</html><?php
$isbn = $_GET["isbn"];
$title = $_GET["title"];
$author = $_GET["author"];


$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="tiger";
$dbname="books";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


  $query = "SELECT
    table_A.*, Branch_id, Branch_name, No_of_copies, (No_of_copies -
    (select count(*) from book_loans where Book_loans.ISBN13=table_A.ISBN13 and Book_loans.Branch_id=library_branch.Branch_id and Book_loans.Date_in is null)) as 'Checkedout'
FROM
    library_branch
        JOIN
    BOOK_COPIES USING (branch_id),
    (SELECT
        ISBN13,
            Book.Title Bname,
            GROUP_CONCAT(TRIM(CONCAT(TRIM(CONCAT(TRIM(CONCAT(TRIM(CONCAT(AUTHORS.Title, ' ', AUTHORS.fname)), ' ', AUTHORS.Mname)), ' ', AUTHORS.Lname)), ' ', AUTHORS.Suffix))) Author
    FROM
        book
    JOIN BOOK_AUTHORS USING (ISBN13)
    JOIN AUTHORS USING (Author_id)
    WHERE
        Book.ISBN13 LIKE '%".$isbn."%'
            AND Book.Title LIKE '%".$title."%'
            AND Authors.Fullname LIKE '%".$author."%'
    GROUP BY ISBN13) table_A
WHERE
    ISBN13 = Book_id;";


  $result = $con->query($query);

  if ($result && $result->num_rows > 0) {
    echo '<table><tr><th>ISBN13</th><th>Title</th><th>Author</th><th>Branch ID</th><th>Branch Name</th><th>No of Copies</th><th>Available</th></tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["ISBN13"]."</td><td>".$row["Bname"]."</td><td>".$row["Author"]."</td><td>".$row["Branch_id"]."</td><td>".$row["Branch_name"]."</td><td>".$row["No_of_copies"]."</td><td>".$row["Checkedout"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
  $con->close();

 ?>
