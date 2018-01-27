<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 7;


if(isset($_GET['DebStatus'])){
    $DebStatus = $_GET['DebStatus'];
}else{
    $DebStatus = "1,2";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?> 
        <link href="assets/css/bootstrap-chosen.css" rel="stylesheet">
        <link href="assets/css/bootstrap-multiselect.css" rel="stylesheet">
        
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
                                        <h2 class="panel-title">รายงานหนี้ค้างชำระค่าบริการสาธารณะ </h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php                                                
                                              $result_debt = mysqli_query($link, "SELECT * FROM `debt` "
                                                      . "WHERE `BId` = '$_SESSION[BId]' "
                                                      . "AND `DebStatus` IN ($DebStatus) "
                                                      . "ORDER BY `ParNo`,`DueDate`"
                                                      . "");
                                              $row = mysqli_num_rows($result_debt);
                                              if($DebStatus=="1,2"){$condition = "ทั้งหมด";}
                                                if($DebStatus=="1"){$condition = "ยังไม่ชำระ";}
                                                if($DebStatus=="2"){$condition = "ชำระบางส่วน";}
                                             ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?> <?php echo $row; ?> รายการ</small></span>
                                               <div class="pull-right">
                                                   <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                   <a href="form/<?php echo $_SESSION['BId']; ?>/pdf_debpublicutility.php" class="btn btn-info" title="พิมพ์" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> พิมพ์ใบแจ้งหนี้</a>
                                                   <a href="report_behindpayment.php?DebStatus=<?php echo $DebStatus; ?>" class="btn btn-info" title="พิมพ์" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> พิมพ์รายงาน</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th width="10%">เลขแปลง</th>
                                                            <th width="10%">บ้านเลขที่</th>                                                            
                                                            <th>รายการ</th>
                                                            <th width="15%">ระยะเวลาบริการ</th>
                                                            <th width="12%">ยอดเต็มจำนวน</th>
                                                            <th width="10%">ค้างชำระ</th>
                                                            <th width="10%">กำหนดชำระ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php 
                                                        $i=0;
                                                        while ($debt = mysqli_fetch_array($result_debt)){
                                                            $result_receipt = mysqli_query($link, "SELECT SUM(RecGrandtotal) FROM `receipt` "
                                                                              . "WHERE `RefId` = '$debt[DebId]' AND `BId` = '$_SESSION[BId]' AND `RecStatus` = '1'");
                                                            $receipt_sum  = mysqli_fetch_array($result_receipt);
                                                            $RemainPay = $debt['DebTotal']-$receipt_sum[0];
                                                        
                                                        ?>
                                                        <tr>
                                                            
                                                            <td class="text-center"><?php echo $debt['ParNo']; ?></td>
                                                            <td class="text-center"><?php echo $debt['RefId']; ?></td>
                                                            <td><?php echo $debt['DebName']; ?></td>
                                                            <td><small>
                                                                <?php echo viewdate($debt['ServiceStart'])." - ".viewdate($debt['ServiceEnd']); ?>
                                                                </small></td>          
                                                            <td class="text-right"><?php echo number_format($debt['DebTotal'],2); ?></td>  
                                                            <td class="text-right"><?php echo number_format($RemainPay,2); ?></td>
                                                            <td class="text-center">
                                                                <?php 
                                                                if(strtotime($debt['DueDate']) < strtotime(date('Y-m-d'))){
                                                                    echo "<font color=red>".viewdate($debt['DueDate'])."</font>";
                                                                }else{
                                                                    echo viewdate($debt['DueDate']); 
                                                                }
                                                                ?>
                                                            </td>   
                                                        </tr>
                                                        <?php } ?>        
                                                    </tbody>
                                                </table>         
                                        </div>

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
     


       <!-- Modal -->


        
<!-- Modal -->
  <!-- herfModal -->
  <div class="modal fade" id="hrefModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>   
  <!-- myModal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">รายการค้างชำระ</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="behindpayment.php">
            <div class="form-group">
                <label class="control-label col-sm-3">เลือก </label>
                <div class="col-sm-4">
                    <select class="form-control" name="DebStatus">
                        <option value="1,2" <?php if($DebStatus=="1,2"){echo "selected";} ?>>ทั้งหมด</option>
                        <option value="1"<?php if($DebStatus=="1"){echo "selected";} ?>>ยังไม่ชำระ</option>
                        <option value="2"<?php if($DebStatus=="2"){echo "selected";} ?>>ชำระบางส่วน</option>
                    </select>
                </div> 
            </div> 
              
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4">ค้นหา</button>      
                  </div>                     

              </div>  
            </form> 
      </div>

    </div>
  </div>
</div> 
  
  
  
        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        
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
                format: 'DD/MM/YYYY',locale: 'th'
            });
            
        });
        
        $(document).ready(function() {
            $(".STId").click(function() {
                selectedBox = this.id;
                $(".STId").each(function() {
                    if ( this.id == selectedBox )
                    {
                        this.checked = true;
                    }
                    else
                    {
                        this.checked = false;
                    };        
                });
            });
            $('#SStatus,#STId,#SGId,#SEId').multiselect({
                nonSelectedText: 'ยังไม่ได้เลือก',
                allSelectedText: 'เลือกทั้งหมด'
            });
        });
        
        </script>

        <script src="assets/js/chosen.jquery.min.js?v=1001" type="text/javascript"></script>
        <script src="assets/js/bootstrap-multiselect.js" type="text/javascript"></script>
        <script>
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
</html>