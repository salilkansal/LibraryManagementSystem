<?php
$loanid = $_GET["loanid"];

echo '<form method="get" action="checkin3.php">
<fieldset>
  <legend>Enter Check In Date:</legend>
  <input type="date" name="date_in" value = '.date('Y-m-d').'>
  <input type="hidden" name="loanid" value='.$loanid.'>
  <input type="submit">
</form>';

?>
