<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $CWId = newid($link, 11);
    $CId = $_POST['CId'];    
   
    $CWCompany = trim($_POST['CWCompany']); //ชื่อบริษัท
    $CWAddress = trim($_POST['CWAddress']); //ที่อยู่
    $CWSubdistrict = trim($_POST['CWSubdistrict']); //ตำบล
    $CWDistrict = trim($_POST['CWDistrict']); //อำเภอ
    $CWProvince = trim($_POST['CWProvince']); //จังหวัด
    $CWPostCode = trim($_POST['CWPostCode']); //รหัสไปรษณีย์
    $CWTel = trim($_POST['CWTel']); //เบอร์โทรศัพท์
    $CWTelEx = trim($_POST['CWTelEx']); //เบอร์ต่อ
    $CWType = trim($_POST['CWType']); //ลักษณะงาน 1=ประจำ,2=พนักงานซับ,3=สัญญาจ้าง
    $CWPosition = trim($_POST['CWPosition']); // ตำแหน่ง
    $CWWorkStartMonth = trim($_POST['CWWorkStartMonth']); // เริ่มงานเดือน
    $CWWorkStartYear = trim($_POST['CWWorkStartYear']); //เริมงานปี
    $CWSalary = trim($_POST['CWSalary']); //เงินเดือน
    $CWOT = trim($_POST['CWOT']); //โอที
    $CWSalaryDate = trim($_POST['CWSalaryDate']); //เงินเดือนออกวันที่
    $CWTimeContact = trim($_POST['CWTimeContact']); // ติดต่อได้เวลา
    
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    mysqli_query($link, "INSERT INTO `customerwork` (`CWId`, `CId`, `CWCompany`, `CWAddress`, `CWSubdistrict`, `CWDistrict`, `CWProvince`, `CWPostCode`, `CWTel`, `CWTelEx`, `CWType`,`CWPosition`,`CWWorkStartMonth`,`CWWorkStartYear`,`CWSalary`, `CWOT`, `CWSalaryDate`, `CWTimeContact`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$CWId','$CId','$CWCompany','$CWAddress','$CWSubdistrict','$CWDistrict','$CWProvince','$CWPostCode','$CWTel','$CWTelEx','$CWType', '$CWPosition','$CWWorkStartMonth','$CWWorkStartYear','$CWSalary','$CWOT','$CWSalaryDate','$CWTimeContact','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$CId."#work\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $CWId = $_POST['CWId'];
    $CId = $_POST['CId'];
    
    $CWCompany = trim($_POST['CWCompany']); //ชื่อบริษัท
    $CWAddress = trim($_POST['CWAddress']); //ที่อยู่
    $CWSubdistrict = trim($_POST['CWSubdistrict']); //ตำบล
    $CWDistrict = trim($_POST['CWDistrict']); //อำเภอ
    $CWProvince = trim($_POST['CWProvince']); //จังหวัด
    $CWPostCode = trim($_POST['CWPostCode']); //รหัสไปรษณีย์
    $CWTel = trim($_POST['CWTel']); //เบอร์โทรศัพท์
    $CWTelEx = trim($_POST['CWTelEx']); //เบอร์ต่อ
    $CWType = trim($_POST['CWType']); //ลักษณะงาน 1=ประจำ,2=พนักงานซับ,3=สัญญาจ้าง
    $CWPosition = trim($_POST['CWPosition']); // ตำแหน่ง
    $CWWorkStartMonth = trim($_POST['CWWorkStartMonth']); // เริ่มงานเดือน
    $CWWorkStartYear = trim($_POST['CWWorkStartYear']); //เริมงานปี
    $CWSalary = trim($_POST['CWSalary']); //เงินเดือน
    $CWOT = trim($_POST['CWOT']); //โอที
    $CWSalaryDate = trim($_POST['CWSalaryDate']); //เงินเดือนออกวันที่
    $CWTimeContact = trim($_POST['CWTimeContact']); // ติดต่อได้เวลา
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "UPDATE `customerwork` SET "
            . "`CWCompany` = '$CWCompany', "
            . "`CWAddress` = '$CWAddress', "
            . "`CWSubdistrict` = '$CWSubdistrict', "
            . "`CWDistrict` = '$CWDistrict', "
            . "`CWProvince` = '$CWProvince', "
            . "`CWPostCode` = '$CWPostCode', "
            . "`CWTel` = '$CWTel', "
            . "`CWTelEx` = '$CWTelEx', "
            . "`CWType` = '$CWType', "
            . "`CWPosition` = '$CWPosition', "
            . "`CWWorkStartMonth` = '$CWWorkStartMonth', "
            . "`CWWorkStartYear` = '$CWWorkStartYear', "
            . "`CWSalary` = '$CWSalary', "
            . "`CWOT` = '$CWOT', "
            . "`CWSalaryDate` = '$CWSalaryDate', "
            . "`CWTimeContact` = '$CWTimeContact', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `CWId` = '$CWId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$CId."#work\">"; 
    exit;    
}

//------รับค่ามาจากหน้าหลัก
if(isset($_GET['CWId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขที่ทำงาน ".$_GET['CWId'];
    $action = 'edit';
    $CWId = trim($_GET['CWId']);
    $CId = trim($_GET['CId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_customerwork = mysqli_query($link, "SELECT * FROM `customerwork` WHERE `CWId` = '$CWId'");    
    $customerwork = mysqli_fetch_array($result_customerwork);
    
    $CWCompany = $customerwork['CWCompany']; //ชื่อบริษัท
    $CWAddress = $customerwork['CWAddress']; //ที่อยู่
    $CWSubdistrict = $customerwork['CWSubdistrict']; //ตำบล
    $CWDistrict = $customerwork['CWDistrict']; //อำเภอ
    $CWProvince = $customerwork['CWProvince']; //จังหวัด
    $CWPostCode = $customerwork['CWPostCode']; //รหัสไปรษณีย์
    $CWTel = $customerwork['CWTel']; //เบอร์โทรศัพท์
    $CWTelEx = $customerwork['CWTelEx']; //เบอร์ต่อ
    $CWType = $customerwork['CWType']; //ลักษณะงาน 1=ประจำ,2=พนักงานซับ,3=สัญญาจ้าง
    $CWPosition = $customerwork['CWPosition']; // ตำแหน่ง
    $CWWorkStartMonth = $customerwork['CWWorkStartMonth']; // เริ่มงานเดือน
    $CWWorkStartYear = $customerwork['CWWorkStartYear']; //เริมงานปี
    $CWSalary = $customerwork['CWSalary']; //เงินเดือน
    $CWOT = $customerwork['CWOT']; //โอที
    $CWSalaryDate = $customerwork['CWSalaryDate']; //เงินเดือนออกวันที่
    $CWTimeContact = $customerwork['CWTimeContact']; // ติดต่อได้เวลา
    
}else{
    //สำหรับบันทึกใหม่
    $CId = trim($_GET['CId']);
    $CWId = "";
    $title = "เพิ่มที่ทำงานใหม่";
    $action = 'newsave';
    
    $CWCompany = ""; //ชื่อบริษัท
    $CWAddress = ""; //ที่อยู่
    $CWSubdistrict = ""; //ตำบล
    $CWDistrict = ""; //อำเภอ
    $CWProvince = ""; //จังหวัด
    $CWPostCode = ""; //รหัสไปรษณีย์
    $CWTel = ""; //เบอร์โทรศัพท์
    $CWTelEx = ""; //เบอร์ต่อ
    $CWType = 1; //ลักษณะงาน 1=ประจำ,2=พนักงานซับ,3=สัญญาจ้าง
    $CWPosition = ""; // ตำแหน่ง
    $CWWorkStartMonth = ""; // เริ่มงานเดือน
    $CWWorkStartYear = ""; //เริมงานปี
    $CWSalary = ""; //เงินเดือน
    $CWOT = ""; //โอที
    $CWSalaryDate = ""; //เงินเดือนออกวันที่
    $CWTimeContact = ""; // ติดต่อได้เวลา
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_customerwork.php">
                <input type="hidden" name="CId" value="<?php echo $CId; ?>"> 
                <input type="hidden" name="CWId" value="<?php echo $CWId; ?>"> 
            <div class="form-group">
                <label class="control-label col-sm-3">ลักษณะงาน </label>
                <div class="col-sm-3">
                    <select class="form-control" name="CWType">
                        <option value="1" <?php if($CWType==1) echo "selected"; ?>>ประจำ</option>
                        <option value="2" <?php if($CWType==2) echo "selected"; ?>>พนักงานซับ</option>
                        <option value="3" <?php if($CWType==3) echo "selected"; ?>>สัญญาจ้าง</option>
                    </select>
                </div> 
                <label class="control-label col-sm-3">เวลาที่สะดวกติดต่อ</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWTimeContact" value="<?php echo $CWTimeContact; ?>">
                </div> 
            </div>
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">ชื่อบริษัท</label>
                <div class="col-sm-9">                    
                    <input type="text" class="form-control" name="CWCompany" value="<?php echo $CWCompany; ?>">
                </div> 
            </div>
                
            <div class="form-group">                 
                <label class="control-label col-sm-3">ที่อยู่</label>
                <div class="col-sm-9">                    
                    <input type="text" class="form-control" name="CWAddress" value="<?php echo $CWAddress; ?>">
                </div> 
            </div>  
            <div class="form-group">                 
                <label class="control-label col-sm-3">ตำบล</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWSubdistrict" value="<?php echo $CWSubdistrict; ?>">
                </div> 
                <label class="control-label col-sm-3">อำเภอ</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWDistrict" value="<?php echo $CWDistrict; ?>">
                </div> 
            </div>
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">จังหวัด</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWProvince" value="<?php echo $CWProvince; ?>">
                </div> 
                <label class="control-label col-sm-3">รหัสไปรษณีย์</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWPostCode" value="<?php echo $CWPostCode; ?>">
                </div> 
            </div>     
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">โทรศัพท์</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWTel" value="<?php echo $CWTel; ?>">
                </div> 
                <label class="control-label col-sm-3">ต่อ</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWTelEx" value="<?php echo $CWTelEx; ?>">
                </div> 
            </div>  
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">ตำแหน่ง</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWPosition" value="<?php echo $CWPosition; ?>">
                </div> 
                <label class="control-label col-sm-3">เงินออกวันที่</label>
                <div class="col-sm-3">                    
                    <select class="form-control" name="CWSalaryDate">
                        <?php
                        for($i=1;$i<32;$i++){
                            if($CWSalaryDate==$i){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            } 
                            echo "<option value=\"".$i."\" ".$selected.">".$i."</option>";
                        }
                        ?>
                    </select>
                </div> 
            </div>  
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">ฐานเงินเดือน</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWSalary" value="<?php echo $CWSalary; ?>">
                </div> 
                <label class="control-label col-sm-3">โอที</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWOT" value="<?php echo $CWOT; ?>">
                </div> 
            </div> 
            
            <div class="form-group">                 
                <label class="control-label col-sm-3">เริ่มทำงานเดือน</label>
                <div class="col-sm-3">
                    <select class="form-control" name="CWWorkStartMonth">
                        <option value="1" <?php if($CWWorkStartMonth==1) echo "selected"; ?>>1</option>
                        <option value="2" <?php if($CWWorkStartMonth==2) echo "selected"; ?>>2</option>
                        <option value="3" <?php if($CWWorkStartMonth==3) echo "selected"; ?>>3</option>
                        <option value="4" <?php if($CWWorkStartMonth==4) echo "selected"; ?>>4</option>
                        <option value="5" <?php if($CWWorkStartMonth==5) echo "selected"; ?>>5</option>
                        <option value="6" <?php if($CWWorkStartMonth==6) echo "selected"; ?>>6</option>
                        <option value="7" <?php if($CWWorkStartMonth==7) echo "selected"; ?>>7</option>
                        <option value="8" <?php if($CWWorkStartMonth==8) echo "selected"; ?>>8</option>
                        <option value="9" <?php if($CWWorkStartMonth==9) echo "selected"; ?>>9</option>
                        <option value="10" <?php if($CWWorkStartMonth==10) echo "selected"; ?>>10</option>
                        <option value="11" <?php if($CWWorkStartMonth==11) echo "selected"; ?>>11</option>
                        <option value="12" <?php if($CWWorkStartMonth==12) echo "selected"; ?>>12</option>
                    </select>
                </div> 
                <label class="control-label col-sm-3">ปี(ค.ศ.)</label>
                <div class="col-sm-3">                    
                    <input type="text" class="form-control" name="CWWorkStartYear" value="<?php echo $CWWorkStartYear; ?>">
                </div> 
            </div> 
                
            <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>                    
            </div>  
            </form> 
                       
            </div>     
                         
        </div>