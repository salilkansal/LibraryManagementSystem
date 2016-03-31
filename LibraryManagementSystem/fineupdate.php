  <?php

  $host="127.0.0.1"; $port=3306; $socket=""; $user="root"; $password="tiger";
  $dbname="books";

  $con = new mysqli($host, $user, $password, $dbname, $port, $socket) or die
  ('Could not connect to the database server' . mysqli_connect_error());

  $query = "SELECT Loan_id, ISBN13, Branch_id, Card_no, Date_out, Due_date, Date_in from late_books";

  $result = $con->query($query);

  if ($result && $result->num_rows > 0) {
    # Loan_id, ISBN13, Branch_id, Card_no, Date_out, Due_date, Date_in
    # '2', '9780802769756', '1', 'ID000888','2015-09-24', '2015-10-07', '2015-10-08'
    while($row = $result->fetch_assoc()) {

     if(is_null($row['Date_in'])){
      //book is still out
      //check if row exists in fines for this loanid

      $query = "SELECT * from fines where loan_id = ".$row['Loan_id']."";
      $result1 = $con->query($query);
      $currDate = strtotime(date('Y-m-d'));
      $dueDate = strtotime($row['Due_date']);
      $fine = 0.25*($currDate-$dueDate)/86400;

      if ($result1 && $result1->num_rows > 0) {
                //if exists then update fine
        $fine_amt = "SELECT fine_amt from fines where Loan_id=".$row['Loan_id'];
        $res = $con->query($fine_amt);
        $orgFine = $res->fetch_assoc();
        if($orgFine["fine_amt"] <> $fine){
          $update = "UPDATE fines set fine_amt=".$fine." where Loan_id=".$row['Loan_id'];
          if($con->query($update) === TRUE)
            { echo "<br>Fine $".round($fine,2)." updated for Loan ID: ".$row['Loan_id']; }
          else { echo "<br>Error, could not update fine"; }
        }else{ echo "<br>Fine updation not required for Loan ID: ".$row['Loan_id'];}
      }
      else{
              //else insert new row with fine

        $insert = "INSERT into fines values('".$row['Loan_id']."','".$fine."',0)";
        if($con->query($insert) === TRUE)
          { echo "<br>Fine $".round($fine,2)." inserted for (Book not Returned) Loan ID: ".$row['Loan_id']; }
        else { echo "<br>Error, could not insert fine"; }
      }
    }
    else{

            //book returned
            //check if row exists in fines for this loanid
      $query = "SELECT * from fines where loan_id = ".$row['Loan_id'];
      $result1 = $con->query($query);

      if ($result1 && $result1->num_rows > 0) {
        //update existing fine row
        $returnedFine = $result1->fetch_assoc();
        if($returnedFine['paid']==0){
          $Datein = strtotime($row['Date_in']);
          $dueDate = strtotime($row['Due_date']);
          $fine = 0.25*($Datein-$dueDate)/86400;

          if($returnedFine['fine_amt'] <> round($fine,2)){
            $update = "UPDATE fines set fine_amt=".round($fine,2)." where Loan_id=".$row['Loan_id'];
            if($con->query($update) === TRUE)
              { echo "<br>Fine $".round($fine,2)." updated for Loan ID: ".$row['Loan_id']; }
            else { echo "<br>Error, could not update fine"; }
          }else{ echo "<br>Fine update not required for Loan ID: ".$row['Loan_id'];}
        }
        else{ echo "<br>Fine Already Paid for Loan ID:".$row['Loan_id'];}
      }
            else{ //else insert new row with fine
              $Datein = strtotime($row['Date_in']);
              $dueDate = strtotime($row['Due_date']);
              $fine = 0.25*($Datein-$dueDate)/86400;
              $insert = "INSERT into fines values('".$row['Loan_id']."','".$fine."',0)";
              if($con->query($insert) === TRUE)
                { echo "<br>Fine $".round($fine,2)." inserted for (Book Returned) Loan ID: ".$row['Loan_id']; }
              else { echo "<br>Error, could not insert fine"; }
            }
          }
        }
      }
      else{
        echo "<br>No late books are there";
      }
      $con->close();

      ?>
