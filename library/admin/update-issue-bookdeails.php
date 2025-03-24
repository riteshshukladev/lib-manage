<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
} else {
    if (isset($_POST['return'])) {
        $rid = intval($_GET['rid']);
        $fine = intval($_POST['fine']);
        $rstatus = 1;
        $bookid = intval($_POST['bookid']);

        // First update: update tblissuedbookdetails record
        $sql1 = "UPDATE tblissuedbookdetails SET fine = :fine, retrunstatus = :rstatus WHERE id = :rid";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':rid', $rid, PDO::PARAM_INT);
        $query1->bindParam(':fine', $fine, PDO::PARAM_INT);
        $query1->bindParam(':rstatus', $rstatus, PDO::PARAM_INT);
        $query1->execute();

        // Second update: update tblbooks record to mark as not issued
        $sql2 = "UPDATE tblbooks SET isissued = 0 WHERE id = :bookid";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':bookid', $bookid, PDO::PARAM_INT);
        $query2->execute();

        $_SESSION['msg'] = "Book returned successfully";
        header('location:manage-issued-books.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Issued Book Details</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT  -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script>
        // Function for get student name
        function getstudent() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_student.php",
                data: 'studentid=' + $("#studentid").val(),
                type: "POST",
                success: function(data) {
                    $("#get_student_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
        // Function for book details
        function getbook() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_book.php",
                data: 'bookid=' + $("#bookid").val(),
                type: "POST",
                success: function(data) {
                    $("#get_book_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
    <style type="text/css">
        .others {
            color: red;
        }
    </style>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Issued Book Details</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Issued Book Details
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php
                                $rid = intval($_GET['rid']);
                                $sql = "SELECT tblstudents.studentid, tblstudents.fullname, tblstudents.emailid, tblstudents.mobilenumber, 
                                             tblbooks.bookname, tblbooks.isbnnumber, tblissuedbookdetails.issuesdate, tblissuedbookdetails.returndate, 
                                             tblissuedbookdetails.id as rid, tblissuedbookdetails.fine, tblissuedbookdetails.retrunstatus, 
                                             tblbooks.id as bid, tblbooks.bookimage 
                                      FROM tblissuedbookdetails 
                                      JOIN tblstudents ON tblstudents.studentid = tblissuedbookdetails.studentid 
                                      JOIN tblbooks ON tblbooks.id = tblissuedbookdetails.bookid 
                                      WHERE tblissuedbookdetails.id = :rid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':rid', $rid, PDO::PARAM_INT);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                ?>
                                        <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid); ?>">
                                        <h4>Student Details</h4>
                                        <hr />
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Student ID :</label>
                                                <?php echo htmlentities($result->studentid); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Student Name :</label>
                                                <?php echo htmlentities($result->fullname); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Student Email Id :</label>
                                                <?php echo htmlentities($result->emailid); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Student Contact No :</label>
                                                <?php echo htmlentities($result->mobilenumber); ?>
                                            </div>
                                        </div>
                                        <h4>Book Details</h4>
                                        <hr />
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Image :</label>
                                                <img src="bookimg/<?php echo htmlentities($result->bookimage); ?>" width="120">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Name :</label>
                                                <?php echo htmlentities($result->bookname); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ISBN :</label>
                                                <?php echo htmlentities($result->isbnnumber); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Issued Date :</label>
                                                <?php echo htmlentities($result->issuesdate); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Returned Date :</label>
                                                <?php
                                                if ($result->returndate == "") {
                                                    echo htmlentities("Not returned yet");
                                                } else {
                                                    echo htmlentities($result->returndate);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Fine (in USD) :</label>
                                                <?php
                                                if ($result->fine == "") { ?>
                                                    <input class="form-control" type="text" name="fine" id="fine" required />
                                                <?php } else {
                                                    echo htmlentities($result->fine);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if ($result->retrunstatus == 0) { ?>
                                            <button type="submit" name="return" id="submit" class="btn btn-info">Return Book</button>
                                        <?php } ?>
                                <?php
                                    }
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>