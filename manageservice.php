<?php
session_start();
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;

$BId = $_SESSION['BId'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?> 
        <link href="assets/css/bootstrap-chosen.css" rel="stylesheet">
        <link href="assets/css/bootstrap-multiselect.css" rel="stylesheet"> 
        <!-- date 2560 -->
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
                                        <h2 class="panel-title">แจ้งซ่อม</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                            //กรณี มาจากการค้นหา
                                              if(isset($_GET['SDateStart'])){                                                
                                                $SDateStart = datemysql543($_GET['SDateStart']); //ค้นหาตั้งแต่วันที่
                                                $SDateEnd = datemysql543($_GET['SDateEnd']);    //ถึงวันที่                                                
                                                //ประเภทงานซ่อม (บ้านลูกค้าหรือส่วนกลาง
                                                //MultiSelect ถ้าไม่เลือกจะไม่มีค่าส่งมา ต้อง if ป้องกัน
                                                if(isset($_GET['STId'])){ $STId_arr = $_GET['STId'];}
                                                    else{ $STId_arr = array();}    
                                                    
                                                //MultiSelect ถ้าไม่เลือกจะไม่มีค่าส่งมา ต้อง if ป้องกัน
                                                if(isset($_GET['SStatus'])){
                                                    $SStatus_arr = $_GET['SStatus'];                                                    
                                                }else{
                                                    $SStatus_arr = array();
                                                }                                    
                                                $order = $_GET['order'];
                                                $limit = "";
                                                
                                                
                                              }else{
                                                //กรณีเข้ามาจาก Menu                                                                                                
                                                $SDateStart = MINDoc($link,'service','SDate');//ค้นหาตั้งแต่วันที่
                                                    if($SDateStart==null) $SDateStart = date('Y-m-01');
                                                $SDateEnd = MAXDoc($link,'service','SDate'); //ถึงวันที่  
                                                    if($SDateEnd==null) $SDateEnd = date('Y-m-d'); 
                                                    
                                                //find STId    
                                                $result_servicetype = mysqli_query($link,"SELECT * FROM `servicetype`");
                                                while($st = mysqli_fetch_array($result_servicetype)){
                                                    $STId_arr[] = $st['STId'];
                                                }
                                                
                                                // แสดงเฉพาะสถานะ SS-01, SS-02
                                                $SStatus_arr = array('SS-01','SS-02');
                                                /*
                                                //find Status    
                                                $result_servicestatus = mysqli_query($link,"SELECT * FROM `servicestatus`");
                                                while($ss = mysqli_fetch_array($result_servicestatus)){
                                                    $SStatus_arr[] = $ss['SSId'];
                                                }
                                                */
                                               
                                                $order = "DESC";
                                                $limit = "";
                                                
                                              } 
                                              
                                                //----------------CONDITION------------------
                                                $condition = ""; 
                                                if($SDateStart!=""){$condition .= "วันที่รับเรื่องแจ้งซ่อม ".viewdate($SDateStart);}
                                                if($SDateEnd!=""){$condition .= " ถึง ".viewdate($SDateEnd);}  
                                                //--MultiSelect แสดงเงื่อนไข
                                                if(COUNT($SStatus_arr)==0){
                                                    $condition .= " ยังไม่เลือกสถานะ";
                                                }else{
                                                    foreach($SStatus_arr AS $SSId){
                                                        $result_servicestatus = mysqli_query($link, "select `SSName` FROM `servicestatus` "
                                                                . "WHERE `SSId` = '$SSId'");
                                                        $ss = mysqli_fetch_array($result_servicestatus);
                                                        $SSName[] = $ss[0];
                                                    }
                                                    $condition .= " สถานะ(".implode(",", $SSName).")";
                                                }  
                                                //---จบแสดงเงื่อนไข MultiSelect
                                                if($order!=""){$condition .= " เรียงลำดับ $order ";}
                                                //----------END CONDITION-----------------
                                              
                                              $STId = "'" . implode("','",$STId_arr) . "'";
                                              $SStatus = "'" . implode("','",$SStatus_arr) . "'";    //สถานะในตารางย่อย ยังไม่ถูกนำมาใช้งาน                                        
                                              
                            
                                              //--สำหรับส่งต่อไปยังการพิมพ์
                                              $var="SDateStart=".$SDateStart.""
                                                      . "&SDateEnd=".$SDateEnd.""
                                                      . "&STId=".$STId.""
                                                      . "&SStatus=".$SStatus.""
                                                      . "&order=".$order.""
                                                      . "&limit=".$limit.""
                                                      . "&condition=".$condition."";
                                                
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#AddnewModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                                    <a href="report_service.php?<?php echo $var; ?>" class="btn btn-info" title="พิมพ์" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 <?php
                                                 
                                                  $result_sql = mysqli_query($link,""
                                                          . "SELECT * FROM `servicesub` AS `a` "
                                                          . "JOIN `service` AS `b` "
                                                          . "ON `a`.`SId` = `b`.`SId` "
                                                          . "AND `a`.`BId` = `b`.`BId`"
                                                          . "WHERE `b`.`SDate` BETWEEN '$SDateStart' AND '$SDateEnd' "
                                                          . "AND `b`.`BId` LIKE '$_SESSION[BId]' "
                                                          . "AND `a`.`BId` LIKE '$_SESSION[BId]' "
                                                          . "AND `STId` IN ($STId) "
                                                          . "AND `b`.`SStatus` = '1' "
                                                          . "AND `a`.`SSStatus` IN ($SStatus) "
                                                          . "GROUP BY `b`.`SId` " 
                                                          . "ORDER BY `b`.`SDate` $order $limit");                                                 
                                                  
                                                 
                                                 ?>
                                                   
                                                    
                                                    <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="12%">เลขที่เอกสาร</th>
                                                            <th width="10%">วันที่</th>
                                                            <th width="10%">บ้านเลขที่</th>
                                                            <th width="20%">ผู้แจ้ง</th>
                                                            <th>รายการแจ้งซ่อม</th>
                                                            <th width="10%">จำนวนเงิน</th>
                                                            <th width="10%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php                                                         
                                                        while ($service = mysqli_fetch_array($result_sql)){
                                                            
                                                            //---- Status
                                                            $result_status = mysqli_query($link, "SELECT * FROM `servicestatus` WHERE `SSId` = '$service[SStatus]'");
                                                            $status = mysqli_fetch_array($result_status);
                                                            $showstatus = "<span class=\"".$status['SClass']."\">".$status['SSName']."</span>";
                                                            
                                                            //--- สถานที่
                                                            if($service['STId']=='ST-01'){
                                                                $ParAddress = "งานส่วนกลาง";
                                                            }else{
                                                                $ParAddress = $service['ParAddress'];
                                                                //find id
                                                                $ParNo = $service['ParNo'];
                                                                $result_parcel = mysqli_query($link,"SELECT `id` FROM `parcel` WHERE `ParNo` = '$ParNo' AND `BId` = '$BId'");
                                                                $parcel = mysqli_fetch_array($result_parcel);
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                if($service['SWId']!=""){
                                                                    echo "<img src=\"assets/images/serviceway/".$service['SWId'].".png\" width=\"16\">";
                                                                }
                                                                ?>                                                                
                                                                <?php echo $service['SId']."<br>".$showstatus; ?>                                                                
                                                            </td>
                                                            <td><?php echo viewdate($service['SDate']); ?></td>
                                                            <td><?php echo $ParAddress; ?></td>
                                                            <td><?php echo $service['SCustomer']."<br>".$service['SCustomerMobile']; ?></td>
                                                            <td>
                                                                <?php 
                                                                    //เปิดตารางย่อย
                                                                          $result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` WHERE `SId` = '$service[SId]' "
                                                                                  . "AND `SSStatus` IN ($SStatus) AND `BId` = '$_SESSION[BId]'");                                                                       
                                                                          while($ss = mysqli_fetch_array($result_servicesub)){
                                                                              //---- Status
                                                                                $result_status = mysqli_query($link, "SELECT * FROM `servicestatus` WHERE `SSId` = '$ss[SSStatus]'");
                                                                                    $status = mysqli_fetch_array($result_status);
                                                                                    $showstatus = "<span class=\"".$status['SClass']."\">".$status['SSName']."</span> ";                                                                              
                                                                              echo $showstatus." ".$ss['SSName']."<br>";
                                                                          } 
                                                                ?>                                                             
                                                            </td>
                                                            <td class="text-right"><?php echo number_format($service['SPrice'],2); ?><br>
                                                            <?php
                                                            if($service['SPrice']!=0){
                                                            ?>
                                                                <a href="unitaftersale.php?id=<?php echo $parcel[0]; ?>#service" class="btn btn-success btn-xs" title="รับชำระ">
                                                                    <i class="fa fa-money" aria-hidden="true"></i>
                                                                </a>                                                               
                                                                
                                                            <?php } ?>
                                                            </td>
                                                            <td>
                                                                <a href="service.php?SId=<?php echo $service['SId']; ?>" class="btn btn-warning btn-xs" title="แก้ไข">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="pdf_service.php?SId=<?php echo $service['SId']; ?>&BId=<?php echo $service['BId'] ?>" class="btn btn-info btn-xs" title="พิมพ์" target="_blank">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                </a>                                                                
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
        <h4 class="modal-title" id="myModalLabel">ค้นหาการแจ้งซ่อม</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="manageservice.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">วันที่รับเรื่องแจ้งซ่อม </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th"  value="<?php echo viewdate($SDateStart); ?>" name="SDateStart">
                </div>         

                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdate($SDateEnd); ?>" name="SDateEnd">
                </div>        
              </div>          
           
            <div class="form-group">
                <label class="control-label col-sm-3">ประเภท </label>
                <div class="col-sm-4">
                    <select name="STId[]" id="STId" multiple="multiple">  
                        <?php
                        $result_servicetype = mysqli_query($link,"SELECT * FROM `servicetype`");
                        while($st = mysqli_fetch_array($result_servicetype)){
                            if(in_array($st['STId'], $STId_arr)){
                                $selected = "selected";                              
                            }else{
                                $selected = "";
                            } 
                            
                            echo "\n<option value=\"".$st['STId']."\" ".$selected.">".$st['STName']."</option>";
                        }                        
                        ?>
                        
                    </select>
                </div>        
            </div>             
            <div class="form-group">
                <label class="control-label col-sm-3">สถานะ </label>
                <div class="col-sm-4">
                    <select name="SStatus[]" id="SStatus" multiple="multiple">  
                        <?php
                        $result_servicestatus = mysqli_query($link,"SELECT * FROM `servicestatus`");
                        while($ss = mysqli_fetch_array($result_servicestatus)){
                            if(in_array($ss['SSId'], $SStatus_arr)){
                                $selected = "selected";                              
                            }else{
                                $selected = "";
                            } 
                            
                            echo "\n<option value=\"".$ss['SSId']."\" ".$selected.">".$ss['SSName']."</option>";
                        }
                        
                        ?>
                        
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
        <h4 class="modal-title" id="AddnewModalLabel">บันทึกรายการแจ้งซ่อมใหม่</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="service.php">
              <div class="form-group">
                <label class="control-label col-sm-3">
                    <input type="checkbox" value="ST-01" class="STId" name="STId" id="STId1">
                </label>
                <div class="col-sm-6">
                    <label class="control-label">งานส่วนกลาง</label>
                </div>        
            </div>  
            <div class="form-group">
                <label class="control-label col-sm-3">
                    <input type="checkbox" value="ST-02" class="STId" name="STId" id="STId2" checked>
                </label>
                <div class="col-sm-2">
                  <label class="control-label">บ้านลูกค้า</label>
                </div>  
                <div class="col-sm-4">
                    <select class="chosen-select" name="ParAddress">
                        <option value="">เลือกบ้านเลขที่</option>
                        <?php
                        $result_address = mysqli_query($link,"SELECT `ParAddress` FROM `parcel` WHERE `ParStatus` = '6' "
                                . "AND `BId` = '$_SESSION[BId]'");
                        while($address = mysqli_fetch_array($result_address)){
                            
                            echo "\n<option value=\"".$address['ParAddress']."\">".$address['ParAddress']."</option>";
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
<!-- Modal -->
  <!-- herfModal -->
  <div class="modal fade" id="hrefModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
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
                $('#datatable').dataTable( {
                    "paging":   false,
                    "searching": false,
                    "info":     false,
                    "order": []
                 });
                 
                 var myDate = $("#datepicker").data("date");
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
            
             var myDate2 = $("#datepicker2").data("date");
            $('.datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate2);  //กำหนดเป็นวันปัจุบัน
            });
        </script>
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