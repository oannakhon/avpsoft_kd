<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 2;
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
                    <div class="container" style="height: 1000px;">
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">บริษัท</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                                                                         
                                              $result_company = mysqli_query($link, "SELECT * FROM `company` WHERE `ComStatus` = '1' ");                                                
                                              
                                              ?>
                                               <span class="pull-left"><small>ค้นพบบริษัทจำนวน : <?php echo mysqli_num_rows($result_company); ?> บริษัท</small></span>
                                               <div class="pull-right">
                                                    
                                                   <a href="modal_company.php" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">ที่</th>
                                                            <th width="10%">รหัสบริษัท</th>
                                                            <th>ชื่อบริษัท</th>
                                                            <th>ที่อยู่</th>
                                                            <th>เลข 13 หลัก</th>
                                                            <th width="10%">สถานะ</th>
                                                            <th width="10%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php   
                                                        $i=0;
                                                        while ($company = mysqli_fetch_array($result_company)){
                                                            $i++;   
                                                            
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $company['ComId']; ?></td>
                                                            <td><?php echo $company['ComNameTH']; ?><br><?php echo $company['ComNameEN']; ?></td>
                                                            <td><?php echo $company['ComAddress']; ?><br>
                                                                <small>
                                                            โทรศัพท์ <?php echo $company['ComTel']; ?> แฟกซ์ <?php echo $company['ComFax']; ?> มือถือ <?php echo $company['ComMobile']; ?>
                                                            </small>
                                                            </td>
                                                            <td><?php echo $company['ComTaxId']; ?></td>
                                                            <td>
                                                                <?php 
                                                                    if($company['ComStatus']==1){
                                                                        echo "ใช้งาน";
                                                                    }else{
                                                                        echo "ไม่ใช้งาน";
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><a href="modal_company.php?id=<?php echo $company['id']; ?>" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#myModal"><i class="ion-edit"></i> แก้ไข</a></td>
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
     
<!-- myModal -->
  <div class="modal fade" id="myModal" role="dialog">
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