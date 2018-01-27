<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 9;

if(isset($_POST['edit'])) { // แก้คำนำหน้าเอกสาร
    $id = $_POST['id'];
    $PrePrefix = $_POST['PrePrefix'];
    $PreNum = $_POST['PreNum'];
    mysqli_query($link, "UPDATE `prefix` SET `PrePrefix` = '$PrePrefix',  `PreNum` = '$PreNum' WHERE `id` LIKE '$id'");   
} 

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>     
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
                                        <h2 class="panel-title">เลขที่เอกสาร</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 

  <div class="row">                
            <div class="col-lg-6">
                <table class="table table-bordered  table-hover" style="font-family: tahoma">
                <thead>
                  <tr>
                    <th>ชื่อเอกสาร</th>
                    <th width="25%">ตัวนำหน้า</th>
                    <th width="20%">จำนวนหลัก</th>
                    <th width="15%">กระทำ</th>
                  </tr>
                </thead>
                <tbody>
                    
                  
                    <?php
                        $result = mysqli_query($link, "SELECT * FROM `prefix`");
                        while($prefix = mysqli_fetch_array($result)){
                    ?>
                    <form method="post" action="prefix.php">   
                    <tr>                
                        <td><?php echo "(".$prefix['id'].")".$prefix['PreDocname']; ?></td>
                        <td><input type="text"  class="form-control" name="PrePrefix" value="<?php echo $prefix['PrePrefix']; ?>"></td>
                        <td><input type="text"  class="form-control" name="PreNum" value="<?php echo $prefix['PreNum']; ?>"></td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $prefix['id']; ?>">
                            <button type="submit" class="btn btn-success" name="edit">อัพเดท</button>
                        </td>
                    </tr>
                    </form>
                    <?php
                        }
                    ?>
                
                    
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
        <h4 class="modal-title" id="myModalLabel">ค้นหาใบรับสินค้า</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="receiveproduct.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">ตั้งแต่วันที่ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control dateadd" name="RPDateStart" value="<?php echo viewdate($RPDateStart); ?>">
                </div>         

                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control dateadd" name="RPDateEnd" value="<?php echo viewdate($RPDateEnd); ?>">
                </div>        
              </div>
            <div class="form-group">
                <label class="control-label col-sm-3">เลขที่ใบรับสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="RPRefId" value="<?php echo $RPRefId; ?>">
                </div>        
            </div>
           <div class="form-group">
                <label class="control-label col-sm-3">เลขที่ใบสั่งซื้อสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="POId" value="<?php echo $POId; ?>">
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
        
        
        <script>
            $(document).ready(function() {
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
        
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        
        </script>

        
</html>