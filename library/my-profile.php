<?php
session_start();
include('includes/config.php');
error_reporting(0);
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $sid = $_SESSION['stdid'];
        $fname = $_POST['fullanme'];
        $mobileno = $_POST['mobileno'];

        // Corrected column names to lowercase
        $sql = "UPDATE tblstudents SET fullname = :fname, mobilenumber = :mobileno WHERE studentid = :sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Your profile has been updated")</script>';
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Library Management System | My Profile</title>
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
                        <h4 class="header-line">My Profile</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-md-offset-1">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                My Profile
                            </div>
                            <div class="panel-body">
                                <form name="signup" method="post">
                                    <?php
                                    $sid = $_SESSION['stdid'];
                                    // Corrected column names to lowercase
                                    $sql = "SELECT studentid, fullname, emailid, mobilenumber, regdate, updationdate, status 
                                        FROM tblstudents  
                                        WHERE studentid = :sid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <div class="form-group">
                                                <label>Student ID : </label>
                                                <?php echo htmlentities($result->studentid); ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Reg Date : </label>
                                                <?php echo htmlentities($result->regdate); ?>
                                            </div>
                                            <?php if ($result->updationdate != "") { ?>
                                                <div class="form-group">
                                                    <label>Last Updation Date : </label>
                                                    <?php echo htmlentities($result->updationdate); ?>
                                                </div>
                                            <?php } ?>

                                            <div class="form-group">
                                                <label>Profile Status : </label>
                                                <?php if ($result->status == 1) { ?>
                                                    <span style="color: green">Active</span>
                                                <?php } else { ?>
                                                    <span style="color: red">Blocked</span>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Enter Full Name</label>
                                                <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->fullname); ?>" autocomplete="off" required />
                                            </div>

                                            <div class="form-group">
                                                <label>Mobile Number :</label>
                                                <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($result->mobilenumber); ?>" autocomplete="off" required />
                                            </div>

                                            <div class="form-group">
                                                <label>Enter Email</label>
                                                <input class="form-control" type="email" name="email" id="emailid" value="<?php echo htmlentities($result->emailid); ?>" autocomplete="off" required readonly />
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <button type="submit" name="update" class="btn btn-primary" id="submit">Update Now </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>