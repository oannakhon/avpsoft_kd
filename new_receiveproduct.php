<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 3;

if(isset($_POST['RPDate'])){
    $RPDate = datemysql($_POST['RPDate']);
    $SuppId = $_POST['SuppId'];
    $RPRefId = $_POST['RPRefId'];
    $POId = $_POST['POId'];
    $WId = $_POST['WId'];
    $RPId = newid($link, 6);
    
    mysqli_query($link,"INSERT INTO `receiveproduct` (`RPId`, `RPRefId`, `POId`, `RPDate`, `SuppId`, `WId`, `BId`, `RPStatus`) "
            . "VALUES('$RPId','$RPRefId','$POId','$RPDate','$SuppId','$WId', '$_SESSION[BId]', '1')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=managereceiveproduct.php?RPId=$RPId\">"; 
    exit; 
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
       <style>
        .ui-autocomplete-loading {
                background: white url("assets/jqueryui/images/ui-anim_basic_16x16.gif") right center no-repeat;
        }
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
                                        <h2 class="panel-title">บันทึกรับสินค้า</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="col-lg-6">                                            
                                            
                                            <form class="form-horizontal" role="form" method="post" action="new_receiveproduct.php">
                                           
                                          <div class="form-group">
                                            <label class="control-label col-sm-3">วันที่ </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control dateadd" name="RPDate" value="<?php echo date('d/m/Y') ?>">
                                            </div>         

                                          </div>
                                            <div class="form-group">
                                            <label class="control-label col-sm-3">ซัพพลายเออร์ </label>
                                            <div class="col-sm-6">
                                              <input type="hidden" name="SuppId" id="SuppId" value="">  
                                              <input type="text" class="form-control" name="SuppName" id="SuppName" value="">
                                            </div> 
                                            <div class="col-sm-1">
                                                <a href="modal_supplier.php?location=new_receiveproduct.php" class="btn btn-success" data-toggle="modal" data-target="#supplierModal"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                            </div>
                                            
                                          </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">เลขที่ใบส่งของ </label>
                                            <div class="col-sm-6">
                                              <input type="text" class="form-control" name="RPRefId">
                                            </div>        
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">เลขที่ใบสั่งซื้อ </label>
                                            <div class="col-sm-6">
                                              <input type="text" class="form-control" name="POId">
                                            </div>        
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">คลังสินค้า </label>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="WId">
                                                    <?php 
                                                    $result_warehouse = mysqli_query($link, "SELECT * FROM `warehouse` "
                                                            . "WHERE `BId` = '$_SESSION[BId]'");
                                                    while($warehouse = mysqli_fetch_array($result_warehouse)){
                                                        echo "<option value=\"".$warehouse['WId']."\">".$warehouse['WName']."</option>";
                                                    }
                                                    ?>
                                                    
                                                </select>  
                                            </div>        
                                        </div>
                                        <div class="form-group">
                                              <div class="col-sm-6 col-sm-offset-3">
                                                  <button type="submit" class="btn btn-primary col-sm-4" id="save" disabled>ถัดไป</button>      
                                              </div>                     

                                        </div>  
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
        
        <!-- supplierModal -->
  <div class="modal fade" id="supplierModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>

            

        
        
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
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
        $(document).ready(function(){       
            //ส่วนของการค้นหาสินค้า
            $(function() { 
                $( "#SuppName" ).autocomplete({
                    source: "search_supplier.php",
                    minLength: 2,      
                    select: function( event, ui ) {
                        $( "#SuppId" ).val(ui.item.id);
                        $( "#SuppName" ).val( ui.item.name );  
                                              
                        $('#save').removeAttr('disabled');
                    }
                });
            });   
            
        });
        </script>      
</html>