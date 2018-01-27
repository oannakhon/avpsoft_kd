<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");

//-------------newsave
if(isset($_POST['newsave'])){
    $CAId = newid($link, 16);
    $BId = $_SESSION['BId'];
    $ParNo = $_POST['ParNo'];
    $ParAddress = $_POST['ParAddress'];
    $url = $_POST['url'];
    
    $CADate = datemysql543($_POST['CADate']);    
    $CACardId = $_POST['CACardId'];
    $CAPreName = $_POST['CAPreName'];
    $CAName = $_POST['CAName'];
    $CALastName = $_POST['CALastName'];
    $CANickName = $_POST['CANickName'];
    $CABirthDay = datemysql543($_POST['CABirthDay']);
    $CAGender = $_POST['CAGender'];
    $CAMobile = $_POST['CAMobile'];
    $CANote = $_POST['CANote'];
    $CAAddress = $_POST['CAAddress'];   
    $CAStatus = $_POST['CAStatus'];
    
    $ComId = BIdToComId($link,$_SESSION['BId']);
    mysqli_query($link, "INSERT INTO `customeraftersale` (`ComId`, `CAId`, `BId`, `ParNo`, `ParAddress`, `CADate`, `CACardId`, `CAPreName`, `CAName`, `CALastName`, `CANickName`, `CABirthDay`, `CAGender`,`CAMobile`,`CANote`,`CAAddress`,`CAStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$ComId','$CAId','$BId','$ParNo','$ParAddress','$CADate','$CACardId','$CAPreName','$CAName','$CALastName','$CANickName','$CABirthDay','$CAGender','$CAMobile','$CANote','$CAAddress','$CAStatus','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#customer\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $CAId = $_POST['CAId'];
    $url = $_POST['url'];
    
    $CADate = datemysql543($_POST['CADate']);    
    $CACardId = $_POST['CACardId'];
    $CAPreName = $_POST['CAPreName'];
    $CAName = $_POST['CAName'];
    $CALastName = $_POST['CALastName'];
    $CANickName = $_POST['CANickName'];
    $CABirthDay = datemysql($_POST['CABirthDay']);
    $CAGender = $_POST['CAGender'];
    $CAMobile = $_POST['CAMobile'];
    $CANote = $_POST['CANote'];
    $CAAddress = $_POST['CAAddress'];    
    $CAStatus = $_POST['CAStatus'];
    mysqli_query($link, "UPDATE `customeraftersale` SET "
            . "`CADate` = '$CADate', "
            . "`CACardId` = '$CACardId', "
            . "`CAPreName` = '$CAPreName', "
            . "`CAName` = '$CAName', "
            . "`CALastName` = '$CALastName', "
            . "`CANickName` = '$CANickName', "
            . "`CABirthDay` = '$CABirthDay', "
            . "`CAGender` = '$CAGender', "
            . "`CAMobile` = '$CAMobile', "
            . "`CANote` = '$CANote', "
            . "`CAAddress` = '$CAAddress', "
            . "`CAStatus` = '$CAStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `CAId` = '$CAId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#customer\">"; 
    exit;    
}

//------รับค่ามาจากหน้าหลัก
if(isset($_GET['CAId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไขข้อมูลลูกค้า ".$_GET['CAId'];
    $action = 'edit';
    $CAId = trim($_GET['CAId']);    
    $url = trim($_GET['url']);
    
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_customeraftersale = mysqli_query($link, "SELECT * FROM `customeraftersale` WHERE `CAId` = '$CAId'");    
    $caf = mysqli_fetch_array($result_customeraftersale);
    
    $ParNo = $caf['ParNo'];
    $ParAddress = $caf['ParAddress'];
    
    $CADate = viewdate543($caf['CADate']);
    $CACardId = $caf['CACardId'];
    $CAPreName = $caf['CAPreName'];
    $CAName = $caf['CAName'];
    $CALastName = $caf['CALastName'];
    $CANickName = $caf['CANickName'];
    $CABirthDay = viewdate($caf['CABirthDay']);
    $CAGender = $caf['CAGender'];
    $CAMobile = $caf['CAMobile'];
    $CANote = $caf['CANote'];
    $CAAddress = $caf['CAAddress']; 
    $CAStatus = $caf['CAStatus'];
    
}else{
    //สำหรับบันทึกใหม่
    $ParNo = trim($_GET['ParNo']);
    $ParAddress = trim($_GET['ParAddress']);
    $url = trim($_GET['url']);    
    $CAId = "";
    $title = "เพิ่มลูกค้าใหม่ แปลง ".$ParNo;
    $action = 'newsave';
    
    $CADate = date('d/m/Y');
    $CACardId = "";
    $CAPreName = "";
    $CAName = "";
    $CALastName = "";
    $CANickName = "";
    $CABirthDay = "";
    $CAGender = 0;
    $CAMobile = "";
    $CANote = "";
    $CAAddress = "";  
    $CAStatus = 1;
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="POST" action="modal_customer.php">
                <input type="hidden" name="CAId" value="<?php echo $CAId; ?>"> 
                <input type="hidden" name="ParNo" value="<?php echo $ParNo; ?>"> 
                <input type="hidden" name="ParAddress" value="<?php echo $ParAddress; ?>"> 
                <input type="hidden" name="url" value="<?php echo $url; ?>"> 
            <div class="form-group">
                <label class="control-label col-sm-2">เลขที่บัตร</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="CACardId" name="CACardId" value="<?php echo $CACardId; ?>">
                </div> 
                <script language="javascript"> 
                    jQuery(function($){
                       $("#CACardId").mask("9-9999-99999-99-9",{placeholder:""});
                    });
                </script>
                <label class="control-label col-sm-2">วันเกิด</label>
                
                <div class="col-sm-3">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="CABirthDay" value="<?php echo $CABirthDay; ?>">
                </div>  
                               
            </div> 
                
            <div class="form-group">
                <label class="control-label col-sm-2">ชื่อ</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="CAPreName" value="<?php echo $CAPreName; ?>" placeholder="คำนำหน้า">
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="CAName" value="<?php echo $CAName; ?>">
                </div>  
                <label class="control-label col-sm-2">นามสกุล</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="CALastName" value="<?php echo $CALastName; ?>">
                </div>                
            </div>
                
            <div class="form-group">
                <label class="control-label col-sm-2">ชื่อเล่น</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="CANickName" value="<?php echo $CANickName; ?>">
                </div> 
                <label class="control-label col-sm-2">เพศ</label>
                
                <div class="col-sm-3">
                    <select class="form-control" name="CAGender">
                        <option value="0" <?php if($CAGender==0) echo "selected"; ?>>ไม่ระบุ</option>
                        <option value="1" <?php if($CAGender==1) echo "selected"; ?>>ชาย</option>
                        <option value="2" <?php if($CAGender==2) echo "selected"; ?>>หญิง</option>
                    </select>
                </div> 
            </div>   
                
            <div class="form-group">
                <label class="control-label col-sm-2">ที่อยู่</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="CAAddress" value="<?php echo $CAAddress; ?>">
                </div>                                
            </div> 
                
             <div class="form-group">
                <label class="control-label col-sm-2">เบอร์มือถือ</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="CAMobile" name="CAMobile" value="<?php echo $CAMobile; ?>">
                </div>
               
                <label class="control-label col-sm-2">หมายเหตุ</label>
                
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="CANote" value="<?php echo $CANote; ?>">
                </div> 
            </div>
                
              <div class="form-group">
                  <label class="control-label col-sm-2">สถานะ</label>
                  <div class="col-sm-3">
                      <select class="form-control" name="CAStatus">
                        <option value="1" <?php if($CAStatus==1) echo "selected"; ?>>เจ้าบ้าน</option>
                        <option value="2" <?php if($CAStatus==2) echo "selected"; ?>>อาศัยอยู่</option>
                    </select>    
                  </div>
                  <label class="control-label col-sm-3 col-sm-offset-1">บันทึกข้อมูลวันที่</label>
                  <div class="col-sm-3">
                      <input type="text" id="datepicker9" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdatebe($CADate); ?>" name="CADate">
                </div>
              </div> 
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-2">
                      <button type="submit" class="btn btn-primary col-sm-12" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                </div>
            </form>            
        </div>


        
        <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker(); 
            
        });
    
    $(function () {
            
               
                $('#CABirthDay').datetimepicker({
                    format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
                });
            
        });
        
        
        
        </script>