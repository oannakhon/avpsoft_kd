<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $PGId = newid($link, 2);
    $PGName = $_POST['PGName'];
    $PGStatus = $_POST['PGStatus'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "INSERT INTO `productgroup` (`PGId`, `PGName`, `PGStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$PGId','$PGName','$PGStatus','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php#group\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $PGId = $_POST['PGId'];
    $PGName = $_POST['PGName'];
    $PGStatus = $_POST['PGStatus'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "UPDATE `productgroup` SET "
            . "`PGName` = '$PGName', "
            . "`PGStatus` = '$PGStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `PGId` = '$PGId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php#group\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['PGId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขกลุ่มสินค้า ".$_GET['PGId'];
    $action = 'edit';
    $PGId = trim($_GET['PGId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_productgroup = mysqli_query($link, "SELECT * FROM `productgroup` WHERE `PGId` = '$PGId'");
    $productgroup = mysqli_fetch_array($result_productgroup);
    $PGName = $productgroup['PGName'];
    $PGStatus = $productgroup['PGStatus'];   

}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มกลุ่มสินค้าใหม่";
    $action = 'newsave';
    $PGId = "";
    $PGName = "";
    $PGStatus = "1";    
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_productgroup.php">
                <input type="hidden" name="PGId" value="<?php echo $PGId; ?>">  
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อกลุ่มสินค้า </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="PGName" value="<?php echo $PGName; ?>">
                </div>          

              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3"> </label>
                <div class="col-sm-6">
                    <select class="form-control" name="PGStatus">
                        <option value="1" <?php if($PGStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($PGStatus==0){echo "selected";} ?>>ไม่ใช้งาน</option>
                    </select>
                </div>         

              </div>
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                      

              </div>  
            </form>
            
        </div>