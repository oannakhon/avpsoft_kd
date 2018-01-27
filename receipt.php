<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 8;
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
                    <div class="container" >
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">ใบเสร็จรับเงิน</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        
                                        <!-- Nav tabs -->
                                                    
                                        
                                                    <div class="panel">
                                        <div>                                          
                                            <?php                                                
                                                if(isset($_GET['RecVol'])){                                                    
                                                  //กรณี มาจากการค้นหา  
                                                    if($_GET['RecDateStart']==""){
                                                        $RecDateStart = MINDoc($link,'receipt','RecDate');
                                                    }else{
                                                        $RecDateStart = datemysql543($_GET['RecDateStart']);
                                                    }
                                                    if($_GET['RecDateEnd']==""){
                                                        $RecDateEnd = MAXDoc($link,'receipt','RecDate');
                                                    }else{
                                                        $RecDateEnd = datemysql543($_GET['RecDateEnd']);
                                                    }
                                                $RVId =  $_GET['RecVol'];
                                                $order = $_GET['order'];                                                
                                             }else{
                                                //กรณีเข้ามาจาก Menu                                                                                               
                                                $RecDateStart = MAXDoc($link,'receipt','RecDate'); 
                                                
                                                
                                                //$RecDateStart = MINDoc($link,'receipt','RecDate');   
                                                //$RecDateEnd = MAXDoc($link,'receipt','RecDate');
                                                $RecDateEnd = $RecDateStart;
                                                $RVId =  "%";     
                                                $order = "DESC";
                                              }  
                                            
                                              if($RecDateStart==null) $RecDateStart = date('Y-m-d');
                                              if($RecDateEnd==null) $RecDateEnd = date('Y-m-d'); 
                                              
                                              
                                              
                                              
                                              $sql = "SELECT * FROM `receipt` "
                                                      . "WHERE `RecDate` BETWEEN '$RecDateStart' AND '$RecDateEnd' "
                                                      . "AND `RVId` LIKE '$RVId' "
                                                      . "AND `BId` LIKE '$_SESSION[BId]' "
                                                      . "ORDER BY `id` $order ";
                                              
                                              $result_receipt = mysqli_query($link, $sql);           
                                              
                                             
                                              //condition
                                              
                                              $condition = "วันที่ ".viewdate($RecDateStart)." - ".viewdate($RecDateEnd);                                                  
                                              if($RVId!=""){$condition .= " เล่มที่ ".$RVId;}                                            
                                              if($order!=""){$condition .= " เรียงลำดับ $order ";}
                                              
                                              $var="RecDateStart=".$RecDateStart.""
                                                      . "&RecDateEnd=".$RecDateEnd.""
                                                      . "&RVId=".$RVId.""
                                                      . "&order=".$order."";
                                                
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="modal_receipt.php?CName=RecptVolforOther&url=receipt.php" class="btn btn-success" data-toggle="modal" data-target="#hrefModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                                    <a href="report_receipt.php?<?php echo $var; ?>&RecVol=<?php echo 1; ?>" class="btn btn-info" title="พิมพ์" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="10%">วันที่</th>
                                                            <th width="5%">เล่มที่</th>
                                                            <th width="5%">เลขที่</th>
                                                            <th width="10%">ชื่อผู้จ่ายเงิน</th>
                                                            <th>รายละเอียด</th>
                                                            <th width="10%">จำนวนเงิน</th>
                                                            <th width="18%">รายละเอียดการชำระเงิน</th>
                                                            <th width="10%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php 
                                                        $i=0;
                                                        while ($receipt = mysqli_fetch_array($result_receipt)){
                                                            $i++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo viewdate($receipt['RecDate']); ?></td>
                                                            <td><?php echo sprintf('%03d',$receipt['RecVol']); ?></td>
                                                            <td><?php echo sprintf('%03d',$receipt['RecNo']); ?></td>
                                                            <td><small><?php echo $receipt['CusName']; ?><br>บ้านเลขที่ <?php echo $receipt['ParAddress']; ?></small></td>
                                                            <td><small><?php echo $receipt['RecName']; ?></small></td>                                                           
                                                            <td class="text-right"><?php echo number_format($receipt['RecGrandtotal'],2); ?></td>
                                                            <td>
                                                                <small>
                                                                    <?php
                                                                    if($receipt['RecStatus']==1){
                                                                    ?>
                                                                    <?php
                                                                    if($receipt['Rec1']>0){
                                                                        echo "เงินสด ".number_format($receipt['Rec1'],2)." บาท";
                                                                    }
                                                                    if($receipt['Rec2']>0){
                                                                        echo "เช็ค ".number_format($receipt['Rec2'],2)." บาท<br>";
                                                                        echo "เช็คเลขที่ ".$receipt['RecNote1']." ธนาคาร ".$receipt['RecNote2']."<br>";
                                                                        echo "เช็คลงวันที่ ".$receipt['RecNote3'];
                                                                    }
                                                                    if($receipt['Rec3']>0){
                                                                        echo "บัตรเครดิต ".number_format($receipt['Rec3'],2)." บาท<br>";
                                                                        echo "เลขที่บัตร ".$receipt['RecNote4']." ธนาคาร ".$receipt['RecNote5']."<br>";
                                                                        echo "บัตรหมดอายุ ".$receipt['RecNote6'];
                                                                    }
                                                                    if($receipt['Rec4']>0){
                                                                        echo "อื่นๆ ".number_format($receipt['Rec1'],2)." บาท";
                                                                    }
                                                                    ?>
                                                                    <?php }else{
                                                                        echo "ยกเลิก";
                                                                    } ?>
                                                                </small>
                                                            </td>
                                                            
                                                            <td>
                                                                <?php
                                                                if($receipt['RecStatus']==1){
                                                                ?>
                                                                <a href="modal_receipt.php?RecId=<?php echo $receipt['RecId']; ?>&url=receipt.php" class="btn btn-warning btn-xs" title="แก้ไข"  data-toggle="modal" data-target="#hrefModal">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="pdf_receipt.php?RecId=<?php echo $receipt['RecId'] ?>" class="btn btn-info btn-xs" title="พิมพ์" target="_blank">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                </a> 
                                                                <a href="del_receipt.php?RecId=<?php echo $receipt['RecId'] ?>&RefId=<?php echo $receipt['RefId'] ?>" class="btn btn-danger btn-xs" title="ยกเลิกใบเสร็จ" onclick="return confirm('คุณแน่ใจที่จะยกเลิกใบเสร็จนี้?')">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a> 
                                                                <?php } ?>
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
        <h4 class="modal-title" id="myModalLabel">ค้นหาใบเสร็จรับเงิน</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="GET" action="receipt.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">ตั้งแต่วันที่ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdate($RecDateStart); ?>" name="RecDateStart">
                </div>         

                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdate($RecDateEnd); ?>" name="RecDateEnd">
                </div>        
              </div>          
           
            <div class="form-group">
                <label class="control-label col-sm-3">เล่มใบเสร็จ </label>
                <div class="col-sm-4">
                    <select class="form-control" name="RecVol">
                        <option value="%">ทุกเล่ม</option>
                        <?php 
                        $result_receiptvol = mysqli_query($link, "SELECT * FROM `receiptvol` WHERE `BId` = '$_SESSION[BId]'");
                        while($recvol = mysqli_fetch_array($result_receiptvol)){
                            $RVBook = sprintf('%03d', $recvol['RVBook']);
                            
                            if($RVId == $recvol['RVId']){
                                
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            
                            
                            echo "<option value=\"".$recvol['RVId']."\" $selected>".$RVBook." (".$recvol['RVId'].")"."</option>";
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
                 
                 $('.datepicker').datepicker();
                 
           
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