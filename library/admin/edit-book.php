<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['update'])) {

        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author   = $_POST['author'];
        $isbn     = $_POST['isbn'];
        $price    = $_POST['price'];
        $bookid   = intval($_GET['bookid']);

        // Update query using lowercase field names
        $sql = "UPDATE tblbooks SET bookname = :bookname, catid = :category, authorid = :author, bookprice = :price WHERE id = :bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Book info updated successfully');</script>";
        echo "<script>window.location.href='manage-books.php'</script>";
    }
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Library Management System | Edit Book</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
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
                        <h4 class="header-line">Edit Book</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Book Info
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">
                                    <?php
                                    $bookid = intval($_GET['bookid']);
                                    $sql = "SELECT tblbooks.bookname, tblcategory.categoryname, tblcategory.id as cid, tblauthors.authorname, tblauthors.id as athrid, tblbooks.isbnnumber, tblbooks.bookprice, tblbooks.id as bookid, tblbooks.bookimage 
        FROM tblbooks 
        JOIN tblcategory ON tblcategory.id = tblbooks.catid 
        JOIN tblauthors ON tblauthors.id = tblbooks.authorid 
        WHERE tblbooks.id = :bookid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <input type="hidden" name="curremtimage" value="<?php echo htmlentities($result->bookimage); ?>">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Image</label>
                                                    <img src="bookimg/<?php echo htmlentities($result->bookimage); ?>" width="100">
                                                    <a href="change-bookimg.php?bookid=<?php echo htmlentities($result->bookid); ?>">Change Book Image</a>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Book Name<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result->bookname); ?>" required />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category<span style="color:red;">*</span></label>
                                                    <select class="form-control" name="category" required="required">
                                                        <option value="<?php echo htmlentities($result->cid); ?>">
                                                            <?php $catname = $result->categoryname;
                                                            echo htmlentities($catname); ?>
                                                        </option>
                                                        <?php
                                                        $status = 1;
                                                        $sql1 = "SELECT * FROM tblcategory WHERE status = :status";
                                                        $query1 = $dbh->prepare($sql1);
                                                        $query1->bindParam(':status', $status, PDO::PARAM_STR);
                                                        $query1->execute();
                                                        $resultss = $query1->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query1->rowCount() > 0) {
                                                            foreach ($resultss as $row) {
                                                                if ($catname == $row->categoryname) {
                                                                    continue;
                                                                } else { ?>
                                                                    <option value="<?php echo htmlentities($row->id); ?>">
                                                                        <?php echo htmlentities($row->categoryname); ?>
                                                                    </option>
                                                        <?php }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Author<span style="color:red;">*</span></label>
                                                    <select class="form-control" name="author" required="required">
                                                        <option value="<?php echo htmlentities($result->athrid); ?>">
                                                            <?php $athrname = $result->authorname;
                                                            echo htmlentities($athrname); ?>
                                                        </option>
                                                        <?php
                                                        $sql2 = "SELECT * FROM tblauthors";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->execute();
                                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query2->rowCount() > 0) {
                                                            foreach ($result2 as $ret) {
                                                                if ($athrname == $ret->authorname) {
                                                                    continue;
                                                                } else {
                                                        ?>
                                                                    <option value="<?php echo htmlentities($ret->id); ?>">
                                                                        <?php echo htmlentities($ret->authorname); ?>
                                                                    </option>
                                                        <?php
                                                                }
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ISBN Number<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="isbn" value="<?php echo htmlentities($result->isbnnumber); ?>" readonly />
                                                    <p class="help-block">An ISBN is an International Standard Book Number. ISBN must be unique</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Price in USD<span style="color:red;">*</span></label>
                                                    <input class="form-control" type="text" name="price" value="<?php echo htmlentities($result->bookprice); ?>" required="required" />
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="col-md-12">
                                        <button type="submit" name="update" class="btn btn-info">Update</button>
                                    </div>
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