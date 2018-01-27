<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $ComId = trim($_POST['ComId']);
    if($ComId==""){
        $ComId = newid($link, 13);
    }
    
   
    $ComTaxId = trim($_POST['ComTaxId']);
    $ComNameTH = $_POST['ComNameTH'];
    $ComNameEN = $_POST['ComNameEN'];
    $ComAddress = $_POST['ComAddress'];
    $ComTel = $_POST['ComTel'];
    $ComFax = $_POST['ComFax'];
    $ComMobile = $_POST['ComMobile'];
    $ComStatus = $_POST['ComStatus'];   
    
    //check dupplicate  
    if(chkdupp($link,'company', 'ComTaxId', $ComTaxId)==0){ 
        mysqli_query($link, "INSERT INTO `company` (`ComId`, `ComTaxId`, `ComNameTH`,`ComNameEN`, `ComAddress`,`ComTel`, `ComFax`, `ComMobile`, `ComStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$ComId','$ComTaxId','$ComNameTH', '$ComNameEN', '$ComAddress', '$ComTel','$ComFax','$ComMobile','$ComStatus', '$CreateBy','$CreateDate')");
    }
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=company.php\">"; 
    exit;    
}
if(isset($_POST['edit'])){    
    $id = trim($_POST['id']);   
    $ComId= $_POST['ComId'];
    $ComTaxId = $_POST['ComTaxId'];
    $ComNameTH = $_POST['ComNameTH'];
    $ComNameEN = $_POST['ComNameEN'];
    $ComAddress = $_POST['ComAddress'];
    $ComTel = $_POST['ComTel'];
    $ComFax = $_POST['ComFax'];
    $ComMobile = $_POST['ComMobile'];
    $ComStatus = $_POST['ComStatus']; 
    
    mysqli_query($link, "UPDATE `company` SET "
            . "`ComId` = '$ComId', "
            . "`ComTaxId` = '$ComTaxId', "
            . "`ComNameTH` = '$ComNameTH', "
            . "`ComNameEN` = '$ComNameEN', "
            . "`ComAddress` = '$ComAddress', "
            . "`ComTel` = '$ComTel', "
            . "`ComFax` = '$ComFax', "
            . "`ComMobile` = '$ComMobile', "
            . "`ComStatus` = '$ComStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `id` = '$id'");
       
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=company.php\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['id'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขบริษัท ".$_GET['id'];
    $action = 'edit';
    $id = trim($_GET['id']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_company = mysqli_query($link, "SELECT * FROM `company` WHERE `id` = '$id'");
    $company = mysqli_fetch_array($result_company);
    
    $ComId = $company['ComId'];
    $ComTaxId = $company['ComTaxId'];
    $ComNameTH = $company['ComNameTH'];
    $ComNameEN = $company['ComNameEN'];
    $ComAddress = $company['ComAddress'];
    $ComTel = $company['ComTel'];
    $ComFax = $company['ComFax'];
    $ComMobile = $company['ComMobile'];
    $ComStatus = $company['ComStatus'];

}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มบริษัทใหม่";
    $action = 'newsave';
    $id = "";
    $ComId = "";
    $ComTaxId = "";
    $ComNameTH = "";
    $ComNameEN = "";
    $ComAddress = "";
    $ComTel = "";
    $ComFax = "";
    $ComMobile = "";
    $ComStatus = 1;

}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_company.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
                <input type="hidden" name="ComId" value="<?php echo $ComId; ?>"> 
              <div class="form-group">
                <label class="control-label col-sm-3">เลขประจำตัว 13 หลัก</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ComTaxId" value="<?php echo $ComTaxId; ?>" required>
                </div>         
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อบริษัทภาษาไทย</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ComNameTH" value="<?php echo $ComNameTH; ?>" required>
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อบริษัทภาษาอังกฤษ</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ComNameEN" value="<?php echo $ComNameEN; ?>">
                </div>         
              </div>   
              <div class="form-group">
                <label class="control-label col-sm-3">ที่อยู่</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="ComAddress" value="<?php echo $ComAddress; ?>">
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">โทรศัพท์</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="ComTel" value="<?php echo $ComTel; ?>">
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">แฟกซ์</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="ComFax" value="<?php echo $ComFax; ?>">
                </div>         
              </div>   
              <div class="form-group">
                <label class="control-label col-sm-3">มือถือ</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="ComMobile" value="<?php echo $ComMobile; ?>">
                </div>         
              </div> 
              <div class="form-group">
                <label class="control-label col-sm-3">สถานะ</label>
                <div class="col-sm-3">
                    <select class="form-control" name="ComStatus">
                        <option value="1" <?php if($ComStatus==1){echo "selected";} ?>>ใช้งาน</option>
                        <option value="0" <?php if($ComStatus==0){echo "selected";} ?>>ไม่ใช้งาน</option>
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