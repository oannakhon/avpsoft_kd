<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $SuppId = newid($link, 5);
    $SuppName = trim($_POST['SuppName']);
    $SuppTaxId = trim($_POST['SuppTaxId']);
    $SuppAddress = trim($_POST['SuppAddress']);
    $SuppCreditTerm = trim($_POST['SuppCreditTerm']);  
    $location = $_POST['location'];
    
    //Check ซ้ำ จาก TaxId ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    mysqli_query($link, "INSERT INTO `supplier` (`SuppId`, `SuppName`, `SuppTaxId`, `SuppAddress`, `SuppCreditTerm`, `SuppStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$SuppId','$SuppName','$SuppTaxId','$SuppAddress','$SuppCreditTerm','1','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=$location\">"; 
    exit;    
}
if(isset($_POST['edit'])){ 
    $location = $_POST['location'];
    $SuppId = $_POST['SuppId'];
    $SuppName = $_POST['SuppName'];
    $SuppTaxId = $_POST['SuppTaxId'];
    $SuppAddress = $_POST['SuppAddress'];
    $SuppAddress2 = $_POST['SuppAddress2'];
    $SuppTel = $_POST['SuppTel'];
    $SuppMobile = $_POST['SuppMobile'];
    $SuppFax = $_POST['SuppFax'];
    $SuppCreditTerm = $_POST['SuppCreditTerm'];
    $SuppNote = $_POST['SuppNote'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
        
    mysqli_query($link, "UPDATE `supplier` SET "
            . "`SuppName` = '$SuppName', "
            . "`SuppTaxId` = '$SuppTaxId', "
            . "`SuppAddress` = '$SuppAddress', "
            . "`SuppAddress2` = '$SuppAddress2', "
            . "`SuppTel` = '$SuppTel', "
            . "`SuppMobile` = '$SuppMobile', "
            . "`SuppFax` = '$SuppFax', "
            . "`SuppCreditTerm` = '$SuppCreditTerm', "
            . "`SuppNote` = '$SuppNote', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `SuppId` = '$SuppId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=$location\">"; 
    exit;    
}
//------รับค่ามาจากหน้าหลัก
if(isset($_GET['SuppId'])){    
    //เปิดของเก่ามา Edit
    $title = "แก้ไขซัพพลายเออร์ ".$_GET['SuppId'];
    $action = 'edit';
    $SuppId = trim($_GET['SuppId']);
    $location = trim($_GET['location']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข    
    $result_supplier = mysqli_query($link, "SELECT * FROM `supplier` WHERE `SuppId` = '$SuppId'");
    $supplier = mysqli_fetch_array($result_supplier);
    
    $SuppName = $supplier['SuppName'];
    $SuppTaxId = $supplier['SuppTaxId'];
    $SuppAddress = $supplier['SuppAddress'];
    $SuppAddress2 = $supplier['SuppAddress2'];
    $SuppTel = $supplier['SuppTel'];
    $SuppMobile = $supplier['SuppMobile'];
    $SuppFax = $supplier['SuppFax'];
    $SuppCreditTerm = $supplier['SuppCreditTerm'];
    $SuppNote = $supplier['SuppNote'];   
    
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มซัพพลายเออร์ใหม่";
    $action = 'newsave';
    $SuppId = "";
    $SuppName = "";
    $SuppTaxId = "";
    $SuppAddress = "";
    $SuppAddress2 = "";
    $SuppTel = "";
    $SuppMobile = "";
    $SuppFax = "";
    $SuppCreditTerm = "";
    $SuppNote = "";
    $location = trim($_GET['location']);
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_supplier.php">
                <input type="hidden" name="location" value="<?php echo $location; ?>">
                <input type="hidden" name="SuppId" value="<?php echo $SuppId; ?>">
              <div class="form-group">
                <label class="control-label col-sm-3">TaxId </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppTaxId" value="<?php echo $SuppTaxId; ?>">
                </div>        
              </div>
                
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อบริษัท </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="SuppName" value="<?php echo $SuppName; ?>">
                </div>         
                
              </div>
              
            <div class="form-group">
                <label class="control-label col-sm-3">ที่อยู่ </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppAddress" value="<?php echo $SuppAddress; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppAddress2" value="<?php echo $SuppAddress2; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">เบอร์โทรศัพท์</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppTel" value="<?php echo $SuppTel; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">แฟกซ์</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppFax" value="<?php echo $SuppFax; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">มือถือ</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppMobile" value="<?php echo $SuppMobile; ?>">
                </div>        
            </div>        
            <div class="form-group">
                <label class="control-label col-sm-3">เครดิต </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="SuppCreditTerm" value="<?php echo $SuppCreditTerm; ?>">
                </div> 
                <label class="control-label col-sm-1">วัน</label>
            </div> 
            <div class="form-group">
                <label class="control-label col-sm-3">หมายเหตุ</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="SuppNote" value="<?php echo $SuppNote; ?>">
                </div>        
            </div>    
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>                     

              </div>  
            </form>            
        </div>