<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else { ?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | User Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
  </head>

  <body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
      <div class="container">
        <div class="row pad-botm">
          <div class="col-md-12">
            <h4 class="header-line">User DASHBOARD</h4>
          </div>
        </div>

        <div class="row">
          <!-- Books Listed -->
          <a href="listed-books.php">
            <div class="col-md-4 col-sm-4 col-xs-6">
              <div class="alert alert-success back-widget-set text-center">
                <i class="fa fa-book fa-5x"></i>
                <?php
                $sql = "SELECT id FROM tblbooks";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $listdbooks = $query->rowCount();
                ?>
                <h3><?php echo htmlentities($listdbooks); ?></h3>
                Books Listed
              </div>
            </div>
          </a>

          <!-- Books Not Returned Yet -->
          <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="alert alert-warning back-widget-set text-center">
              <i class="fa fa-recycle fa-5x"></i>
              <?php
              $rsts = 0;
              $sid = $_SESSION['stdid'];
              $sql2 = "SELECT id FROM tblissuedbookdetails WHERE studentid = :sid AND (retrunstatus = :rsts OR retrunstatus IS NULL)";
              $query2 = $dbh->prepare($sql2);
              $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
              $query2->bindParam(':rsts', $rsts, PDO::PARAM_INT); // Ensure $rsts is bound as an integer
              $query2->execute();
              $returnedbooks = $query2->rowCount();
              ?>
              <h3><?php echo htmlentities($returnedbooks); ?></h3>
              Books Not Returned Yet
            </div>
          </div>
          <!-- Issued Books -->
          <a href="issued-books.php">
            <div class="col-md-4 col-sm-4 col-xs-6">
              <div class="alert alert-success back-widget-set text-center">
                <i class="fa fa-book fa-5x"></i>
                <?php
                $sql3 = "SELECT id FROM tblissuedbookdetails WHERE studentid = :sid";
                $query3 = $dbh->prepare($sql3);
                $query3->bindParam(':sid', $sid, PDO::PARAM_STR);
                $query3->execute();
                $issuedBooks = $query3->rowCount();
                ?>
                <h3><?php echo htmlentities($issuedBooks); ?></h3>
                Issued Books
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
  </body>

  </html>
<?php } ?>