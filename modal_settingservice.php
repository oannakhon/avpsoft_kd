<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave

if(isset($_POST['newsave'])){
    $SEId = newid($link, 39);
    $SEName = $_POST['SEName'];
    $SEStatus = $_POST['SEStatus'];
    $UserId = $_POST['UserId'];
    mysqli_query($link, "INSERT INTO `serviceengineer` (`SEId`, `SEName`, `SEStatus`, `CreateBy`, `CreateDate`, `UserId`)"
            . "VALUES ('$SEId', '$SEName', '$SEStatus', '$CreateBy', '$CreateDate', '')");
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=settingservice.php\">"; 
    exit;
}

//edit
if(isset($_POST['edit'])){
    
    $SEId = $_POST['SEId'];
    $SEName = $_POST['SEName'];
    $SEStatus = $_POST['SEStatus'];
    $UserId = $_POST['UserId'];
    
    mysqli_query($link, "UPDATE `serviceengineer` SET "
            . "`SEName` = '$SEName', "
            . "`SEStatus` = '$SEStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate', "
            . "`UserId` = '$UserId'"
            . "WHERE `SEId` = '$SEId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=settingservice.php\">"; 
    exit;
}




if(isset($_GET['SEId'])){
    $SEId = $_GET['SEId'];
    $title = "แก้ไขผู้รับผิดชอบ";
    $action = "edit";
    
    
    $result_serviceengineer = mysqli_query($link, "SELECT * FROM `serviceengineer` WHERE `SEId` = '$SEId'");
    $serviceengineer = mysqli_fetch_array($result_serviceengineer);
    $SEName = $serviceengineer['SEName'];
    $SEStatus = $serviceengineer['SEStatus'];
    $UserId = $serviceengineer['UserId'];
}else{
    $SEId = "";
    $SEName = "";
    $title = "เพิ่มผู้รับผิดชอบ";
    $UserId = 0;
    $action = "newsave";
    $SEStatus = 1;
}



?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_settingservice.php">
                <input type="hidden" name="SEId" value="<?php echo $SEId; ?>">  
              <div class="form-group">
                <label class="control-label col-sm-4">ชื่อผู้รับผิดชอบ </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="SEName" value="<?php echo $SEName; ?>">
                </div>          

              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-4">สถานะ </label>
                <div class="col-sm-6">
                    <select name="SEStatus" class="form-control">
                        <option value="1" <?php if($SEStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($SEStatus==0){echo "selected";} ?>>ยกเลิก</option>
                    </select>
                </div>         

              </div>
                
                
              <div class="form-group">
                <label class="control-label col-sm-4">ผูกกับ </label>
                <div class="col-sm-6">
                    <select name="UserId" class="form-control">
                        <option value="0" <?php if($UserId==0){echo "selected";} ?>>ไม่ต้องการผูก</option>
                        <?php
                            $result_aduser = mysqli_query($link, "SELECT * FROM `ad_user` WHERE `UserStatus` = '1' ORDER BY `UserFullName` ");
                            while ($aduser = mysqli_fetch_array($result_aduser)){
                        ?>
                        
                        <option value="<?php echo $aduser['UserId']; ?>" <?php if($UserId==$aduser['UserId']){echo "selected";} ?>><?php echo $aduser['UserFullName']; ?></option>
                        
                            <?php } ?>
                    </select>
                </div>         

              </div>
                
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-4">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                      

              </div>  
            </form>
            
        </div>