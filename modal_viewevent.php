<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $BId = trim($_POST['BId']);
    if($BId==""){
        $BId = newid($link, 12);
    }
    
    $ComId= $_POST['ComId'];
    $BName = trim($_POST['BName']);
    $BAddress = trim($_POST['BAddress']); 
    $BStatus = $_POST['BStatus'];    
    
    //check dupplicate  
    if(chkdupp($link,'branch', 'BId', $BId)==0){ 
        mysqli_query($link, "INSERT INTO `branch` (`BId`, `ComId`, `BName`, `BAddress`, `BStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$BId','$ComId','$BName', '$BAddress', '$BStatus', '$CreateBy','$CreateDate')");
    }
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=branch.php\">"; 
    exit;    
}
if(isset($_POST['edit'])){    
    $id = trim($_POST['id']);
    $BId = trim($_POST['BId']);
    if($BId==""){
        $BId = newid($link, 12);
    }
    $ComId= $_POST['ComId'];
    $BName = trim($_POST['BName']);
    $BAddress = trim($_POST['BAddress']); 
    $BStatus = $_POST['BStatus'];     
    
    mysqli_query($link, "UPDATE `branch` SET "
            . "`BId` = '$BId', "
            . "`ComId` = '$ComId', "
            . "`BName` = '$BName', "
            . "`BAddress` = '$BAddress', "
            . "`BStatus` = '$BStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `id` = '$id'");
       
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=branch.php\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['id'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขสาขา ".$_GET['id'];
    $action = 'edit';
    $id = trim($_GET['id']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_branch = mysqli_query($link, "SELECT * FROM `branch` WHERE `id` = '$id'");
    $branch = mysqli_fetch_array($result_branch);
    
    $BId = $branch['BId'];
    $ComId= $branch['ComId'];
    $BName = $branch['BName'];
    $BAddress = $branch['BAddress']; 
    $BStatus = $branch['BStatus']; 

}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มสาขาใหม่";
    $action = 'newsave';
    $id = "";
    $BId = "";
    $ComId= "";
    $BName = "";
    $BAddress = "";
    $BStatus = 1; 
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_branch.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
              <div class="form-group">
                <label class="control-label col-sm-3">รหัสสาขา</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="BId" value="<?php echo $BId; ?>">
                </div>          
                <div class="text-info"><small>**หากไม่ใส่จะออกให้รหัสให้อัตโนมัติ</small></div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อสาขา</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="BName" value="<?php echo $BName; ?>" required>
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">ที่อยู่</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="BAddress" value="<?php echo $BAddress; ?>">
                </div>         
              </div> 
                
              <div class="form-group">
                <label class="control-label col-sm-3">บริษัท</label>
                <div class="col-sm-9">
                    <select class="form-control" name="ComId">
                        <?php
                        $result_company = mysqli_query($link,"SELECT * FROM `company` WHERE `ComStatus` ='1'");
                        while($company = mysqli_fetch_array($result_company)){
                            if($company['ComId']==$ComId){
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
                <label class="control-label col-sm-3">สถานะ</label>
                <div class="col-sm-3">
                    <select class="form-control" name="BStatus">
                        <option value="1" <?php if($BStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($BStatus==0){echo "selected";} ?>>ไม่ใช้งาน</option>
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