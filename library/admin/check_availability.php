<?php
require_once("includes/config.php");
// Code to check ISBN availability
if (!empty($_POST["isbn"])) {
    $isbn = $_POST["isbn"];
    $sql = "SELECT id FROM tblbooks WHERE isbnnumber = :isbn";
    $query = $dbh->prepare($sql);
    $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo "<span style='color:red'>ISBN already exists with another book.</span>";
        echo "<script>$('#add').prop('disabled',true);</script>";
    } else {
        echo "<script>$('#add').prop('disabled',false);</script>";
    }
}
