<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

if(isset($_POST['edit'])){
    $DRId = $_POST['DRId'];
    $DRName = $_POST['DRName'];
    $DRFileName = $_POST['DRFileName'];
    
    mysqli_query($link, "UPDATE `dr` SET "
            . "`DRName` = '$DRName',"
            . "`DRFileName` = '$DRFileName'"
            . "WHERE `DRId` = '$DRId'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=permissionregister.php\">"; 
    exit; 
}


if(isset($_POST['save'])){
    $DRId = newid($link, 26);
    $DRName = $_POST['DRName'];
    $DRFileName = $_POST['DRFileName'];
    
    mysqli_query($link, "INSERT INTO `dr` (`DRId` , `DRName`, `DRFileName`)"
            . "VALUES ('$DRId','$DRName','$DRFileName')");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=permissionregister.php\">"; 
    exit;  
}


if(isset($_GET['DRId'])){
    $titel = "แก้ไขรายงานการแจ้งเตือน";
    $DRId = $_GET['DRId'];
    $action = "edit";
    
    $result_dr = mysqli_query($link, "SELECT * FROM `dr` WHERE `DRId` = '$DRId'");
    $dr = mysqli_fetch_array($result_dr);
    
    $DRName = $dr['DRName'];
    $DRFileName = $dr['DRFileName'];
    
}else{
    $titel = "เพิ่มรายงานการแจ้งเตือน";
    $DRId = "";
    $DRName = "";
    $DRFileName = "";
    $action = "save";
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $titel; ?></h4>
        </div>          
        <div class="modal-body">
           <form class="form-horizontal" method="POST" action="modal_permissionregister.php">
               <input type="hidden" name="DRId" value="<?php echo $DRId; ?>">
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 text-right" style="margin-top: 5px">ชื่อรายงาน</label>
                    <div class="col-sm-7">
                        <input class="form-control" type="text" name="DRName" value="<?php echo $DRName; ?>" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 text-right" style="margin-top: 5px">ชื่อไฟล์</label>
                    <div class="col-sm-7">
                    <input class="form-control" type="text" name="DRFileName" value="<?php echo $DRFileName; ?>" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <label class="col-sm-3 text-right" style="margin-top: 5px"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary text-right" name="<?php echo $action; ?>"><i class="fa fa-save"></i> บันทึก</button>
                    </div>
                </div>
            </div>
            
            <br>
           </form>
        </div>