<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 9;

if(isset($_POST['permenu'])){
   $result = mysqli_query($link, "SELECT `PerId` FROM `ad_permission` WHERE `UserId` LIKE '$_POST[UserId]'");
   while($per =  mysqli_fetch_array($result)){
       if (in_array($per['PerId'], $_POST['permenu'])) {
            mysqli_query($link,"UPDATE `ad_permission` SET `PerStatus` = '1' WHERE `PerId` = $per[PerId]");
        }else{
            mysqli_query($link,"UPDATE `ad_permission` SET `PerStatus` = '0' WHERE `PerId` = $per[PerId]");
        }  
    }    
}

//UPDATE MENU
$result_user = mysqli_query($link,"SELECT  `UserId` FROM `ad_user` WHERE `UserStatus` = '1' ");
while($user = mysqli_fetch_array($result_user)){
    $result1 = mysqli_query($link, "SELECT * FROM `ad_menu` WHERE `MenuGId` <> 0");
    while($menu = mysqli_fetch_array($result1)){
        $result = mysqli_query($link,"SELECT * FROM `ad_permission` WHERE `UserId` = '$user[UserId]' AND `MenuId` = '$menu[MenuId]'");
        $num = mysqli_num_rows($result);    
        if($num == 0){
            mysqli_query($link, "INSERT INTO `ad_permission` (`UserId`, `MenuId`, `PerStatus`, `CreateBy`) VALUES "
                    . "('$user[UserId]','$menu[MenuId]','0','1')");            
        }   
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
                                        <h2 class="panel-title">สิทธิ์การเข้าถึงเมนู</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 

   <div class="row">
                <div class="col-lg-6">
                    <!--email_off-->
                    <form method="post" action="permissionmenu.php">
                        <select class="form-control" name="UserId" onchange="this.form.submit()" style="font-family: tahoma; font-size: 14px">
                             
                         <?php
                           if(!isset($_POST['UserId'])){
                                $UserId = $_SESSION['UserId'];
                            }else{
                                $UserId = $_POST['UserId'];
                            }
                            $result = mysqli_query($link, "SELECT * FROM `ad_user` WHERE `UserStatus` LIKE '1' ORDER BY `UserFullName`");                          
                            while($user = mysqli_fetch_array($result)){                                
                                if($user['UserId']==$UserId){$selected = "selected";}else{$selected = "";}
                                echo "<option value=\"".$user['UserId']."\" ".$selected.">".$user['UserFullName']." - ".$user['UEmail']."</option>";                                
                            }
                            
                        ?>
                        </select>
                    </form> <!--/email_off-->
                </div>
            </div>
            <br>
            
            <form method="post" action="permissionmenu.php">
            <input type="hidden" name="UserId" value="<?php echo $UserId; ?>"> 
            <input type="hidden" name="permenu[]" value="0"> <!-- เพื่อให้ตรวจสอบ isset($_POST['permenu']) -->
            <div class="row">
                <div class="col-lg-6">
            <div class="table-responsive">
                <table class="table table-bordered  table-hover" style="font-family: tahoma">
                                    <thead>
                                        <tr>
                                            <th width="10%">ID</th>
                                            <th width="25%">เมนูหลัก</th>
                                            <th>เมนูย่อย</th>
                                            <th width="15%">กระทำ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <?php
                                        $result = mysqli_query($link,"SELECT * FROM `ad_permission` INNER JOIN `ad_menu` ON `ad_permission`.`MenuId` = `ad_menu`.`MenuId` "
                                                . "WHERE `ad_permission`.`UserId` = '$UserId' ORDER BY `ad_menu`.`MenuId`");
                                        while($per =  mysqli_fetch_array($result)){
                                            if($per['PerStatus']==1){$check = "checked";
                                            }else{$check = "";}
                                            
                                            $result_gmenu = mysqli_query($link,"SELECT `MenuName` FROM `ad_menu` WHERE `MenuId` = '$per[MenuGId]'");
                                            $gmenu = mysqli_fetch_array($result_gmenu);                                            
                                    ?>        
                                        <tr>
                                            <td class="text-center"><a href="#" class="btn btn-info btn-xs" title="<?php echo $per['PerId']; ?>"><i class="fa fa-map-signs"></i></a></td>
                                            <td><?php echo $gmenu[0]; ?></td>
                                            <td><?php echo $per['MenuName']; ?></td>
                                            <td>
                                                <input id="checkbox" type="checkbox" data-size="mini" name="permenu[]" value="<?php echo $per['PerId']; ?>" <?php echo $check; ?>>
                                            </td>
                                        </tr>
                                    <?php } ?>   
                                    
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->    
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6 text-right">
                    <button type="submit" class="btn btn-primary btn-sm"> &nbsp;&nbsp;&nbsp; บันทึก &nbsp;&nbsp;&nbsp;</button>
                &nbsp;&nbsp;
                    <button type="button" class="btn btn-danger btn-sm">ยกเลิก</button>
                </div>                
            </div>  
            <br>
           </form>
               
              
                




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