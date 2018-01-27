<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 7;

$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId =  $_SESSION['BId']; 



if(isset($_POST['savedoc'])){
    $EpId = $_POST['EpId'];
    $EpDate = datemysql($_POST['EpDate']);        
    
    mysqli_query($link, "UPDATE `expense` SET "
                . "`EpDate`='$EpDate', "
                . "`UpdateBy`='$UpdateBy', "
                . "`UpdateDate`='$UpdateDate' "
                . "WHERE `EpId`= '$EpId'");    
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=expense.php\">";
    exit;
}

if(isset($_GET['del'])){
    $EpId = trim($_GET['del']);
    
    
    mysqli_query($link, "UPDATE `expense` SET `EpStatus` = '0' WHERE `EpId` = '$EpId' AND `BId` = '$BId'");
    mysqli_query($link, "UPDATE `expensesub` SET `EpSubStatus` = '0' WHERE `EpId` = '$EpId' AND `BId` = '$BId'");
   
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=expense.php\">";
    exit;
}

if(!isset($_GET['EpId'])){
    $EpId = newidBId($link, 24);
    $BId = $_SESSION['BId'];
    $EpDate = date('Y-m-d'); 
    
    mysqli_query($link,"INSERT INTO `expense` (`BId`, `EpId`, `EpDate`, `EpStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES('$BId','$EpId','$EpDate', '1', '$CreateBy','$CreateDate')");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageexpense.php?EpId=$EpId\">"; 
    exit; 
}else{
    $EpId = trim($_GET['EpId']);
    $result_expense = mysqli_query($link, "SELECT * FROM `expense` WHERE `EpId` = '$EpId' AND `BId` = '$BId'");
    $ep = mysqli_fetch_array($result_expense);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
       <style>
        .ui-autocomplete-loading {
                background: white url("assets/jqueryui/images/ui-anim_basic_16x16.gif") right center no-repeat;
        }
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
                                        <h2 class="panel-title">บันทึกค่าใช้จ่าย <?php echo $EpId; ?></h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <form class="form-horizontal" role="form" method="post" action="manageexpense.php">
                                            <input type="hidden" name="EpId" id="EpId" value="<?php echo $EpId; ?>">  
                                              <div class="form-group">
                                                <label class="control-label col-lg-2">วันที่ </label>
                                                <div class="col-lg-2">
                                                    <input type="text" class="form-control dateadd" name="EpDate" value="<?php echo viewdate($ep['EpDate']); ?>">
                                                </div>                                                 
                                              </div>
                                         
                                            <table class="table table-bordered table-hover" style="font-family: tahoma">
                                              <thead>
                                                <tr>
                                                  <th class="text-center" width="15%">ลำดับที่</th>
                                                  <th class="text-center">รายการ</th>
                                                  <th class="text-center">หมวหมู่</th>
                                                  <th class="text-right" width="15%">จำนวนเงิน</th>
                                                  <th class="text-center" width="15%">กระทำ</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php
                                                $sum=0;
                                                $i = 0;
                                                $result_expensesub = mysqli_query($link, "SELECT * FROM `expensesub` "
                                                    . "WHERE `EpId` = '$EpId' "
                                                    . "AND `EpSubStatus` = '1'");
                                                while($eps = mysqli_fetch_array($result_expensesub)){                                                    
                                                    $i++;
                                                    $sum= $sum+$eps['EpSubAmount'];
                                                ?>
                                                  <tr>
                                                      <td class="text-center"><?php echo $i; ?></td>                                                      
                                                      <td> <?php echo $eps['EpSubName']; ?></td>
                                                      <td><?php 
                                                       $result_ETName = mysqli_query($link, "SELECT `ETName` FROM `expensetype` WHERE `ETId` = '$eps[ETId]' ");
                                                while ($ETName = mysqli_fetch_array($result_ETName)){
                                                      echo $ETName[0]; 
                                                } ?></td>
                                                      <td class="text-right"><?php echo number_format($eps['EpSubAmount'],2); ?></td>                                                     
                                                      <td>
                                                          <a href="saveexpensesub.php?del=<?php echo $eps['id']; ?>&EpId=<?php echo $EpId; ?>" class="btn btn-danger btn-xs" onclick="return confirm('คุณต้องการที่จะลบรายการนี้?')"><i class="fa fa-trash-o"></i></a>
                                                      </td>
                                                  </tr>
                                                <?php } ?>
                                                  <tr>
                                                      <td colspan="3" class="text-right"><strong>รวม</strong></td>
                                                      
                                                      <td class="text-right"><strong><?php echo number_format($sum,2); ?></strong></td>
                                                      <td></td>
                                                  </tr>
                                                  <tr>
                                                      <td class="text-right">เพิ่มรายการใหม่</td>
                                                      
                                                      <td>
                                                          <input type="text" class="form-control" id="EpSubName" name="EpSubName">                                                   
                                                      </td>  
                                                      <td><select class="form-control" id="ETId">
                                                              <?php
                                                                 $sql_expensetype =  mysqli_query($link, "SELECT `ETId`,`ETName` FROM `expensetype`");
                                                                  while ($et = mysqli_fetch_array($sql_expensetype)){
                                                              ?>
                                                               <option value="<?php echo $et[0]; ?>"><?php echo $et[1]; ?></option>
                                                                  <?php }?>
                                                          </select>
                                                     
                                                      </td>
                                                      <td>
                                                          <input type="number" class="form-control" id="EpSubAmount" name="EpSubAmount" style="text-align: right"> 
                                                      </td>
                                                      <td>
                                                          <button type="button" class="btn btn-primary btn-sm" title="บันทึก" id="save-product" name="save-product"><i class="fa fa-floppy-o"></i></button>
                                                      </td>
                                                      
                                                  </tr>                                                 
                                                  

                                                  </tbody>
                                                </table>                                         
                                            
                                            <div class="form-group">
                                                <div class="col-lg-6 col-lg-offset-6 text-right">
              
                                                    <a href="expense.php" class="btn btn-default"><i class="fa fa-chevron-left" aria-hidden="true"></i> กลับหน้าหลัก</a>
                                                    <input type="submit" class="btn btn-primary" name="savedoc" value="&nbsp;&nbsp;บันทึก&nbsp;&nbsp;">
                                                    <a href="manageexpense.php?del=<?php echo $EpId; ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการที่จะยกเลิกเอกสารนี้?')">ยกเลิกเอกสารนี้</a>
                                                    
                                                    
                                                </div>                                            
                                            </div>    
                                            
                                         
                                        </form>       
                                                 
                                           
                                       

                                        

                                    </div>
                                </div>
                            </div>


                        </div>
                        
                        
                       
                        
                    </div><!--end container-->

                    <!--footer start-->
                    <div class="footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <span>&copy; Copyright 2016. AVP Enterprise Co.Ltd.</span>
                            </div>
                        </div>
                    </div>
                    <!--footer end-->
                </section><!--end main content-->
            </div>
        </div><!--end wrapper-->
        
<!-- generalModal -->
  <div class="modal fade" id="generalModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>


            

        
        
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
        <script type="text/javascript">
        //--Clear Modal cache    
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });   
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        $(document).ready(function(){               
            /* สำหรับบันทึกรายการย่อย*/
            $("#save-product").click(function() {
                var EpId = $("#EpId").val();
                var EpSubName = $("#EpSubName").val();
                var ETId = $("#ETId").val();
                var EpSubAmount = $("#EpSubAmount").val();
                $.ajax({
                    type:"post",
                    url:"saveexpensesub.php",
                    data:"EpId="+EpId+"&EpSubName="+EpSubName+"&ETId="+ETId+"&EpSubAmount="+EpSubAmount,

                    success:function(getData){  
                        location.reload();
                      }
                });
                return false;
                });
            /* สำหรับบันทึกรายการย่อย*/            
        });   
        </script>      
</html>