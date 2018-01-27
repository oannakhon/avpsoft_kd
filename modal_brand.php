<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $BrandId = newid($link, 1);
    $BrandNameTH = $_POST['BrandNameTH'];
    $BrandNameEN = $_POST['BrandNameEN'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "INSERT INTO `brand` (`BrandId`, `BrandNameTH`, `BrandNameEN`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$BrandId','$BrandNameTH','$BrandNameEN','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php#brand\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $BrandId = $_POST['BrandId'];
    $BrandNameTH = $_POST['BrandNameTH'];
    $BrandNameEN = $_POST['BrandNameEN'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "UPDATE `brand` SET "
            . "`BrandNameTH` = '$BrandNameTH', "
            . "`BrandNameEN` = '$BrandNameEN', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `BrandId` = '$BrandId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php#brand\">"; 
    exit;    
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['BrandId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขยี่ห้อสินค้า ".$_GET['BrandId'];
    $action = 'edit';
    $BrandId = trim($_GET['BrandId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_brand = mysqli_query($link, "SELECT * FROM `brand` WHERE `BrandId` = '$BrandId'");
    $brand = mysqli_fetch_array($result_brand);
    $BrandNameTH = $brand['BrandNameTH'];
    $BrandNameEN = $brand['BrandNameEN'];    
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มยี่ห้อสินค้าใหม่";
    $action = 'newsave';
    $BrandId = "";
    $BrandNameTH = "";
    $BrandNameEN = "";    
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_brand.php">
                <input type="hidden" name="BrandId" value="<?php echo $BrandId; ?>">  
              <div class="form-group">
                <label class="control-label col-sm-3">ยี่ห้อภาษาไทย </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="BrandNameTH" value="<?php echo $BrandNameTH; ?>">
                </div>          

              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3">ยี่ห้อภาษาอังกฤษ </label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="BrandNameEN" value="<?php echo $BrandNameEN; ?>">
                </div>         

              </div>
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                      

              </div>  
            </form>
            
        </div>