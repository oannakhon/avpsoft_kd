<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);




include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 1;
$BId = $_SESSION['BId'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
        <style>
            .projectplan {
                max-width: 100vw;
                height:auto;
                width:auto;
                max-height:85vh;
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
                    <div class="space-10"></div>
                        <div class="pull-right">
                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                        &nbsp;&nbsp; 
                        </div><br>
                    <div class="space-20"></div>
                    <div class="container">
                        <!--widget box row-->  
                    <div class="row">                       
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 3054 2879" >
                                    <image width="3054" height="2879" xlink:href="assets/images/projectplan/KM.14P1.jpg"></image>
                                    <?php
                                    $str_SGId = "";
                                    
                                    if(isset($_POST['search'])){
                                      
                                        $SGId = $_POST['SGId'];
                                        $num =  count($SGId);                                        
                                        $last = $num-1;
                                        for($i=0;$i<$num;$i++){
                                            $SGId1 = "'".$SGId[$i]."'";
                                            $str_SGId .= $SGId1;
                                            if(($i!=$last)){$str_SGId .= ", ";}                                       }   
                                        
                                        
                                        
                                  
                                    $result_servicesub = mysqli_query($link, "SELECT `b`.`ParNo` "
                                            . "FROM `servicesub` AS `a` "
                                            . "JOIN `service` AS `b` "
                                            . "ON `a`.`SId` = `b`.`SId` "
                                            . "WHERE `b`.`BId` = '$BId' "
                                            . "AND `a`.`SGId` IN ($str_SGId) "
                                            . "AND `a`.`SSStatus` != '0' "
                                            . "AND `b`.`ParNo` != '' "
                                            . "GROUP BY `b`.`ParNo` "
                                            . "ORDER BY `b`.`ParNo`");  
                                    if(mysqli_num_rows($result_servicesub)!=0){
                                    while($ss = mysqli_fetch_array($result_servicesub)){
                                        $ParNo = $ss[0];
                                        $result_coor = mysqli_query($link, "SELECT `ParCoor` FROM `parcel` "
                                            . "WHERE `BId` = '$BId' AND `ParNo` = '$ParNo' "
                                            . "AND `ParStatus` != '99' ");
                                        $ParCoor = mysqli_fetch_array($result_coor);                                    
                                    ?>
                                    <polygon points="<?php echo $ParCoor[0]; ?>" fill="#ff0000" opacity="0.5" title="1"><title><?php echo $ParNo; ?></title></polygon>
                                    <?php }}} ?>
                            </svg>                   
                        </div>
                           
                        
                            
                        </div>
                    
                        <!--widget box row-->


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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ปรับการแสดงผลผังโครงการ งานบริการหลังการขาย</h4>
      </div>
      <div class="modal-body">
          <form action="projectplan.php" method="post">
              
              <?php
              $result_servicegroup = mysqli_query($link, "SELECT * FROM `servicegroup` "
                      . "WHERE `SGLevel` = '2' AND `SGStatus` = '1'");
              while($sg = mysqli_fetch_array($result_servicegroup)){
                    $L0 = SGParentId($link,$sg['SGParentId']);
                    $SGName = SGName($link,$L0)."->".SGName($link,$sg['SGParentId'])."->".$sg['SGName'];
              ?>
              <input type="checkbox" name="SGId[]" value="<?php echo $sg['SGId']; ?>" checked> <?php echo $SGName; ?><br>
              <?php } ?>
              
              <button type="submit" class="btn btn-primary col-sm-4" name="search">ค้นหา</button><br>
          </form>
      </div>
      
    </div>

  </div>
</div>
        
        
    </body>
</html>