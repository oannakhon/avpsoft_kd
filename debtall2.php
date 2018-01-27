<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 7;
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");

if(isset($_POST['newsave'])){    
        
    
    $BId = $_SESSION['BId'];    
    $ParNo = $_POST['ParNo'];
    $RefId = ParAddressbyParNo($link, $BId, $ParNo);
    $DebId = newidBId($link, 18);    
    $DebDate = datemysql543($_POST['DebDate']); 
    $DebName = $_POST['rDebName'];
    $ServiceStart = datemysql543($_POST['rServiceStart']);
    $ServiceEnd = datemysql543($_POST['rServiceEnd']);
    $DueDate = datemysql543($_POST['DueDate']);
    $DebTotal = $_POST['DebTotal'];
    $DebStatus = 1;    
   
    mysqli_query($link, "INSERT INTO `debt` (`BId`, `ParNo`, `RefId`, `DebId`, `DebDate`, `DebName`, `ServiceStart`, `ServiceEnd`, `DueDate`, `DebTotal`, `DebStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$BId','$ParNo','$RefId','$DebId','$DebDate','$DebName','$ServiceStart','$ServiceEnd','$DueDate','$DebTotal','$DebStatus','$CreateBy','$CreateDate')");
    
//ป้องกันการบันทึกซ้ำ
    $var = "DebName=".$_POST['DebName']."&ServiceStart=".$_POST['ServiceStart']."&ServiceEnd=".$_POST['ServiceEnd']."&DebDate=".$_POST['DebDate']."&DueDate=".$_POST['DueDate'];
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=debtall2.php?".$var."\">"; 
    exit;    
}

if(isset($_GET['DebName'])){
    $_POST['DebName'] = $_GET['DebName'];
    $_POST['ServiceStart'] = $_GET['ServiceStart'];
    $_POST['ServiceEnd'] = $_GET['ServiceEnd'];
    $_POST['DebDate'] = $_GET['DebDate'];
    $_POST['DueDate'] = $_GET['DueDate'];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>    
        <link href="css/bootstrap-datepicker.css" rel="stylesheet" />
        <script src="assets/js/bootstrap-datepicker-custom.js"></script>
         <script src="assets/js/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
        <style>           
           .datepicker{z-index:1151 !important;}
        </style>
    </head>
    <body hoe-navigation-type="vertical" hoe-nav-placement="left" theme-layout="wide-layout">

        <!--side navigation start-->
        <div id="hoeapp-wrapper" class="hoe-hide-lpanel" hoe-device-type="desktop">
            <?php include 'header.php'; ?>
            <div id="hoeapp-container" hoe-color-type="lpanel-bg7" hoe-lpanel-effect="shrink">
            <?php include 'menu.php'; ?>    


                <!--start main content-->
                <section id="main-content">
                    <div class="space-30"></div>
                    <div class="container">
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">ตั้งหนี้บริการสาธารณะทั้งโครงการ</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 
                                        <table class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="9%" class="text-center">บ้านเลขที่</th>  
                                                            <th width="10%" class="text-center">ขนาดที่ดิน</th> 
                                                            <th>ชื่อรายการ</th>  
                                                            <th width="25%" class="text-center" colspan="2">ระยะเวลาบริการ</th>
                                                            <th width="12%" class="text-right">จำนวนเงิน</th>                                                            
                                                            <th width="10%" class="text-center">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php 
                                                        
                                                        $ServiceEnd = datemysql543($_POST['ServiceEnd']);
                                                        
                                                        $sql = "SELECT  d.`ServiceEnd`,d.`ParNo`
                                                                FROM debt AS d
                                                                INNER JOIN
                                                                    (SELECT ParNo, MAX(ServiceEnd) AS MaxDateTime
                                                                    FROM debt 
                                                                    GROUP BY ParNo) groupedtt 
                                                                ON d.ParNo = groupedtt.ParNo 
                                                                AND d.ServiceEnd = groupedtt.MaxDateTime

                                                                WHERE `BId` = '$BId' AND d.ServiceEnd < '$ServiceEnd'  
                                                                ORDER BY `d`.`ParNo` ASC";
                                                        
                                                        $result_debt = mysqli_query($link, $sql);
                                                        
                                                        $i=0;
                                                        while ($debt = mysqli_fetch_array($result_debt)){
                                                            //หาขนาดที่ดิน
                                                            $ParArea = ParArea($link, $BId, $debt['ParNo']);
                                                            //ราคาต่อตารางวา
                                                            $PerWah = CBValue($link, $BId, "PublicUtilitiyWah");
                                                            
                                                            
                                                            $End = $debt[0];
                                                            
                                                            
                                                            
                                                            $showServiceStart = date("Y-m-d",strtotime("+1 days",strtotime($End)));                                                            
                                                            $showServiceEnd = $_POST['ServiceEnd'];
                                                            
                                                            //หาผลต่างเดือน
                                                            $date1= $showServiceStart;
                                                            $date2= datemysql($showServiceEnd);
                                                            
                                                            $months = month_diff($link, $date1, $date2);
                                                            $DebTotal = number_format(($ParArea*$PerWah*$months), 2, '.', '');
                                                        ?>
                                                    <form method="post" action="debtall2.php" onsubmit="return confirm('คุณต้องการตั้งหนี้รายการนี้?');">
                                                        <input type="hidden" name="DebName" value="<?php echo $_POST['DebName']; ?>">
                                                        <input type="hidden" name="ServiceStart" value="<?php echo $_POST['ServiceStart']; ?>">
                                                        <input type="hidden" name="ServiceEnd" value="<?php echo $_POST['ServiceEnd']; ?>">
                                                        <input type="hidden" name="DebDate" value="<?php echo $_POST['DebDate']; ?>">
                                                        <input type="hidden" name="DueDate" value="<?php echo $_POST['DueDate']; ?>">
                                                        <tr>
                                                            <td class="text-center"><?php echo $debt['ParNo']; ?>
                                                                <input type="hidden" name="ParNo" value="<?php echo $debt['ParNo']; ?>">
                                                            </td>
                                                            <td class="text-center"><?php echo $ParArea." ตร.ว."; ?></td> 
                                                            <td>
                                                                <input class="form-control input-sm" type="text" name="rDebName" value="<?php echo $_POST['DebName']; ?>">
                                                                
                                                            </td>
                                                            <td><input class="form-control input-sm" data-provide="datepicker" data-date-language="th-th" type="text" name="rServiceStart" value="<?php echo viewdate($showServiceStart); ?>"></td>
                                                            <td><input class="form-control input-sm" data-provide="datepicker" data-date-language="th-th" type="text" name="rServiceEnd" value="<?php echo $showServiceEnd; ?>"></td>
                                                            <td><input class="form-control input-sm text-right" type="text" name="DebTotal" value="<?php echo $DebTotal; ?>"></td>
                                                            <td><button type="submit" class="btn btn-sm btn-primary" name="newsave">ตั้งหนี้รายการนี้</button></td>
                                                        </tr>
                                                        </form>
                                                        <?php } ?>        
                                                    </tbody>
                                                </table>  
                                    </div>
                                </div>
                            </div>


                        </div>
                        
                        
                       
                        
                    </div><!--end container-->

                    <!--footer start-->
                    <div class="footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <span>&copy; Copyright 2016. AVP Enterprise Co.Ltd. ติดต่อผู้พัฒนาโปรแกรมได้ที่ Line Id: @avpenterp</span>
                            </div>
                        </div>
                    </div>
                    <!--footer end-->
                </section><!--end main content-->
            </div>
        </div><!--end wrapper-->
     




        

        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
        <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>
        
        

        <script type="text/javascript">
        $(document).ready(function () {
            
            $('.datepicker').datepicker(); 
        });
    
    //--Clear Modal cache    
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
        
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        
        </script>

        
</html>