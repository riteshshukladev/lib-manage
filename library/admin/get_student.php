<?php
require_once("includes/config.php");
if (!empty($_POST["studentid"])) {
  $studentid = strtoupper($_POST["studentid"]);

  // Query using lowercase field names
  $sql = "SELECT fullname, status, emailid, mobilenumber FROM tblstudents WHERE studentid = :studentid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);

  if ($query->rowCount() > 0) {
    foreach ($results as $result) {
      if ($result->status == 0) {
        echo "<span style='color:red'>Student ID Blocked</span><br />";
        echo "<b>Student Name - </b>" . htmlentities($result->fullname);
        echo "<script>$('#submit').prop('disabled',true);</script>";
      } else {
        echo htmlentities($result->fullname) . "<br />";
        echo htmlentities($result->emailid) . "<br />";
        echo htmlentities($result->mobilenumber);
        echo "<script>$('#submit').prop('disabled',false);</script>";
      }
    }
  } else {
    echo "<span style='color:red'>Invalid Student ID. Please enter a valid student id.</span>";
    echo "<script>$('#submit').prop('disabled',true);</script>";
  }
}
