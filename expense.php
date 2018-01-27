<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 7;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>      
        <link href="assets/css/bootstrap-chosen.css" rel="stylesheet">
        <link href="assets/css/bootstrap-multiselect.css" rel="stylesheet"> 
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
                                        <h2 class="panel-title">บันทึกค่าใช้จ่าย</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                            //กรณี มาจากการค้นหา
                                              if(isset($_GET['EpDateStart'])){
                                                $EpDateStart = datemysql($_GET['EpDateStart']);
                                                $EpDateEnd = datemysql($_GET['EpDateEnd']);                                                
                                                $order = $_GET['order'];
                                                $limit = "";
                                                $condition = "";                                             
                                                
                                                if($EpDateStart!=""){$condition .= "ตั้งแต่วันที่ ".viewdate($EpDateStart);}
                                                if($EpDateEnd!=""){$condition .= " ถึงวันที่ ".viewdate($EpDateEnd);}
                                                if($order!=""){$condition .= " เรียงลำดับ $order ";}
                                                
                                              }else{
                                            
                                            //กรณีเข้ามาจาก Menu
                                                $EpDateStart = MINDoc($link,'expense','EpDate');
                                                    if($EpDateStart==NULL) $EpDateStart=date('Y-m-d');
                                                $EpDateEnd = MAXDoc($link,'expense','EpDate');
                                                    if($EpDateEnd==NULL) $EpDateEnd=date('Y-m-d');                                               
                                                                                                  
                                                    
                                                $order = "DESC";
                                                $limit = "LIMIT 20";
                                                $condition = "แสดง 20 รายการล่าสุด";
                                              }                                             
                                            
                                              $sql ="SELECT * FROM `expense` "
                                                      . "WHERE `EpDate` BETWEEN '$EpDateStart' AND '$EpDateEnd' "
                                                      . "AND `BId` LIKE '$_SESSION[BId]' "
                                                      . "AND `EpStatus` = '1' "
                                                      . "ORDER BY `id` $order $limit";
                                              
                                              $result_expense = mysqli_query($link,$sql );
                                                
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="manageexpense.php" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                                    <a href="pdf_expense.php?EpDateStart=<?php echo $EpDateStart; ?>&EpDateEnd=<?php echo $EpDateEnd; ?>&order=<?php echo $order; ?>&limit=<?php echo $limit; ?>" class="btn btn-info" title="พิมพ์" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="15%">เลขที่เอกสาร</th>
                                                            <th width="15%">วันที่</th>
                                                            <th class="text-right">จำนวนเงิน</th>
                                                            <th width="15%">สถานะ</th>
                                                            <th width="15%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php                                                         
                                                        while ($ep = mysqli_fetch_array($result_expense)){                                                         
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                    echo $ep['EpId']; 
                                                                ?>
                                                            </td>
                                                            <td><?php echo viewdate($ep['EpDate']); ?></td>                                                            
                                                            <td class="text-right"><?php echo number_format($ep['EpAmount'],2); ?></td>
                                                            <td>
                                                                <?php
                                                                if($ep['EpStatus']=='1' ){
                                                                    echo "ปกติ";
                                                                }else{
                                                                    echo "ยกเลิก";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a href="manageexpense.php?EpId=<?php echo $ep['EpId']; ?>" class="btn btn-xs btn-warning"><i class="ion-edit"></i> จัดการ</a>
                                                                <a href="manageexpense.php?del=<?php echo $ep['EpId']; ?>" class="btn btn-xs btn-danger" title="ลบรายการนี้" onclick="return confirm('คุณต้องการที่จบลบรายการนี้?')"><i class="ion-trash-a"></i></a>
                                                                
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
                                <span>&copy; Copyright 2016. AVP Enterprise Co.Ltd.</span>
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
        <h4 class="modal-title" id="myModalLabel">ค้นหา</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="expense.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">ตั้งแต่วันที่ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="EpDateStart" value="<?php echo viewdate($EpDateStart); ?>">
                </div>         

                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="EpDateEnd" value="<?php echo viewdate($EpDateEnd); ?>">
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
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
        
        <script>
            $(document).ready(function() {
                
                 $('.datepicker').datepicker(); 
                 
                 
                $('#datatable').dataTable( {
                    "paging":   false,
                    "searching": false,
                    "info":     false,
                    "order": []
                 });
                 $('#SCStatus').multiselect({
                nonSelectedText: 'ยังไม่ได้เลือก',
                allSelectedText: 'เลือกทั้งหมด'
            });
            });
        </script>
        <script type="text/javascript">
        //--Clear Modal cache    
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
        
       
        </script>

        
</html>