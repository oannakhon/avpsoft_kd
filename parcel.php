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
                    <div class="container" >
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">แปลง</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                                                                         
                                              $result_parcel = mysqli_query($link, "SELECT * FROM `parcel` WHERE `BId` = '$_SESSION[BId]' ORDER BY `id`");                                                
                                              
                                              ?>
                                               <span class="pull-left"><small>ค้นพบแปลงจำนวน : <?php echo mysqli_num_rows($result_parcel); ?> แปลง</small></span>
                                               <div class="pull-right">
                                                    
                                                   <a href="modal_parcel.php" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="10%">เลขที่แปลง</th>
                                                            <th width="10%">เลขที่โฉนด</th>
                                                            <th width="10%">ขนาดที่ดิน</th>
                                                            <th width="10%">โซนที่ดิน</th>
                                                            <th width="13%">โซนบ้าน</th>
                                                            <th width="10%">บ้านเลขที่</th>
                                                            <th width="10%">แบบบ้าน</th>
                                                            <th width="10%">สถานะ</th>
                                                            <th width="12%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php   
                                                        $i=0;
                                                        while ($parcel = mysqli_fetch_array($result_parcel)){
                                                            $i++;   
                                                            
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $parcel['ParNo']; ?></td>
                                                            <td><?php echo $parcel['ParDeed']; ?></td>
                                                            <td class="text-right"><?php echo number_format($parcel['ParArea'],1); ?></td>
                                                            <td class="text-center">
                                                                <?php 
                                                                $result_zoneid =  mysqli_query($link, "SELECT `ZoneName` FROM `zone` WHERE `ZoneId` = '$parcel[ZoneId]'");
                                                                $zonename = mysqli_fetch_array($result_zoneid);
                                                                 echo $zonename[0];
                                                                ?>
                                                            </td>
                                                            <td>
                                                            <?php
                                                            $result_homezone = mysqli_query($link, "SELECT * FROM `homezone` WHERE `BId` = '$BId' AND `HomeZoneId` = '$parcel[HomeZoneId]' ");
                                                            $homezone = mysqli_fetch_array($result_homezone);
                                                            
                                                            echo $homezone['HomeZoneName'];
                                                            ?>
                                                            </td>
                                                            <td><?php echo $parcel['ParAddress']; ?></td>
                                                            <td>
                                                                <?php 
                                                                $result_hpid =  mysqli_query($link, "SELECT `HPName` FROM `homeplan` WHERE `HPId` = '$parcel[HPId]'");
                                                                $hpname = mysqli_fetch_array($result_hpid);
                                                                 echo $hpname[0];
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php 
                                                              $result_psname =  mysqli_query($link, "SELECT `PSName`,`PSColor` FROM `parcelstatus` WHERE `PSId` = '$parcel[ParStatus]'");
                                                              $psname = mysqli_fetch_array($result_psname);
                                                              //echo $psname[0];
                                                                ?>
                                                                <label class="label" opacity="0.8" style="background-color: <?php echo $psname[1]; ?>"><?php echo $psname[0]; ?></label> 
                                                            </td>
                                                            <td>
                                                                <a href="modal_parcel.php?id=<?php echo $parcel['id']; ?>" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#myModal"><i class="ion-edit"></i> แก้ไข</a>
                                                                <a href="mapimage/demo.php?id=<?php echo $parcel['id']; ?>&BId=<?php echo $BId; ?>" class="btn btn-xs btn-info" target="_blank" title="แก้ไขตำแหน่งผัง"><i class="fa fa-object-group"></i> </a>
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
     
<!-- myModal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">    
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
                    "searching": true,
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