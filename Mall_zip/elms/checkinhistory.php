<?php
session_start();
// error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {


    if(isset($_GET['del'])){
        $delQuery = "DELETE FROM `tblcheckin` WHERE `tblcheckin`.`id` = :del";
        $del = $dbh->prepare($delQuery);
        $del->bindParam(':del', $_GET['del'], PDO::PARAM_STR);
        $del->execute();

        // echo "<pre>";
        // print_r($_GET);
        // echo "</pre>";

        // $month=$_GET['date'];

        $timestamp    = strtotime($_GET['date']);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        // SELECT id FROM things 
        // WHERE MONTH(happened_at) = 1 AND YEAR(happened_at) = 2009


        $updQuery = "UPDATE `tblpayment` SET `presentdays` = presentdays-1 WHERE `tblpayment`.`emp_id` = :emp AND MONTH(fromdate)=:month AND YEAR(fromdate) = :year";
        $upd = $dbh->prepare($updQuery);
        $upd->bindParam(':emp', $_GET['emp'], PDO::PARAM_STR);
        $upd->bindParam(':month', $month, PDO::PARAM_STR);
        $upd->bindParam(':year', $year, PDO::PARAM_STR);
        $upd->execute();

        header('location:checkinhistory.php');


    }


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Employee | Check In History</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main  class="mn-inner">
       
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Check In History</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Check In History</span>
                            <?php if (isset($msg)) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="120">From Time</th>
                                        <th>To Time</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $eid = $_SESSION['eid'];
                                    // $sql = "SELECT LeaveType,ToDate,FromDate,Description,PostingDate,AdminRemarkDate,AdminRemark,Status from tblleaves where emp_id=:eid";
                                    $sql = "SELECT * from tblcheckin where emp_id=:eid ORDER BY `id` DESC";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    // echo "<pre>";
                                    // print_r($results);
                                    // echo "</pre>";
                                    // exit();

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->from_time); ?></td>
                                                <td><?php echo htmlentities($result->to_time); ?></td>
                                                <td><?php echo htmlentities($result->checkin_date); ?></td>
                                                <td><?php echo htmlentities($result->description); ?></td>
                                        <?php //echo date("Y-m-d");
                                        // echo $result->checkin_date;
                                        ?>
                                                <td><?php if ($result->checkin_date == date("Y-m-d")) {
                                                        echo "<a href=\"checkinhistory.php?del=$result->id&emp=$result->emp_id&date=$result->checkin_date\">Delete</a>";
                                                        // echo "HIII";
                                                    } else {

                                                        echo htmlentities('Delete Freezed');
                                                    }

                                                    ?></td>
                                          

                                            </tr>
                                    <?php $cnt++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        </div>
        <div class="left-sidebar-hover"></div>

        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>

    </body>

    </html>
<?php } ?>