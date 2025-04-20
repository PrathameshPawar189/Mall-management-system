<?php
session_start();
error_reporting(1);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['add_time'])) {
        extract($_POST);
        $empid = $_SESSION['eid'];

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";


        // echo $sqlCheck = "SELECT * FROM `tblcheckin` WHERE `checkin_date` = $check_in_date AND `emp_id` = $empid";

        $sqlCheck = "SELECT * FROM `tblcheckin` WHERE `checkin_date` = :check_in_date AND `emp_id` = :empid";
        $sqlChk = $dbh->prepare($sqlCheck);

        $sqlChk->bindParam(':check_in_date', $check_in_date, PDO::PARAM_STR);
        $sqlChk->bindParam(':empid', $empid, PDO::PARAM_STR);

        $sqlChk->execute();
        
        if($sqlChk->rowCount() == 0){
            // if(1){
        
                function countWeekendDays($month, $year){
                    $daytime = strtotime(date($year."/".$month."/01 00:00:01"));
                    $daysOfMonth = date("t", $daytime);
                    $weekdays = 0;
                    for ($day=1;  $day <= $daysOfMonth; $day++) { 
                        $time = strtotime(date($year.'/'.$month.'/'.$day.' 00:00:01'));
                        $dayStr = date('l', $time);
                        if ($dayStr == 'Saturday' || $dayStr == 'Sunday') {
                            $weekdays++;
                        }
                    }
            
                    return $weekdays;
                }

                // echo countWeekendDays('5', '2022');
        
        if ($fromtime < $totime) {
            $lastDate = date('Y-m-t');
            $firstDate = date('Y-m-01');
            $month = date('m');
            $year = date('Y');
            $nodays = date('t');
            $weekends= countWeekendDays($month, $year);

            $working_days = $nodays - $weekends;
    
            $paymentCheck = "SELECT * FROM `tblpayment` WHERE `emp_id` = :empid AND fromdate=:fromdate AND todate=:todate";
            $paymentCh = $dbh->prepare($paymentCheck);

            $paymentCh->bindParam(':empid', $empid, PDO::PARAM_STR);
            $paymentCh->bindParam(':fromdate', $firstDate, PDO::PARAM_STR);
            $paymentCh->bindParam(':todate', $lastDate, PDO::PARAM_STR);
            $paymentCh->execute();


             $paymentCh->rowCount();


            if($paymentCh->rowCount() == 0){
            $paymentInsert = "INSERT INTO `tblpayment` (`id`, `fromdate`, `todate`, `paiddate`, `emp_id`, `presentdays`, `mode`,`workingdays`) VALUES (NULL, :fromdate, :todate, NULL, :empid, '0', '',:workingdays);";
            $paymentIn = $dbh->prepare($paymentInsert);
            $paymentIn->bindParam(':fromdate', $firstDate, PDO::PARAM_STR);
            $paymentIn->bindParam(':todate', $lastDate, PDO::PARAM_STR);
            $paymentIn->bindParam(':empid', $empid, PDO::PARAM_STR);
            $paymentIn->bindParam(':workingdays',$working_days,PDO::PARAM_STR);
            $paymentIn->execute();
            }

            

        $sql = "INSERT INTO `tblcheckin` (`from_time`, `to_time`, `checkin_date`, `description`, `emp_id`) VALUES (:fromtime, :totime, :check_in_date ,:description, :empid);";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fromtime', $fromtime, PDO::PARAM_STR);
        $query->bindParam(':totime', $totime, PDO::PARAM_STR);
        $query->bindParam(':check_in_date', $check_in_date, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':empid', $empid, PDO::PARAM_STR);
        // $query->bindParam(':auto_id',$auto_id,PDO::PARAM_STR);
        $query->execute();

        $updateCount = "UPDATE `tblpayment` SET `presentdays` =(SELECT COUNT(*) FROM `tblcheckin` WHERE `checkin_date` BETWEEN :fromdate AND :todate AND emp_id=:empid) WHERE `tblpayment`.`emp_id` = :empid AND  fromdate=:fromdate AND todate=:todate;";
        $updateC = $dbh->prepare($updateCount);

        $updateC->bindParam(':fromdate', $firstDate, PDO::PARAM_STR);
        $updateC->bindParam(':todate', $lastDate, PDO::PARAM_STR);
        $updateC->bindParam(':empid', $empid, PDO::PARAM_STR);
        $updateC->execute();




        // $lastInsertId = $updateC->lastInsertId();
        if ($updateC->execute()) {
            $msg = "Time Added successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
        }else{
            $error = " fromtime should be greater than totime ";
            
        }
    }else{
        $error = "Time already added for $check_in_date";

    }

}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Xion mall staff managing website | Add Time</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
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
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Add Check In Check Out Time</div>
                </div>
                <div class="col s12 m12 l8">
                    <div class="card">
                        <div class="card-content">
                            <form id="example-form" method="post" name="addemp">
                                <div>
                                    <h3>Add Check In Check Out Time</h3>
                                    <section>
                                        <div class="wizard-content">
                                            <div class="row">
                                                <div class="col m12">
                                                    <div class="row">
                                                        <?php if (isset($error)) { ?><div class="errorWrap"><strong>ERROR </strong>:<?php echo htmlentities($error); ?> </div><?php } else if (isset($msg)) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>


                                                        <div class="input-field col  s12">
                                                            <!-- <label for="date">Date</label>     -->

                                                            <?php //echo $_POST['check_in_date']; ?>
                                                            <!-- <input id="check_in_date" name="check_in_date" value="<?php //if(isset($_POST['check_in_date'])){echo date('Y-m-d',strtotime($_POST['check_in_date']));}?>" class="masked" type="date" data-inputmask="'alias': 'date'" required> -->

                                                            <input id="check_in_date" name="check_in_date" value="<?php if(isset($_POST['check_in_date'])){echo date('Y-m-d',strtotime($_POST['check_in_date']));}?>" type='date'>

                                                        </div>


                                                        <div class="input-field col m6 s12">
                                                            <!-- <label for="fromdate">From  Time</label> -->
                                                            <input placeholder="" id="" name="fromtime" class="" type="time" value="<?php if(isset($_POST['fromtime'])){echo $fromtime;}?>" required>
                                                            <!-- data-inputmask="'alias': 'date'" -->
                                                        </div>
                                                        <div class="input-field col m6 s12">
                                                            <!-- <label for="todate">To Time</label> -->
                                                            <input placeholder="" id="" name="totime" class="" type="time" value="<?php if(isset($_POST['totime'])){echo $totime;}?>" required>
                                                            <!-- data-inputmask="'alias': 'date'" -->
                                                        </div>
                                                        <div class="input-field col m12 s12">
                                                            <label for="birthdate">Description</label>

                                                            <textarea id="textarea1" name="description" class="materialize-textarea" length="250" required><?php if(isset($_POST['description'])){echo $description;}?></textarea>
                                                        </div>
                                                    </div>
                                                    <button type="submit" name="add_time" id="add_time" class="waves-effect waves-light btn indigo m-b-xs">Add Time</button>

                                                </div>
                                            </div>
                                    </section>


                                    </section>
                                </div>
                            </form>
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
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/form_elements.js"></script>
        <script src="assets/js/pages/form-input-mask.js"></script>
        <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    </body>

    </html>
<?php } ?>