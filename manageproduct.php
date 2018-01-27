<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 3;

$result_product = mysqli_query($link,"SELECT * FROM `product`");
$num_product = mysqli_num_rows($result_product); 

$result_productgroup = mysqli_query($link,"SELECT * FROM `productgroup`");
$num_productgroup = mysqli_num_rows($result_productgroup); 

$result_brand = mysqli_query($link,"SELECT * FROM `brand`");
$num_brand = mysqli_num_rows($result_brand);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
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
                                        <h2 class="panel-title">จัดการสินค้า</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#product" aria-controls="product" role="tab" data-toggle="tab"><i class="ion-ios-cart-outline"></i> สินค้า(<?php echo $num_product; ?>) </a></li>
                                                <li><a href="#group" aria-controls="group" role="tab" data-toggle="tab"><i class="ion-grid"></i> กลุ่มสินค้า(<?php echo $num_productgroup; ?>) </a></li>
                                                <li><a href="#brand" aria-controls="brand" role="tab" data-toggle="tab"><i class="ion-pricetags"></i> ยี่ห้อ(<?php echo $num_brand; ?>) </a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="product">
                                               <div class="space-10"></div>  
                                              <?php
                                              if(isset($_POST['PName'])){
                                                $PCode = $_POST['PCode'];
                                                $PName = $_POST['PName'];
                                                $PModel = $_POST['PModel'];
                                                $PGId = $_POST['PGId'];
                                                $BrandId = $_POST['BrandId'];
                                                $PSize = $_POST['PSize'];
                                                $PColor = $_POST['PColor'];
                                                $order = $_POST['order'];
                                                $limit = "";
                                                $condition = "";
                                                
                                                if($PCode!=""){$condition .= "บาร์โค้ด=$PCode, ";}
                                                if($PName!=""){$condition .= "ชื่อสินค้า=$PName, ";}
                                                if($PModel!=""){$condition .= "รุ่น=$PModel, ";}
                                                if($PGId!=""){$condition .= "กลุ่ม=".PGName($link, $PGId).", ";}
                                                if($BrandId!=""){$condition .= "ยี่ห้อ=".BrandName($link, $BrandId).", ";}
                                                if($PSize!=""){$condition .= "ขนาด=$PSize, ";}
                                                if($PColor!=""){$condition .= "สี=$PColor, ";}
                                                if($order!=""){$condition .= "เรียงลำดับ $order ";}
                                                
                                              }else{
                                                $PCode = "";
                                                $PName = "";
                                                $PModel = "";
                                                $PGId = "%";
                                                $BrandId = "%";
                                                $PSize = "";
                                                $PColor = "";
                                                $order = "DESC";
                                                $limit = "LIMIT 20";
                                                $condition = "แสดง 20 รายการล่าสุด";
                                              }
                                              
                                              if(isset($_GET['PName'])){
                                                $PCode = "";
                                                $PName = $_GET['PName'];
                                                $PModel = "";
                                                $PGId = "%";
                                                $BrandId = "%";
                                                $PSize = "";
                                                $PColor = "";
                                                $order = "DESC";
                                                $limit = "";
                                                $condition = "ชื่อสินค้า=$PName"; 
                                              }
                                                                                            
                                              $result_product = mysqli_query($link,"SELECT * FROM `product` "
                                                      . "WHERE `PCode` LIKE '%$PCode%' "
                                                      . "AND `PName` LIKE '%$PName%' "
                                                      . "AND `PModel` LIKE '%$PModel%' "
                                                      . "AND `PGId` LIKE '$PGId' "
                                                      . "AND `BrandId` LIKE '$BrandId' "
                                                      . "AND `PSize` LIKE '%$PSize%' "
                                                      . "AND `PColor` LIKE '%$PColor%' "
                                                      . "ORDER BY `id` $order "
                                                      . "$limit");
                                              ?>
                                               <span class="pull-left"><small>เงื่อนไขแสดงผล : <?php echo $condition; ?></small></span>
                                               <div class="pull-right">
                                                    <a href="modal_productsearch.php?PCode=<?php echo $PCode; ?>&PName=<?php echo $PName; ?>&PModel=<?php echo $PModel; ?>&PGId=<?php echo $PGId; ?>&BrandId=<?php echo $BrandId; ?>&PSize=<?php echo $PSize; ?>&PColor=<?php echo $PColor; ?>" class="btn btn-warning" data-toggle="modal" data-target="#productModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                                                    <a href="modal_product.php" class="btn btn-success" data-toggle="modal" data-target="#productModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่</a>
                                               </div>
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table id="datatable" class="table table-hover table-bordered dt-responsive " style="font-family: tahoma">
                                                    <thead>
                                                        <tr>
                                                            <th>บาร์โค้ด</th>
                                                            <th>ชื่อสินค้า</th>
                                                            <th width="10%">กลุ่มสินค้า</th>
                                                            <th width="10%">ยี่ห้อ</th>
                                                            <th >รุ่น</th>
                                                            <th width="8%">สี</th>
                                                            <th width="8%">ขนาด</th>
                                                            <th width="15%">กระทำ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php 
                                                        
                                                        while ($product = mysqli_fetch_array($result_product)){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $product['PCode']; ?></td>
                                                            <td><?php echo $product['PName']; ?></td>
                                                            <td><?php echo PGName($link, $product['PGId']); ?></td>
                                                            <td><?php echo BrandName($link, $product['BrandId']); ?></td>
                                                            <td><?php echo $product['PModel']; ?></td>
                                                            <td><?php echo $product['PColor']; ?></td>
                                                            <td><?php echo $product['PSize']; ?></td>

                                                            <td>
                                                                <a href="modal_product.php?PId=<?php echo $product['PId']; ?>" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#productModal"><i class="ion-edit"></i> แก้ไข</a>
                                                                <a href="modal_productview.php?PId=<?php echo $product['PId']; ?>" class="btn btn-xs btn-info" data-toggle="modal" data-target="#productModal"><i class="ion-eye"></i></a>
                                                                <a href="#" class="btn btn-xs btn-info">สต๊อก</a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>        
                                                    </tbody>
                                                </table>         
                                                    
                                                    
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="group">
                                                    <div class="space-10"></div>  
                                                    <div class="col-lg-6">
                                                        <a href="modal_productgroup.php" class="btn btn-success pull-right" data-toggle="modal" data-target="#productgroupModal"> <i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่ </a>
                                                        
                                                        <br>
                                                        <div class="space-20"></div>  
                                                        <table class="table table-bordered table-hover" style="font-family: tahoma">
                                                                  <thead>
                                                                    <tr>
                                                                      <th class="text-center" width='20%'>รหัสกลุ่มสินค้า</th>
                                                                      <th class="text-center">ชื่อกลุ่ม</th>
                                                                      <th class="text-center" width='10%'>สถานะ</th>
                                                                      <th class="text-center" width='10%'>กระทำ</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                      <?php
                                                                      
                                                                      while($productgroup = mysqli_fetch_array($result_productgroup)){   
                                                                          if($productgroup['PGStatus']==1){
                                                                              $ion_class = "ion-checkmark-round";
                                                                          }else{
                                                                              $ion_class = "ion-close-round";
                                                                          }
                                                                          
                                                                      ?>
                                                                      <tr>
                                                                      
                                                                      <td class="text-center"><?php echo $productgroup['PGId']; ?></td>
                                                                      <td><?php echo $productgroup['PGName']; ?></td>
                                                                      <td class="text-center"><i class="<?php echo $ion_class; ?>"></i></td>
                                                                      <td><a href="modal_productgroup.php?PGId=<?php echo $productgroup['PGId']; ?>"  class="btn btn-xs btn-warning" data-toggle="modal" data-target="#productgroupModal"><i class="ion-edit"></i> แก้ไข</a></td>
                                                                    </tr>
                                                                      <?php } ?>
                                                                  </tbody>
                                                                </table>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="brand">
                                                    <div class="space-10"></div>  
                                                    <div class="col-lg-10">
                                                        <a href="modal_brand.php" class="btn btn-success pull-right" data-toggle="modal" data-target="#brandModal"> <i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่ </a>
                                                        
                                                        <br>
                                                        <div class="space-20"></div>  
                                                        <table class="table table-bordered table-hover" style="font-family: tahoma">
                                                                  <thead>
                                                                    <tr>
                                                                      <th class="text-center">#</th>
                                                                      <th class="text-center" width='15%'>รหัสยี่ห้อ</th>
                                                                      <th class="text-center" width='25%'>ชื่อยี่ห้อภาษาไทย</th>
                                                                      <th class="text-center" width='25%'>ชื่อยี่ห้อภาษาอังกฤษ</th>
                                                                      <th class="text-center" width='10%'>กระทำ</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                      <?php
                                                                      
                                                                      while($branch = mysqli_fetch_array($result_brand)){
                                                                          //Check exist BrandLogo file
                                                                          $logoimg = "assets/images/brand/".$branch['BrandId'].".png";
                                                                          if(!file_exists($logoimg)){
                                                                              $logoimg = "assets/images/brand/nologo.png";
                                                                          }
                                                                          
                                                                      ?>
                                                                      <tr>
                                                                        <td class="text-center" style="padding: 3px"><img src="<?php echo $logoimg; ?>" width="30%"></td>
                                                                      <td class="text-center"><?php echo $branch['BrandId']; ?></td>
                                                                      <td><?php echo $branch['BrandNameTH']; ?></td>
                                                                      <td><?php echo $branch['BrandNameEN']; ?></td>
                                                                      <td><a href="modal_brand.php?BrandId=<?php echo $branch['BrandId']; ?>"  class="btn btn-xs btn-warning" data-toggle="modal" data-target="#brandModal"><i class="ion-edit"></i> แก้ไข</a></td>
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
        
        <!-- productModal -->
  <div class="modal fade" id="productModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>

<!-- brandModal -->
  <div class="modal fade" id="brandModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>
              
<!-- productgroupModal -->
  <div class="modal fade" id="productgroupModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>              

        

        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
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
        
        $('#productModal').on('shown.bs.modal', function () {
             $('.chosen-select', this).chosen('destroy').chosen();
        });
        
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        } 

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
        
        </script>
        
        <script src="assets/js/chosen.jquery.min.js?v=1001" type="text/javascript"></script>
        <script>
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
        
</html>