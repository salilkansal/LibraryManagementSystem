<?php
function addDayswithdate($date,$days){

    $date = strtotime("+".$days." days", strtotime($date));
    return  date("Y-m-d", $date);

}
$isbn = $_GET["isbn13"];
$branchid = $_GET["branchid"];
$cardno = $_GET["cardno"];

if ($isbn=="" || $branchid=="" || $cardno==""){

  header('Location: checkout.html');
  exit;
}

else{

  $host="127.0.0.1";
  $port=3306;
  $socket="";
  $user="root";
  $password="tiger";
  $dbname="books";

  $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
  	or die ('Could not connect to the database server' . mysqli_connect_error());
    $query = "SELECT card_no from borrower where card_no='".$cardno."'";

    $result = $con->query($query);

    if ($result && $result->num_rows > 0) {


      $query = "SELECT No_of_copies from BOOK_COPIES where branch_id='".$branchid."' and book_id='".$isbn."'";
      $result = $con->query($query);

      if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $numofcopies = $row["No_of_copies"];
        $query = "SELECT count(*) Issued  from book_loans where ISBN13='".$isbn."' and branch_id = '".$branchid."' and Date_in is null;";

        $result = $con->query($query);
        if($result && $result->num_rows > 0){
          $row = $result->fetch_assoc();
          $issued = $row["Issued"];
          if($issued<$numofcopies){
              $query = "SELECT count(*) borrowed from book_loans where card_no = '$cardno' and Date_in is null";
              $result = $con->query($query);
              $row = $result->fetch_assoc();
              $borrowed = $row["borrowed"];

            if($borrowed <3){
            $currDate = date('Y/m/d');
            $query = "SELECT max(Loan_id) loanid from book_loans;";
            $result = $con->query($query);
            $row = $result->fetch_assoc();
            $loanid = $row["loanid"];
            $loanid = $loanid + 1;
            $dueDate = addDayswithdate($currDate, '14');
            $insertQuery = "INSERT into book_loans values($loanid,$isbn,$branchid, '$cardno', '$currDate', '$dueDate', null)";

            if ($con->query($insertQuery) === TRUE) {
  echo "Book Issued Successfully";
} else {
  echo "Error: " . $insertQuery . "<br>" . mysqli_error($con);
}
}
else{
  echo "The borrower has already borrowed 3 books";
}
          }
          else{
            echo "All copies of the book checked out";
          }
        }
      }
      else{
        echo "Invalid Book ISBN no or Branch ID";
      }

  } else {
      echo "Invalid Card Number";
  }




  $con->close();
}



 ?>
