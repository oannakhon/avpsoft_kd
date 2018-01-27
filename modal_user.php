<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $UserId = trim($_POST['UserId']);    
    $UserFullName = trim($_POST['UserFullName']);
    $UEmail = trim($_POST['UEmail']);
    $UserAction = $_POST['UserAction'];
    $ISSale = $_POST['ISSale'];
    $Password = trim($_POST['Password']);
    if($Password==""){
        $Password = $_POST['Password_hidden'];
    }else{
        $Password = md5($Password);
    }
    $UseKey = $_POST['UseKey']; 
    $UserStatus = $_POST['UserStatus'];   
    
    //check dupplicate  
    if(chkdupp($link,'ad_user', 'UEmail', $UEmail)==0){ 
        mysqli_query($link, "INSERT INTO `ad_user` (`UserFullName`, `UEmail`, `Password`, `UseKey`, `UserStatus`, `CreateBy`, `CreateDate`, `UserAction`, `ISSale`) "
            . "VALUES ('$UserFullName','$UEmail','$Password', '$UseKey', '$UserStatus', '$CreateBy','$CreateDate', '$UserAction','$ISSale')");
    }
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=user.php\">"; 
    exit;    
}
if(isset($_POST['edit'])){    
    $UserId = trim($_POST['UserId']);    
    $UserFullName = trim($_POST['UserFullName']);
    $UEmail = trim($_POST['UEmail']);
    $UserAction = $_POST['UserAction'];
    $ISSale = $_POST['ISSale'];
    $Password = trim($_POST['Password']);
    if($Password==""){
        $Password = $_POST['Password_hidden'];
    }else{
        $Password = md5($Password);
    }
    
    $UseKey = $_POST['UseKey']; 
    $UserStatus = $_POST['UserStatus'];      
    
    mysqli_query($link, "UPDATE `ad_user` SET "
            . "`UserFullName` = '$UserFullName', "
            . "`UEmail` = '$UEmail', "
            . "`Password` = '$Password', "
            . "`UseKey` = '$UseKey', "
            . "`UserStatus` = '$UserStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate', "
            . "`UserAction` = '$UserAction', "
            . "`ISSale` = '$ISSale' "
            . "WHERE `UserId` = '$UserId'");
       
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=user.php\">";  
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['UserId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขผู้ใช้ ".$_GET['UserId'];
    $action = 'edit';
    $UserId = trim($_GET['UserId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข    
    $result_user = mysqli_query($link, "SELECT * FROM `ad_user` WHERE `UserId` = '$UserId'");
    $user = mysqli_fetch_array($result_user);
    
    $UserFullName = $user['UserFullName'];
    $UEmail = $user['UEmail'];
    $Password = "";
    $Password_hidden = $user['Password'];
    $UseKey = $user['UseKey']; 
    $UserStatus = $user['UserStatus']; 
    $UserAction = $user['UserAction']; 
    $ISSale = $user['ISSale']; 
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มผู้ใช้ใหม่";
    $action = 'newsave';
    $UserId = "";
    $UserFullName = "";
    $UEmail = "";
    $Password = "";
    $Password_hidden = md5('1234');
    $UseKey = 0; //0ใช้ได้ทุกเครื่อง, 1 = ใช้ได้เฉพาะเครื่องที่ติดตั้งคีย์
    $UserStatus = 1; 
    $UserAction = "#";
    $ISSale = 0;
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_user.php">
                <input type="hidden" name="UserId" value="<?php echo $UserId; ?>"> 
                <div class="form-group">
                <label class="control-label col-sm-3">ชื่อ - สกุล</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="UserFullName" value="<?php echo $UserFullName; ?>" required>
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">อีเมลล์</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="UEmail" value="<?php echo $UEmail; ?>" required>
                </div> 
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3">รหัสผ่าน</label>
                <div class="col-sm-6">
                    <input type="hidden" name="Password_hidden" value="<?php echo $Password_hidden; ?>">
                    <input type="password" class="form-control" name="Password" value="<?php echo $Password; ?>">
                </div>         
              </div> 
                
              <div class="form-group">
                <label class="control-label col-sm-3">การใช้งาน</label>
                <div class="col-sm-6">
                    <select class="form-control" name="UseKey">
                        <option value="0" <?php if($UseKey==0) echo "selected";  ?>>ทุกเครื่อง</option>
                        <option value="1" <?php if($UseKey==1) echo "selected";  ?>>เฉพาะเครื่องที่ติดตั้งคีย์</option>
                    </select>
                </div>      
              </div>  
              
              <div class="form-group">
                <label class="control-label col-sm-3">สถานะ</label>
                <div class="col-sm-6">
                    <select class="form-control" name="UserStatus">
                        <option value="1" <?php if($UserStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($UserStatus==0){echo "selected";} ?>>ไม่ใช้งาน</option>
                    </select>
                </div>      
              </div>
                
              <div class="form-group">
                <label class="control-label col-sm-3">ขาย</label>
                <div class="col-sm-6">
                    <select class="form-control" name="ISSale">
                        <option value="1" <?php if($ISSale==1){echo "selected";} ?>>ขายได้</option>
                        <option value="0" <?php if($ISSale==0){echo "selected";} ?>>ขายไม่ได้</option>
                    </select>
                </div>      
              </div>
                
                
              <div class="form-group">
                    <label class="control-label col-sm-3">ช่องค้นหา</label>
                    <div class="col-sm-6">
                        <select class="form-control"  name="UserAction">
                            <option value="#" <?php if($UserAction=="#"){echo "selected";} ?>>ยังไม่ระบุ</option>
                            <option value="sale/unit.php" <?php if($UserAction=="sale/unit.php"){echo "selected";} ?>>ฝ่ายขาย</option>
                            <option value="unitaftersale.php" <?php if($UserAction=="unitaftersale.php"){echo "selected";} ?>>บริการหลังการขาย</option>
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