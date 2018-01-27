<?php
session_start();
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 2;

$BId = $_SESSION['BId'];
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date('Y-m-d H:i:s');
if(isset($_POST['save'])){
    $p = $_POST['p'];
    mysqli_query($link, "DELETE FROM `configpromise` WHERE `BId` = '$BId'");
      mysqli_query($link, "INSERT INTO `configpromise` (`BId`,`p1`,`UpdateDate`, `UpdateBy`) "
                . "VALUES ('$BId', '$p[0]', '$UpdateDate', '$UpdateBy')");
      $a=2;// sql p2
    for($i=1;$i<count($p);$i++){
        mysqli_query($link, "UPDATE `configpromise` SET "
                . "`p$a` = '$p[$i]' "
                . "WHERE `BId` = '$BId'");
        
        $a++;
    }
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
                                        <h2 class="panel-title">ตั้งค่าสัญญา</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>                                          
                                            <?php
                                                                                         
                                              $result_configpromise = mysqli_query($link, "SELECT * FROM `configpromise` WHERE `BId` = '$BId' ORDER BY `BId`");                                                
                                              
                                              ?>
                                               <span class="pull-left"><small></small></span>
                                               <div class="pull-right">
                                                    
                                                </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                            <form method="post" action="configpromise.php"> 
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">ที่</th>
                                                            <th width="12%">ตำแหน่ง</th>
                                                            <th>ข้อความ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php
                                                        $configpromise = mysqli_fetch_array($result_configpromise);
                                                        for($i=1;$i<=58;$i++){
                                                            
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo "P".$i; ?></td>
                                                            <td><input class="form-control" type="text" name="p[]" value="<?php echo $configpromise['p'.$i]; ?>">
                                                            </td>
                                                    
                                                             </tr>
                                                        <?php } ?>     
                                                             
                                                    </tbody>
                                                    
                                                </table>   
                                                <button type="submit" name="save" class="btn btn-info pull-right" ><i class="fa fa-save"></i> บันทึก</button>
                                            </form>    
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