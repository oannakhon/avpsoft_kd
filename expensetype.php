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
                                        <h2 class="panel-title">หมวดหมู่ค่าใช้จ่าย</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                            
                                              ?>
                                               <div class="pull-right">
                                                   <a href="modal_expensetype.php" class="btn btn-success" data-toggle="modal" data-target="#etModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%">เลขที่หมวดหมู่</th>
                                                            <th width="20%">ชื่อหมวดหมู่</th>
                                                            <th width="5%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php          
                                                        $result_expensetype = mysqli_query($link, "SELECT * FROM `expensetype` WHERE `ETStatus` = '1' ");
                                                        while ($et = mysqli_fetch_array($result_expensetype)){
                                                            
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                    echo $et['ETId']; 
                                                                ?>
                                                            </td>
                                                            <td><?php echo $et['ETName']; ?></td>
                                                            <td>
                                                                <a href="modal_expensetype.php?ETId=<?php echo $et['ETId'];  ?>" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#etModal"><i class="ion-edit"></i> จัดการ</a>
                                                               
                                                                <a href="modal_expensetype.php?del=<?php echo $et['ETId']; ?>" class="btn btn-xs btn-danger" title="ลบรายการนี้" onclick="return confirm('คุณต้องการที่จบลบรายการนี้?')"><i class="ion-trash-a"></i></a>
                                                                                                                           
                                                                                                                                
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
<div id="etModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        
        
       <script src="assets/js/chosen.jquery.min.js?v=1001" type="text/javascript"></script>
        <script src="assets/js/bootstrap-multiselect.js" type="text/javascript"></script>
        <script>
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
        
        <script>
            $(document).ready(function() {
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
        
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        
        </script>

        
</html>