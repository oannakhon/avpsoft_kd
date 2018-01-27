<?php
//---- เล่มใบเสร็จ
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];
//-------------newsave

if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($link, "DELETE FROM `receiptvol` WHERE `receiptvol`.`id` = $id");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiptbook.php\">"; 
    exit;
}

if(isset($_POST['newsave'])){
    $RVId = newid($link, 41);
    $RVBook = $_POST['RVId'];
    $RVNoNow = $_POST['RVNoNow'];
    $RVIDMax = $_POST['RVIDMax'];
    $ComId = $_POST['ComId'];
    
    
    //check ว่าเล่มใบเสร็จซ้ำหรือไม่ ?
    $result_check = mysqli_query($link, "SELECT * FROM `receiptvol` WHERE `RVId` = '$RVId'");
    if(mysqli_num_rows($result_check)==0){
        
        mysqli_query($link, "INSERT INTO `receiptvol` (`RVId`, `RVNoNow`, `RVIDMax`, `BId`, `ComId`, `RVBook`) "
            . "VALUES ('$RVId', '$RVNoNow', '$RVIDMax', '$BId', '$ComId', '$RVBook')");
        
       
    }
    
    //------------------------------
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiptbook.php\">"; 
    exit;    
}

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $RVNoNow = $_POST['RVNoNow'];
    $RVIDMax = $_POST['RVIDMax'];
    $ComId = $_POST['ComId'];
    
    mysqli_query($link, "UPDATE `receiptvol` SET "
            . "`ComId` = '$ComId', "
            . "`RVNoNow` = '$RVNoNow', "
            . "`RVIDMax` = '$RVIDMax' "
            . "WHERE `id` = '$id'");
       
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiptbook.php\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['id'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขเล่มใบเสร็จ ".$_GET['id'];
    $action = 'edit';
    $id = trim($_GET['id']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_receiptvol = mysqli_query($link, "SELECT * FROM `receiptvol` WHERE `id` = '$id'");
    $receiptvol = mysqli_fetch_array($result_receiptvol);
    
    $RVId = $receiptvol['RVId'];
    $RVNoNow = $receiptvol['RVNoNow'];
    $RVIDMax = $receiptvol['RVIDMax'];
    $ComId = $receiptvol['ComId'];
    $Disabled = "disabled";

}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มเล่มใบเสร็จใหม่";
    $action = 'newsave';
    $id = "";
    $RVId = "";
    $RVIdNext = "";
    $RVNoNow = "";
    $RVIDMax = "";
    $ComId = "CO1";
    $Disabled = "";
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_receiptbook.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
              <div class="form-group">
                <label class="control-label col-sm-3">เล่มใบเสร็จ</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="RVId" value="<?php echo $RVId; ?>" required <?php echo $Disabled; ?>>
                </div>   
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">เลขที่ปัจจุบัน</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="RVNoNow" value="<?php echo $RVNoNow; ?>" required>
                </div>         
              </div> 
            
              <div class="form-group">
                <label class="control-label col-sm-3">เลขที่สูงสุด</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="RVIDMax" value="999" required>
                </div>      
              </div>
                
                <div class="form-group">
                <label class="control-label col-sm-3">บริษัท</label>
                <div class="col-sm-6">
                    <select class="form-control" name="ComId">
                       <?php
                        $result_company = mysqli_query($link,"SELECT * FROM `company` WHERE `ComStatus` = '1'");
                        while($company = mysqli_fetch_array($result_company)){
                            if($company['ComId'] == $ComId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            
                            echo "<option value=\"".$company['ComId']."\" ".$selected.">".$company['ComNameTH']."</option>";
                        }
                        ?>
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