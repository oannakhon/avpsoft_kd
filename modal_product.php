<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $PId = newid($link, 3);
    $PCode = $_POST['PCode'];
    $PName = $_POST['PName'];
    $PModel = $_POST['PModel'];
    $PGId = $_POST['PGId'];
    $BrandId = $_POST['BrandId'];
    $PSize = $_POST['PSize'];
    $PColor = $_POST['PColor'];
    $PDetail = $_POST['PDetail'];
    $PNote = $_POST['PNote'];
    
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    mysqli_query($link, "INSERT INTO `product` (`PId`, `PCode`, `PName`, `PModel`, `PGId`, `BrandId`, `PSize`, `PColor`, `PDetail`, `PNote`, `PStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$PId','$PCode','$PName','$PModel','$PGId','$BrandId','$PSize','$PColor','$PDetail','$PNote','1','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php#product\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $PId = $_POST['PId'];
    $PCode = $_POST['PCode'];
    $PName = $_POST['PName'];
    $PModel = $_POST['PModel'];
    $PGId = $_POST['PGId'];
    $BrandId = $_POST['BrandId'];
    $PSize = $_POST['PSize'];
    $PColor = $_POST['PColor'];
    $PDetail = $_POST['PDetail'];
    $PNote = $_POST['PNote'];
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "UPDATE `product` SET "
            . "`PCode` = '$PCode', "
            . "`PName` = '$PName', "
            . "`PModel` = '$PModel', "
            . "`PGId` = '$PGId', "
            . "`BrandId` = '$BrandId', "
            . "`PSize` = '$PSize', "
            . "`PColor` = '$PColor', "
            . "`PDetail` = '$PDetail', "
            . "`PNote` = '$PNote', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `PId` = '$PId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=manageproduct.php?PName=$PName#product\">"; 
    exit;    
}
//------รับค่ามาจากหน้าหลัก
if(isset($_GET['PId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขสินค้า ".$_GET['PId'];
    $action = 'edit';
    $PId = trim($_GET['PId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_product = mysqli_query($link, "SELECT * FROM `product` WHERE `PId` = '$PId'");
    $product = mysqli_fetch_array($result_product);
    $PCode = $product['PCode'];
    $PName = $product['PName'];
    $PModel = $product['PModel'];
    $PGId = $product['PGId'];
    $BrandId = $product['BrandId'];
    $PSize = $product['PSize'];
    $PColor = $product['PColor'];
    $PDetail = $product['PDetail'];
    $PNote = $product['PNote'];    
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มสินค้าใหม่";
    $action = 'newsave';
    $PId = "";
    $PCode = "";
    $PName = "";
    $PModel = "";
    $PGId = "";
    $BrandId = "";
    $PSize = "";
    $PColor = "";
    $PDetail = "";
    $PNote = "";   
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_product.php">
                <input type="hidden" name="PId" value="<?php echo $PId; ?>">  
              <div class="form-group">
                <label class="control-label col-sm-3">บาร์โค้ด </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="PCode" value="<?php echo $PCode; ?>">
                </div>         
                
              </div>
                <div class="form-group">
                <label class="control-label col-sm-3">ชื่อสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="PName" value="<?php echo $PName; ?>">
                </div>        
              </div>
            <div class="form-group">
                <label class="control-label col-sm-3">รุ่นสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="PModel" value="<?php echo $PModel; ?>">
                </div>        
            </div>
                <div class="form-group">
                <label class="control-label col-sm-3">กลุ่ม </label>
                <div class="col-sm-4">
                    <select class="chosen-select" name="PGId" required>
                        <option value="">เลือกกลุ่ม</option>
                        <?php
                        $result_productgroup = mysqli_query($link,"SELECT * FROM `productgroup` WHERE `PGStatus` = '1'");
                        while($productgroup = mysqli_fetch_array($result_productgroup)){
                            if($productgroup['PGId'] == $PGId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$productgroup['PGId']."\" ".$selected.">".$productgroup['PGName']."</option>";
                        }
                        ?>
                        
                    </select>
                </div>        
              
                <label class="control-label col-sm-1">ยี่ห้อ </label>
                <div class="col-sm-4">
                  <select class="chosen-select" name="BrandId" required>
                      <option value="">เลือกยี่ห้อ</option>
                        <?php
                        $result_brand = mysqli_query($link,"SELECT * FROM `brand`");
                        while($brand = mysqli_fetch_array($result_brand)){
                            if($brand['BrandId'] == $BrandId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$brand['BrandId']."\" ".$selected.">".$brand['BrandNameTH']."</option>\n";
                        }
                        ?>
                        
                  </select>
                </div>        
              </div>              
            <div class="form-group">
                <label class="control-label col-sm-3">ขนาด </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="PSize" value="<?php echo $PSize; ?>">
                </div>        
            
                <label class="control-label col-sm-1">สี </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="PColor" value="<?php echo $PColor; ?>">
                </div>        
            </div>    
            <div class="form-group">
                <label class="control-label col-sm-3">รายละเอียดอื่นๆ </label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="PDetail"><?php echo $PDetail; ?></textarea>
                </div>        
            </div>  
            <div class="form-group">
                <label class="control-label col-sm-3">หมายเหตุ </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="PNote" value="<?php echo $PNote; ?>">
                </div>        
            </div>     
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>                     

              </div>  
            </form>            
        </div>