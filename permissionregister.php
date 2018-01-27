<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 9;



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
                                        <h2 class="panel-title">การขึ้นทะเบียนรายงานแจ้งเตือน</h2> 
                                        <a href="modal_permissionregister.php" class="btn btn-success pull-right" role="button" data-toggle="modal" data-target="#hrefModal">เพิ่มรายงานการแจ้งเตือน</a>
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 
                                    <!-- edit -->
                                    <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="10%">No.</th>
                                                <th class="text-center">ชื่อรายงาน</th>
                                                <th class="text-center">ชื่อไฟล์</th>
                                                <th class="text-center" width="10%">กระทำ</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $i=1;
                                                $result_dr = mysqli_query($link, "SELECT * FROM `dr`");
                                                while ($dr = mysqli_fetch_array($result_dr)){
                                            
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i; ?></td>
                                                <td><?php echo $dr['DRName']; ?></td>
                                                <td><?php echo $dr['DRFileName']; ?></td>
                                                <td class="text-center"><a href="modal_permissionregister.php?DRId=<?php echo $dr['DRId'];?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#hrefModal"><i class="fa fa-edit"></i> แก้ไข</a></td>
                                            </tr>
                                                <?php $i++; } ?>
                                        </tbody>
                                    </table>
               
              
                
                                    <!-- Endedit -->
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