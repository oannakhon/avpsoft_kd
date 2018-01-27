<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $HPId = trim($_POST['HPId']);
    $HPName = trim($_POST['HPName']); 
    $HPStatus = $_POST['HPStatus'];    
    
    //check dupplicate  
    if(chkdupp($link,'homeplan', 'HPId', $HPId)==0){ 
        mysqli_query($link, "INSERT INTO `homeplan` (`HPId`, `BId`, `HPName`, `HPStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$HPId', '$_SESSION[BId]', '$HPName', '$HPStatus', '$CreateBy','$CreateDate')");
        
    }
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=homeplan.php\">"; 
    exit;    
}
if(isset($_POST['edit'])){    
    $id = trim($_POST['id']);
    $HPId = trim($_POST['HPId']);
    $HPName = trim($_POST['HPName']); 
    $HPStatus = $_POST['HPStatus'];     
    
    mysqli_query($link, "UPDATE `homeplan` SET "
            . "`HPId` = '$HPId', "
            . "`HPName` = '$HPName', "
            . "`HPStatus` = '$HPStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `id` = '$id'");
       
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=homeplan.php\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['id'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขแบบบ้าน ".$_GET['id'];
    $action = 'edit';
    $id = trim($_GET['id']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_homeplan = mysqli_query($link, "SELECT * FROM `homeplan` WHERE `id` = '$id'");
    $homeplan = mysqli_fetch_array($result_homeplan);
    
    $HPId = $homeplan['HPId'];
    $HPName = $homeplan['HPName'];
    $HPStatus = $homeplan['HPStatus']; 

}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มแบบบ้านใหม่";
    $action = 'newsave';
    $id = "";
    $HPId = "";
    $HPName = "";
    $HPStatus = 1;
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_homeplan.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
              <div class="form-group">
                <label class="control-label col-sm-3">รหัสแบบบ้าน</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="HPId" value="<?php echo $HPId; ?>" required>
                </div> 
                <label style="color: red;margin-top: 5px">*ยังไม่สามารถตั้งรหัสแบบบ้านซ้ำได้</label>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อแบบบ้าน</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="HPName" value="<?php echo $HPName; ?>" required>
                </div>         
              </div> 

                

              
              <div class="form-group">
                <label class="control-label col-sm-3">สถานะ</label>
                <div class="col-sm-9">
                    <select class="form-control" name="HPStatus">
                        <option value="1" <?php if($HPStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($HPStatus==0){echo "selected";} ?>>ไม่ใช้งาน</option>
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