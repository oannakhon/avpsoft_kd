<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;

$result_parcel = mysqli_query($link,"SELECT * FROM `parcel` "
        . "WHERE `BId` = '$_SESSION[BId]' ORDER BY `id` ASC ");
$numparcel = mysqli_num_rows($result_parcel);

$result_parcelaftersale = mysqli_query($link,"SELECT * FROM `parcel` "
        . "WHERE `BId` = '$_SESSION[BId]' "
        . "AND `ParDaytransferowner` NOT LIKE '0000-00-00'");
$numparcelaftersale = mysqli_num_rows($result_parcelaftersale);

$result_maxParDaytransferowner = mysqli_query($link, "SELECT MAX(ParDaytransferowner) FROM `parcel` "
        . "WHERE `BId` = '$_SESSION[BId]'");

$lastdaytransfer = mysqli_fetch_array($result_maxParDaytransferowner);
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
                                        <h2 class="panel-title">ข้อมูลลูกค้าบริการหลังการขาย (<?php echo $numparcelaftersale."/".$numparcel; ?>) อัพเดทล่าสุด <?php echo viewdate($lastdaytransfer[0]); ?></h2>                                        
                                    </header>
                                    <div class="panel-body">
                                    
                                        <table id="datatable" class="table table-striped dt-responsive nowrap" style="font-family: tahoma">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="10%">แปลงที่ดิน</th>
                                                    <th width="10%">บ้านเลขที่</th>
                                                    <th width="20%">วันโอนกรรมสิทธิ์</th>
                                                    <th width="15%">การรับประกัน</th>                                                    
                                                    <th>รายละเอียดลูกค้า</th>
                                                    <th width="10%">กระทำ</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $i = 1;
                                                while($parcel = mysqli_fetch_array($result_parcel)){
                                                    if($parcel['ParDaytransferowner']!='0000-00-00'){
                                                        $ParDaytransferowner = viewdate($parcel['ParDaytransferowner']);
                                                        $date1=date_create($parcel['ParDaytransferowner']);
                                                        $date2=date_create(date('Y-m-d'));
                                                        $diff=date_diff($date1,$date2);
                                                        $strdate =  $diff->format("(%y ปี %m เดือน)");
                                                    }else{
                                                        $ParDaytransferowner = "";
                                                        $strdate = "";
                                                    }
                                                    
                                                    
                                                ?>
                                                <tr>
                                                    <td><?php echo $i;  ?></td>
                                                    <td class="text-center"><?php echo $parcel['ParNo']; $i++;?></td>
                                                    <td class="text-center"><?php echo $parcel['ParAddress']; ?></td>
                                                    <td><?php echo $ParDaytransferowner."  ".$strdate; ?> </td>
                                                    <td>
                                                        
                                                        <a href="modal_warranty.php?ParAddress=<?php echo $parcel['ParAddress']; ?>" class="btn btn-primary btn-border btn-xs" data-toggle="modal" data-target="#hrefModal">การรับประกัน</a>
                                                        <?php
                                                        //check ต่อเติม
                                                        $result_renovation = mysqli_query($link, "SELECT * FROM `renovation` "
                                                                . "WHERE `ParAddress` = '$parcel[ParAddress]' "
                                                                . "AND `BId` ='$_SESSION[BId]' "
                                                                . "AND `RVstatus` NOT LIKE 0");
                                                        if(mysqli_num_rows($result_renovation)>0){
                                                        ?>
                                                        <a href="modal_renovation.php?ParAddress=<?php echo $parcel['ParAddress']; ?>" class="btn btn-danger btn-border btn-xs" data-toggle="modal" data-target="#hrefModal">ต่อเติม</a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        //ดึงชื่อลูกค้าล่าสุด
                                                        $result_customeraftersale = mysqli_query($link, "SELECT * FROM `customeraftersale` "
                                                                . "WHERE `BId` = '$_SESSION[BId]' "
                                                                . "AND `ParNo` = '$parcel[ParNo]' "
                                                                . "ORDER BY `CADate` DESC LIMIT 1");
                                                        if(mysqli_num_rows($result_customeraftersale)==1){
                                                            $caf = mysqli_fetch_array($result_customeraftersale);
                                                            echo "<a class=\"btn btn-info btn-xs\" href=\"unitaftersale.php?id=".$parcel['id']."#customer\"><i class=\"fa fa-eye\"></i></a> ";
                                                            echo $caf['CAPreName'].$caf['CAName']." ".$caf['CALastName'];
                                                        }
                                                        
                                                        ?>
                                                        
                                                        
                                                    </td>
                                                    <td><a href="unitaftersale.php?id=<?php echo $parcel['id']; ?>#general" class="btn btn-xs btn-warning"><i class="ion-edit"></i> แก้ไข</a></td>
                                                </tr>
                                                <?php 
                                                //Clear ค่าในตัวแปรก่อนที่จะวนรอบต่อไป
                                                $ParDaytransferowner = '';
                                                $strdate = '';
                                                
                                                    } ?>                                                
                                            </tbody>
                                        </table>
                                       

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
<!-- Modal -->
  <!-- herfModal -->
  <div class="modal fade" id="hrefModal" role="dialog" tabindex="-1">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>  
                
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
        
        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#datatable').dataTable( {
                    "language": {
                        "url": "assets/plugins/datatables/Thai.json"
                    }
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