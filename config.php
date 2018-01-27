<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 9;

//edit-config แก้ไขรายการเดิม
if(isset($_POST['edit-config'])){
    //edit-config
    $id = $_POST['id'];
    $CName = $_POST['CName'];
    $CValue = $_POST['CValue'];
    $CDetail = $_POST['CDetail'];
    
    mysqli_query($link,"UPDATE `config` SET "
            . "`CName` = '$CName', "
            . "`CValue` = '$CValue', "
            . "`CDetail` = '$CDetail' "
            . "WHERE `id` = '$id'");
}

//save-config บันทึกรายการใหม่
if(isset($_POST['save-config'])){
    
    $CName = $_POST['CName'];
    $CValue = $_POST['CValue'];
    $CDetail = $_POST['CDetail'];
    
    mysqli_query($link, "INSERT INTO `config` (`CName`, `CValue`, `CDetail`) "
            . "VALUES ('$CName','$CValue','$CDetail')");
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
                                        <h2 class="panel-title">คอนฟิกโปรแกรม</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 

  <div class="row">                
            <div class="col-lg-12">
                <div class="table-responsive">
                                <table class="table table-bordered  table-hover" style="font-family: tahoma">
                                    <thead>
                                        <tr>
                                            <th width="5%">id</th>
                                            <th width="15%">CName*</th>
                                            <th>CValue</th>
                                            <th>CDetail</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result_config = mysqli_query($link,"SELECT * FROM `config`");
                                        while($config = mysqli_fetch_array($result_config)){
                                        ?>
                                    <form method="post" action="config.php">
                                        <tr>
                                            <td>
                                                <input type="hidden" name="id" value="<?php echo $config['id']; ?>">
                                                <?php echo $config['id']; ?>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CName" value="<?php echo $config['CName']; ?>">                                                
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CValue" value="<?php echo $config['CValue']; ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CDetail" value="<?php echo $config['CDetail']; ?>">
                                            </td>
                                            <td>
                                                <input type="submit" class="btn btn-warning " name="edit-config" value="แก้ไข">
                                            </td>
                                        </tr>
                                    </form>    
                                        <?php } ?>
                                    <form method="post" action="config.php">
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-info" title="เพิ่มรายการใหม่"><i class="fa fa-plus-square-o"></i></button>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CName">                                                
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CValue">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="CDetail">
                                            </td>
                                            <td>
                                                <input type="submit" class="btn btn-primary" name="save-config" value="บันทึก">
                                            </td>
                                        </tr>
                                    </form>  
                                    </tbody>
                                </table>
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
</html>