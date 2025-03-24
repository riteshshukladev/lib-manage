<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Code for block student    
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "UPDATE tblstudents SET status = :status WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }

    // Code for active student
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "UPDATE tblstudents SET status = :status WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Library Management System | Student History</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- DATATABLE STYLE  -->
        <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT  -->
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
                        <?php $sid = $_GET['stdid']; ?>
                        <h4 class="header-line">#<?php echo $sid; ?> Book Issued History</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $sid; ?> Details
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <?php
                                    $sql = "SELECT 
                                            tblstudents.studentid, 
                                            tblstudents.fullname, 
                                            tblstudents.emailid, 
                                            tblstudents.mobilenumber, 
                                            tblbooks.bookname, 
                                            tblbooks.isbnnumber, 
                                            tblissuedbookdetails.issuesdate, 
                                            tblissuedbookdetails.returndate, 
                                            tblissuedbookdetails.id as rid, 
                                            tblissuedbookdetails.fine, 
                                            tblissuedbookdetails.retrunstatus, 
                                            tblbooks.id as bid, 
                                            tblbooks.bookimage 
                                        FROM tblissuedbookdetails 
                                        JOIN tblstudents ON tblstudents.studentid = tblissuedbookdetails.studentid 
                                        JOIN tblbooks ON tblbooks.id = tblissuedbookdetails.bookid 
                                        WHERE tblstudents.studentid = :sid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    ?>
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Issued Book</th>
                                                <th>Issued Date</th>
                                                <th>Returned Date</th>
                                                <th>Fine (if any)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <tr class="odd gradeX">
                                                        <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                        <td class="center"><?php echo htmlentities($result->studentid); ?></td>
                                                        <td class="center"><?php echo htmlentities($result->fullname); ?></td>
                                                        <td class="center"><?php echo htmlentities($result->bookname); ?></td>
                                                        <td class="center"><?php echo htmlentities($result->issuesdate); ?></td>
                                                        <td class="center">
                                                            <?php
                                                            if ($result->returndate == '') {
                                                                echo htmlentities("Not returned yet");
                                                            } else {
                                                                echo htmlentities($result->returndate);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="center">
                                                            <?php
                                                            if ($result->returndate == '') {
                                                                echo htmlentities("Not returned yet");
                                                            } else {
                                                                echo htmlentities($result->fine);
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $cnt++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- End Advanced Tables -->
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
        <!-- DATATABLE SCRIPTS -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>