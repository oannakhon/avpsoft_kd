<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?> 
       
        <link href="css/bootstrap-datepicker.css" rel="stylesheet" />
        <script src="assets/js/bootstrap-datepicker-custom.js"></script>
         <script src="assets/js/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
        <link href="assets/css/bootstrap-chosen.css" rel="stylesheet"> 
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
                                        <h2 class="panel-title">บันทึกต่อเติม</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                            //กรณี มาจากการค้นหา
                                              if(isset($_GET['ParAddress'])){
                                                $ParAddress = $_GET['ParAddress'];
                                                
                                                if(isset($_GET['RVDateStart'])){
                                                    $RVDateStart = datemysql543($_GET['RVDateStart']);
                                                }else{
                                                    $RVDateStart = MINDoc($link,'renovation','RVDate');
                                                }
                                                
                                                if(isset($_GET['RVDateEnd'])){
                                                    $RVDateEnd = datemysql543($_GET['RVDateEnd']);
                                                }else{
                                                    $RVDateEnd = MAXDoc($link,'renovation','RVDate');
                                                }
                                                
                                                if(isset($_GET['order'])){
                                                    $order = $_GET['order'];
                                                }else{
                                                    $order = "DESC";
                                                }                         
                                                
                                                if(isset($_GET['RVStatus'])){
                                                    $RVStatus = $_GET['RVStatus'];
                                                }else{
                                                    $RVStatus = "1,2";
                                                }
                                                $limit = "";
                                                $condition = "";                                                
                                                
                                                if($RVDateStart!=""){$condition .= "วันที่ขอต่อเติม ".viewdate($RVDateStart);}
                                                if($RVDateEnd!=""){$condition .= " ถึง ".viewdate($RVDateEnd);}
                                                if($ParAddress!=""){$condition .= " บ้านเลขที่=$ParAddress, ";}
                                                if($RVStatus=="1"){$condition .= " สถานะ=กำลังต่อเติม, ";}
                                                if($RVStatus=="2"){$condition .= " สถานะ=รับเงินประกันคืนแล้ว, ";}
                                                if($RVStatus=="1,2"){$condition .= " สถานะ=ทั้งหมด, ";}
                                                if($order!=""){$condition .= " เรียงลำดับ $order ";}
                                                
                                              }else{
                                                  //กรณีเข้ามาจาก Menu
                                                $ParAddress = "";                                                
                                                $RVDateStart = MINDoc($link,'renovation','RVDate');
                                                    if($RVDateStart==null) $RVDateStart = date('Y-m-d');
                                                $RVDateEnd = MAXDoc($link,'renovation','RVDate');
                                                    if($RVDateEnd==null) $RVDateEnd = date('Y-m-d');    
                                                $RVStatus = "1,2";
                                                $order = "DESC";
                                                $limit = "LIMIT 20";
                                                $condition = "แสดง 20 รายการล่าสุด";
                                              }                                          
                                           
                                              $result_renovation = mysqli_query($link, "SELECT * FROM `renovation` "
                                                      . "WHERE `RVDate` BETWEEN '$RVDateStart' AND '$RVDateEnd' "
                                                      . "AND `ParAddress` LIKE '%$ParAddress%' "
                                                      . "AND `BId` LIKE '$_SESSION[BId]' "
                                                      . "AND `RVStatus` IN ($RVStatus) "
                                                      . "ORDER BY `id` $order $limit");
                                              
                                                
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#AddnewModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%">เลขที่เอกสาร</th>
                                                            <th width="10%">วันที่</th>
                                                            <th width="10%">บ้านเลขที่</th>
                                                            <th>รายการต่อเติม</th>
                                                            <th width="12%">ต่อเติม</th>
                                                            <th width="12%">คืนเงินประกัน</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php                                                         
                                                        while ($rv = mysqli_fetch_array($result_renovation)){
                                                            $Status = "";
                                                            if($rv['RVStatus']==1) $Status = "<span class=\"label label-warning\">กำลังต่อเติม</span>";
                                                            if($rv['RVStatus']==2) $Status = "<span class=\"label label-success\">รับเงินประกันคืนแล้ว</span>";        
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo $rv['RVId']; ?><br>
                                                                <?php echo $Status; ?>
                                                            </td>
                                                            <td><?php echo viewdate($rv['RVDate']); ?></td>
                                                            <td><?php echo $rv['ParAddress']; ?></td>
                                                            <td><?php
                                                                $result_sub = mysqli_query($link,"SELECT `RVSubDetail` "
                                                                        . "FROM `renovationsub` "
                                                                        . "WHERE `RVId` = '$rv[RVId]' "
                                                                        . "AND  `RVSubType` = '1' "
                                                                        . "AND `RVSubStatus` = '1'");
                                                                while($rvs = mysqli_fetch_array($result_sub)){
                                                                    echo $rvs['RVSubDetail'].", ";
                                                                }
                                                                
                                                            ?>                                                               
                                                            </td>
                                                            
                                                            <td>
                                                                <a href="renovation.php?RVId=<?php echo $rv['RVId']; ?>" class="btn btn-warning btn-xs" title="แก้ไข">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i> แก้ไข
                                                                </a>
                                                                <a href="pdf_renovation.php?RVId=<?php echo $rv['RVId']; ?>" class="btn btn-info btn-xs" title="พิมพ์" target="_blank">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                </a>                                                                
                                                            </td>                                                          
                                                            <td>
                                                                <?php
                                                                    if($rv['RVDatewithdrawal']=='0000-00-00'){
                                                                        echo "<a href=\"withdrawal.php?RVId=".$rv['RVId']."\" class=\"btn btn-success btn-xs\" title=\"คืนเงินประกัน\"><i class=\"fa fa-plus\"></i> คืนเงินประกัน</a>";
                                                                    }else{
                                                                        ?>
                                                                <a href="withdrawal.php?RVId=<?php echo $rv['RVId']; ?>" class="btn btn-warning btn-xs" title="แก้ไข">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i> แก้ไข
                                                                    </a>
                                                                    <a href="pdf_withdrawal.php?RVId=<?php echo $rv['RVId']; ?>" class="btn btn-info btn-xs" title="พิมพ์" target="_blank">
                                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                                    </a> 

                                                                   <?php  }?> 
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">ค้นหาบันทึกต่อเติม</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="managerenovation.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">วันที่ขอต่อเติม </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="RVDateStart" value="<?php echo viewdate($RVDateStart); ?>">
                </div>         

                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="RVDateEnd" value="<?php echo viewdate($RVDateEnd); ?>">
                </div>        
              </div>          

            <div class="form-group">
                <label class="control-label col-sm-3">บ้านเลขที่ </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ParAddress" value="<?php echo $ParAddress; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">สถานะ </label>
                <div class="col-sm-4">
                    <select class="form-control" name="RVStatus">
                        <option value="1,2" <?php if($RVStatus=="1,2") echo "selected"; ?>>ทั้งหมด</option>
                        <option value="1" <?php if($RVStatus=="1") echo "selected"; ?>>กำลังต่อเติม</option>
                        <option value="2" <?php if($RVStatus=="2") echo "selected"; ?>>รับเงินประกันคืนแล้ว</option>                        
                    </select>
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">เรียง </label>
               
                <div class="col-sm-4">
                    <select class="form-control" name="order">
                        <option value="ASC" <?php if($order=="ASC") echo "selected"; ?>>เก่าไปใหม่</option>
                        <option value="DESC" <?php if($order=="DESC") echo "selected"; ?>>ใหม่ไปเก่า</option>
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

       <!-- Modal -->
<div class="modal fade" id="AddnewModal" tabindex="-1" role="dialog" aria-labelledby="AddnewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="AddnewModalLabel">บันทึกรายการต่อเติมใหม่</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="renovation.php">            
            <div class="form-group">
                <label class="control-label col-sm-3">บ้านเลขที่ </label>
                <div class="col-sm-6">
                  <select class="chosen-select" name="ParAddress" required>
                        <option value="">เลือกบ้านเลขที่</option>
                        <?php
                        $result_address = mysqli_query($link,"SELECT `ParAddress` FROM `parcel` WHERE `ParStatus` = '6' "
                                . "AND `BId` = '$_SESSION[BId]'");
                        while($address = mysqli_fetch_array($result_address)){
                            
                            echo "<option value=\"".$address['ParAddress']."\">".$address['ParAddress']."</option>";
                        }
                        ?>
                        
                    </select>
                </div>        
            </div>    
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4">ออกเอกสาร</button>      
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
        <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
        <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>
        
        <script>
            $(document).ready(function() {
                $('.datepicker').datepicker();  
                
                $('#datatable').dataTable( {
                    "paging":   false,
                    "searching": false,
                    "info":     false,
                    "order": []
                 });
            });
        </script>
        <script type="text/javascript">
        
       
    
    //--Clear Modal cache    
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
        
        
        
        </script>

        <script src="assets/js/chosen.jquery.min.js?v=1001" type="text/javascript"></script>
        <script>
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
</html>