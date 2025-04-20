<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // code for Inactive  employee    
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }
   

    if (isset($_POST['paysub'])) {
    //     echo "<pre>";
    //     print_r($_POST);
    //     echo "</pre>";
    // //     [pay] => 41363
    // // [mode] => Gpay
    // // [payid] => 3
    $status=1;
        $sql = "UPDATE `tblpayment` SET `amount` = :amount,`status`=:status WHERE `tblpayment`.`id` = :id;";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $_POST['payidss'], PDO::PARAM_STR);
        $query->bindParam(':amount', $_POST['pay'], PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:monthly-payout.php');
    
    }



    //code for active employee
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Manage Staff</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

        <!-- Theme Styles -->
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
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
                    <div class="page-title">Monthly Payout</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Staff Info</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Staff Id</th>
                                        <th>Full Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Reg Date</th>
                                        <th>Month Start date</th>
                                        <th>Month End date</th>
                                        <th>Monthly Payment</th>
                                        <th>Paid Salary</th>
                                        <th>Present Days</th>
                                        <th>Working Days</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = "SELECT p.status,workingdays,monthlyPayment,EmpId,FirstName,LastName,Department,RegDate,e.id,p.fromdate,p.todate,p.amount,presentdays,p.id as payid from tblemployees e JOIN tblpayment p ON e.id=p.emp_id;";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->EmpId); ?></td>
                                                <td><?php echo htmlentities($result->FirstName); ?>&nbsp;<?php echo htmlentities($result->LastName); ?></td>
                                                <td><?php echo htmlentities($result->Department); ?></td>
                                                <td><?php $stats = $result->status;
                                                    if ($stats==1) {
                                                    ?>
                                                        <a class="waves-effect waves-green btn-flat m-b-xs">Paid</a>
                                                    <?php } else { ?>
                                                        <a class="waves-effect waves-red btn-flat m-b-xs">UnPaid</a>
                                                    <?php } ?>


                                                </td>
                                                <td><?php echo htmlentities($result->RegDate); ?></td>

                                                <td><?php echo htmlentities($result->fromdate); ?></td>
                                                <td><?php echo htmlentities($result->todate); ?></td>
                                                <td><?php echo htmlentities($result->monthlyPayment); ?></td>
                                                <td><?php echo htmlentities($result->amount); ?></td>
                                                <td><?php echo htmlentities($result->presentdays); ?></td>
                                                <td><?php echo htmlentities($result->workingdays); ?></td>
                                              
                                                <td>
<?php  $sunc = "'$result->monthlyPayment','$result->presentdays','$result->workingdays','$result->payid'"; ?>
 <button data-target="modal1" onclick="modalFuction(<?php echo $sunc; ?>)" class="btn modal-trigger">Pay Salary</button>
                                                   
            <a href="view_time.php?getid=<?php echo htmlentities($result->id); ?>&payid=<?php echo htmlentities($result->payid);?>&fullname=<?php echo htmlentities($result->FirstName); ?>&nbsp;<?php echo htmlentities($result->LastName); ?>" > <i class=" material-icons" title="Inactive">mode_edit</i>

                                                        

                                                       
                                                          
                                                </td>
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
            <!-- Button trigger modal -->
 <!-- Modal Trigger -->
  <!-- Modal Trigger -->

  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Salary</h4>
     
      <p id="montthly"></p>
      <p id="working"></p>
      <p id="present"></p>
      <p id="countable"></p>
<form action="" method="POST">
    <label for="">Enter Amount</label>
<input type="number" name="pay" id="pay" value="" required>
<label for="">Select Mode</label>

<select name="mode" id="mode" required>
    <option value="Gpay">Gpay</option>
    <option value="Phone Pe">Phone Pe</option>
    <option value="Cash">Cash</option>
    <option value="Bank">Bank</option>
</select>
<input type="hidden" id="payid" name="payidss" value="" required>
<input type="submit" class="btn" name="paysub" value="Submit">
</form>
   
    </div>
    <div class="modal-footer">
      <!-- <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a> -->
    </div>
  </div>
        </main>

        </div>
        <div class="left-sidebar-hover"></div>

          

        


        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/table-data.js"></script>
        <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

    <script>
        function modalFuction(montthly,present,working,id){
            console.log(montthly);
            console.log(present);
            console.log(working);
            var floor = Math.floor;
        var montthly = floor(montthly);
        var present = floor(present);
        var working = floor(working);

        var countable = (montthly/working)*present;
        console.log(countable);

        $('#countable').html("Countable="+countable);
        $('#montthly').html("Monthly="+montthly);
        $('#working').html("Working="+working);
        $('#present').html("Present="+present);
        $('#pay').val(parseInt(countable));
        $('#payid').val(id);
    



        }
    </script>
    
    </body>



    </html>
<?php } ?>