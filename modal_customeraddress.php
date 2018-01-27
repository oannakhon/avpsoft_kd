<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave
if(isset($_POST['newsave'])){
    $CAId = newid($link, 10);
    $CId = $_POST['CId'];
    
    $CANo = $_POST['CANo']; //เลขที่
    $CAApartment = $_POST['CAApartment']; //ชื่อหอพัก,หมู่บ้าน
    $CARoom = $_POST['CARoom']; 
    $CAFloor = $_POST['CAFloor']; 
    $CASubdistrict = $_POST['CASubdistrict'];
    $CADistrict = $_POST['CADistrict']; 
    $CAProvince = $_POST['CAProvince'];
    $CAPostCode = $_POST['CAPostCode'];
    $CATel = $_POST['CATel'];
    $CATelEx = $_POST['CATelEx']; 
    $CALiveYear = $_POST['CALiveYear']; //อาศัยมากี่ปี
    $CALiveMonth = $_POST['CALiveMonth']; //กี่เดือน
    $CALiveStatus = $_POST['CALiveStatus']; //เช่าหรือเป็นเจ้าของ
    $CALiveWith = $_POST['CALiveWith']; //อยู่กับ
    $CALiveWithNum = $_POST['CALiveWithNum']; //จำนวนคนที่อยู่ด้วยกัน
    
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    mysqli_query($link, "INSERT INTO `customeraddress` (`CAId`, `CId`, `CANo`, `CAApartment`, `CARoom`, `CAFloor`, `CASubdistrict`, `CADistrict`, `CAProvince`, `CAPostCode`, `CATel`, `CATelEx`,`CALiveYear`,`CALiveMonth`,`CALiveStatus`,`CALiveWith`,`CALiveWithNum`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$CAId','$CId','$CANo','$CAApartment','$CARoom','$CAFloor','$CASubdistrict','$CADistrict','$CAProvince','$CAPostCode','$CATel','$CATelEx','$CALiveYear','$CALiveMonth','$CALiveStatus','$CALiveWith','$CALiveWithNum','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$CId."#address\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $CAId = $_POST['CAId'];
    $CId = $_POST['CId'];
    
    $CANo = $_POST['CANo']; //เลขที่
    $CAApartment = $_POST['CAApartment']; //ชื่อหอพัก,หมู่บ้าน
    $CARoom = $_POST['CARoom']; 
    $CAFloor = $_POST['CAFloor']; 
    $CASubdistrict = $_POST['CASubdistrict'];
    $CADistrict = $_POST['CADistrict']; 
    $CAProvince = $_POST['CAProvince'];
    $CAPostCode = $_POST['CAPostCode'];
    $CATel = $_POST['CATel'];
    $CATelEx = $_POST['CATelEx']; 
    $CALiveYear = $_POST['CALiveYear']; //อาศัยมากี่ปี
    $CALiveMonth = $_POST['CALiveMonth']; //กี่เดือน
    $CALiveStatus = $_POST['CALiveStatus']; //เช่าหรือเป็นเจ้าของ
    $CALiveWith = $_POST['CALiveWith']; //อยู่กับ
    $CALiveWithNum = $_POST['CALiveWithNum']; //จำนวนคนที่อยู่ด้วยกัน
    //Check ซ้ำ ยังไม่ได้ทำ รอเวอร์ชั่นอัพเดท
    
    
    mysqli_query($link, "UPDATE `customeraddress` SET "
            . "`CANo` = '$CANo', "
            . "`CAApartment` = '$CAApartment', "
            . "`CARoom` = '$CARoom', "
            . "`CAFloor` = '$CAFloor', "
            . "`CASubdistrict` = '$CASubdistrict', "
            . "`CADistrict` = '$CADistrict', "
            . "`CAProvince` = '$CAProvince', "
            . "`CATel` = '$CATel', "
            . "`CATelEx` = '$CATelEx', "
            . "`CALiveYear` = '$CALiveYear', "
            . "`CALiveMonth` = '$CALiveMonth', "
            . "`CALiveStatus` = '$CALiveStatus', "
            . "`CALiveWith` = '$CALiveWith', "
            . "`CALiveWithNum` = '$CALiveWithNum', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `CAId` = '$CAId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$CId."#address\">"; 
    exit;    
}

//------รับค่ามาจากหน้าหลัก
if(isset($_GET['CAId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขที่อยู่ ".$_GET['CAId'];
    $action = 'edit';
    $CAId = trim($_GET['CAId']);
    $CId = trim($_GET['CId']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_customeraddress = mysqli_query($link, "SELECT * FROM `customeraddress` WHERE `CAId` = '$CAId'");    
    $customeraddress = mysqli_fetch_array($result_customeraddress);
    
    $CANo = $customeraddress['CANo']; //เลขที่
    $CAApartment = $customeraddress['CAApartment']; //ชื่อหอพัก,หมู่บ้าน
    $CARoom = $customeraddress['CARoom']; 
    $CAFloor = $customeraddress['CAFloor']; 
    $CASubdistrict = $customeraddress['CASubdistrict']; 
    $CADistrict = $customeraddress['CADistrict']; 
    $CAProvince = $customeraddress['CAProvince']; 
    $CAPostCode = $customeraddress['CAPostCode']; 
    $CATel = $customeraddress['CATel']; 
    $CATelEx = $customeraddress['CATelEx']; 
    $CALiveYear = $customeraddress['CALiveYear']; //อาศัยมากี่ปี
    $CALiveMonth = $customeraddress['CALiveMonth']; //กี่เดือน
    $CALiveStatus = $customeraddress['CALiveStatus']; //เช่าหรือเป็นเจ้าของ
    $CALiveWith = $customeraddress['CALiveWith']; //อยู่กับ
    $CALiveWithNum = $customeraddress['CALiveWithNum']; //จำนวนคนที่อยู่ด้วยกัน
    
    
}else{
    //สำหรับบันทึกใหม่
    $CId = trim($_GET['CId']);
    $CAId = "";
    $title = "เพิ่มที่อยู่ใหม่";
    $action = 'newsave';
    
    $CANo = ""; //เลขที่
    $CAApartment = ""; //ชื่อหอพัก,หมู่บ้าน
    $CARoom = ""; 
    $CAFloor = ""; 
    $CASubdistrict = "";
    $CADistrict = ""; 
    $CAProvince = "";
    $CAPostCode = "";
    $CATel = "";
    $CATelEx = ""; 
    $CALiveYear = ""; //อาศัยมากี่ปี
    $CALiveMonth = ""; //กี่เดือน
    $CALiveStatus = ""; //เช่าหรือเป็นเจ้าของ
    $CALiveWith = ""; //อยู่กับ
    $CALiveWithNum = ""; //จำนวนคนที่อยู่ด้วยกัน
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_customeraddress.php">
                <input type="hidden" name="CId" value="<?php echo $CId; ?>"> 
                <input type="hidden" name="CAId" value="<?php echo $CAId; ?>"> 
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อหมู่บ้าน/หอพัก </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="CAApartment" value="<?php echo $CAApartment; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">ห้อง </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CARoom" value="<?php echo $CARoom; ?>">
                </div>        
            
                <label class="control-label col-sm-1">ชั้น </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CAFloor" value="<?php echo $CAFloor; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">ที่อยู่ </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="CANo" value="<?php echo $CANo; ?>">
                </div>        
            </div>  
            <div class="form-group">
                <label class="control-label col-sm-3">ตำบล </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="CASubdistrict" value="<?php echo $CASubdistrict; ?>">
                </div>   
                
                <label class="control-label col-sm-1">อำเภอ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="CADistrict" value="<?php echo $CADistrict; ?>">
                </div>    
            </div>  
            <div class="form-group">                   
                <label class="control-label col-sm-3">จังหวัด </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="CAProvince" value="<?php echo $CAProvince; ?>">
                </div>
                
            </div> 
            <div class="form-group">                 
                <label class="control-label col-sm-3">รหัสไปรษณีย์ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="CAPostCode" value="<?php echo $CAPostCode; ?>">
                </div> 
                <label class="control-label col-sm-2">สถานภาพ </label>
                <div class="col-sm-3">
                    <select class="form-control" name="CALiveStatus" >
                        <?php
                        $result_livestatus = mysqli_query($link, "SELECT * FROM `livestatus`");
                        while($livestatus = mysqli_fetch_array($result_livestatus)){
                            if($livestatus['LSId']==$CALiveStatus){
                                $selected ="selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$livestatus['LSId']."\" ".$selected.">".$livestatus['LSName']."</option>";
                        }
                        ?>                        
                    </select>  
                </div> 
            </div>     
                  
            <div class="form-group">
                <label class="control-label col-sm-3">โทรศัพท์บ้าน </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CATel" value="<?php echo $CATel; ?>">
                </div>        
            
                <label class="control-label col-sm-2">ต่อ </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="CATelEx" value="<?php echo $CATelEx; ?>">
                </div>        
            </div>    
             <div class="form-group">
                <label class="control-label col-sm-3">พักอาศัยมา(ปี) </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CALiveYear" value="<?php echo $CALiveYear; ?>">
                </div>        
            
                <label class="control-label col-sm-2">(เดือน) </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="CALiveMonth" value="<?php echo $CALiveMonth; ?>">
                </div>        
            </div> 
            <div class="form-group">
                <label class="control-label col-sm-3">อาศัยอยู่กับ</label>
                <div class="col-sm-4">
                   <select class="form-control" name="CALiveWith" >
                        <?php
                        $result_livewith = mysqli_query($link, "SELECT * FROM `livewith`");
                        while($livewith = mysqli_fetch_array($result_livewith)){
                            if($livewith['LWId']==$CALiveWith){
                                $selected ="selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$livewith['LWId']."\" ".$selected.">".$livewith['LWName']."</option>";
                        }
                        ?>                        
                    </select>  
                </div>        
            
                <label class="control-label col-sm-2">จำนวนผู้พัก </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="CALiveWithNum" value="<?php echo $CALiveWithNum; ?>">
                </div>        
            </div>     
              
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>                     

              </div>  
            </form>            
        </div>