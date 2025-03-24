<?php
require_once("includes/config.php");
if (!empty($_POST["bookid"])) {
  $bookid = $_POST["bookid"];

  // Using lowercase table and field names, and OR operator for conditions
  $sql = "SELECT DISTINCT tblbooks.bookname, tblbooks.id, tblauthors.authorname, tblbooks.bookimage, tblbooks.isissued 
            FROM tblbooks 
            JOIN tblauthors ON tblauthors.id = tblbooks.authorid
            WHERE (isbnnumber = :bookid OR bookname LIKE '%$bookid%')";
  $query = $dbh->prepare($sql);
  $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  $cnt = 1;
  if ($query->rowCount() > 0) {
?>
    <table border="1">
      <tr>
        <?php
        foreach ($results as $result) { ?>
          <th style="padding-left:5%; width: 10%;">
            <img src="bookimg/<?php echo htmlentities($result->bookimage); ?>" width="120"><br />
            <?php echo htmlentities($result->bookname); ?><br />
            <?php echo htmlentities($result->authorname); ?><br />
            <?php if ($result->isissued == '1'): ?>
              <p style="color:red;">Book Already issued</p>
            <?php else: ?>
              <input type="radio" name="bookid" value="<?php echo htmlentities($result->id); ?>" required>
            <?php endif; ?>
          </th>
        <?php echo "<script>$('#submit').prop('disabled',false);</script>";
        } ?>
      </tr>
    </table>
  <?php
  } else { ?>
    <p>Record not found. Please try again.</p>
<?php
    echo "<script>$('#submit').prop('disabled',true);</script>";
  }
}
?>