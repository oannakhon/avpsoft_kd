<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 3;

$result_supplier = mysqli_query($link,"SELECT `id` FROM `supplier` WHERE `SuppStatus` ='1'");

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
                                        <h2 class="panel-title">จัดการซัพพลายเออร์ (<?php echo mysqli_num_rows($result_supplier); ?>)</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                            //กรณี มาจากการค้นหา
                                              if(isset($_POST['SuppName'])){
                                                $SuppName = $_POST['SuppName'];
                                                $SuppTaxId = $_POST['SuppTaxId'];
                                                $order = $_POST['order'];
                                                $limit = "";
                                                $condition = "";    
                                                
                                                if($SuppName!=""){$condition .= " ชื่อ=$SuppName, ";}
                                                if($SuppTaxId!=""){$condition .= " เลข 13 หลัก=$SuppTaxId, ";}
                                                if($order!=""){$condition .= " เรียงลำดับ $order ";}
                                                
                                              }else{
                                                  //กรณีเข้ามาจาก Menu
                                                $SuppName = "";
                                                $SuppTaxId = "";
                                                $order = "DESC";
                                                $limit = "LIMIT 20";
                                                $condition = "แสดง 20 รายการล่าสุด";
                                              }                                       
                                              
                                              $result_supplier = mysqli_query($link, "SELECT * FROM `supplier` "
                                                      . "WHERE `SuppName` LIKE '%$SuppName%' "
                                                      . "AND `SuppTaxId` LIKE '%$SuppTaxId%' "
                                                      . "ORDER BY `id` $order $limit");
                                              
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="modal_supplier.php?location=managesupplier.php" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                                    <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">รหัส</th>
                                                            <th width="12%">เลข 13 หลัก</th>
                                                            <th>รายการ</th>
                                                            <th width="12%">โทรศัพท์</th>
                                                            <th width="5%">เครดิต</th>
                                                            <th width="10%">หมายเหตุ</th>
                                                            <th width="5%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php                                                         
                                                        while ($supplier = mysqli_fetch_array($result_supplier)){
                                                            
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $supplier['SuppId']; ?></td>
                                                            <td><?php echo $supplier['SuppTaxId']; ?></td>
                                                            <td>
                                                                <?php echo $supplier['SuppName']; ?><br>
                                                                <?php echo $supplier['SuppAddress']; ?><br>
                                                                <?php echo $supplier['SuppAddress2']; ?>
                                                            </td>
                                                            <td>
                                                                <i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo $supplier['SuppTel']; ?><br>
                                                                <i class="fa fa-fax" aria-hidden="true"></i> <?php echo $supplier['SuppFax']; ?><br>
                                                                <i class="fa fa-mobile" aria-hidden="true"></i> <?php echo $supplier['SuppMobile']; ?>
                                                            </td>
                                                            <td><?php echo $supplier['SuppCreditTerm']; ?></td>
                                                            <td><?php echo $supplier['SuppNote']; ?></td>
                                                            <td> 
                                                                <?php
                                                                if($supplier['SuppStatus']==1){   
                                                                ?>
                                                                <a href="modal_supplier.php?SuppId=<?php echo $supplier['SuppId']; ?>&location=managesupplier.php" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#myModal"><i class="ion-edit"></i> แก้ไข</a>
                                                                <?php
                                                                }else{
                                                                    echo "<button type=\"button\" class=\"btn btn-xs btn-border btn-danger\">ยกเลิก</button>" ;  
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">ค้นหาซัพพลายเออร์</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" role="form" method="post" action="managesupplier.php">               
             
            <div class="form-group">
                <label class="control-label col-sm-3">เลข 13 หลัก </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppTaxId" value="<?php echo $SuppTaxId; ?>">
                </div>        
            </div>
           <div class="form-group">
                <label class="control-label col-sm-3">ชื่อ </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppName" value="<?php echo $SuppName; ?>">
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