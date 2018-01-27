<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

//---Del --รอตรวจสอบ
if(isset($_GET['del'])){    
    $url = $_GET['url']; 
    $BId = $_SESSION['BId'];    
    $DebId = $_GET['del'];
    $DebStatus = 0;    
    
    mysqli_query($link, "UPDATE `debt` SET `DebStatus`='$DebStatus', `UpdateBy`='$UpdateBy', `UpdateDate`='$UpdateDate' "
            . "WHERE `BId` = '$BId' AND `DebId` = '$DebId'");
   
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#central\">"; 
    exit;    
}

//-------------newsave
if(isset($_POST['newsave'])){    
    $BId = $_SESSION['BId'];    
    $url = $_POST['url'];   
    $RecId = newid($link, 19); // เลขที่ใบเสร็จของโปรแกรม    
    $RefId = $_POST['RefId']; // เลขที่อ้างอิง = ใบแจ้งหนี้(ถ้ามี)
    $VolNo = newVolNo($link, $BId, 'central');
    $RecVol = $VolNo[0];
    $RecNo = $VolNo[1];
    $ParNo = $_POST['ParNo'];
    $ParAddress = $_POST['ParAddress'];
    $RecDate = datemysql543($_POST['RecDate']);
    $CusId = $_POST['CusId'];
    $CusName = $_POST['CusName'];
    $CusAddress = $_POST['CusAddress'];
    $RecName = $_POST['RecName'];
    $Rec1 = $_POST['Rec1'];
    $Rec2 = $_POST['Rec2'];
    $Rec3 = $_POST['Rec3'];
    $Rec4 = $_POST['Rec4'];
    $RecNote1 = $_POST['RecNote1'];
    $RecNote2 = $_POST['RecNote2'];
    $RecNote3 = $_POST['RecNote3'];
    $RecNote4 = $_POST['RecNote4'];
    $RecNote5 = $_POST['RecNote5'];
    $RecNote6 = $_POST['RecNote6']; 
    
    $RemainPay = $_POST['RemainPay'];
    $RecGrandtotal = $Rec1+$Rec2+$Rec3+$Rec4;  

    if($RemainPay>$RecGrandtotal){
        //ชำระบางส่วน
        $DebStatus = 2;
    }else{
        //ชำระเต็ม
        $DebStatus = 3;
    } 
    $result_RVId = mysqli_query($link, "SELECT * FROM `receiptvoltype` WHERE `BId` = '$_SESSION[BId]' AND `RVType` = 'central' ");
    $receiptvoltype = mysqli_fetch_array($result_RVId);
    $RVId = $receiptvoltype['RVId'];
    
    
    
    $ComId = BIdToComId($link, $BId);
    
    
    mysqli_query($link, "INSERT INTO `receipt` (`RVId`,`ComId`,`RecId`, `RecVol`, `RecNo`, `RefId`, `BId`, `ParNo`, `ParAddress`, `CusId`, `CusName`, `CusAddress`, `RecDate`, `RecName`, `Rec1`,`Rec2`,`Rec3`,`Rec4`,`RecNote1`,`RecNote2`,`RecNote3`,`RecNote4`,`RecNote5`,`RecNote6`,`RecGrandtotal`, `RecStatus`, `CreateBy`, `CreateDate`) "
                                    . "VALUES ('$RVId','$ComId','$RecId','$RecVol','$RecNo','$RefId','$BId', '$ParNo','$ParAddress','$CusId','$CusName','$CusAddress','$RecDate','$RecName','$Rec1','$Rec2','$Rec3','$Rec4','$RecNote1','$RecNote2','$RecNote3','$RecNote4','$RecNote5','$RecNote6','$RecGrandtotal','1','$CreateBy','$CreateDate')");
    
   
    
    //update in debt ว่าจ่ายเต็มจำนวน หรือบางส่วน โดยจะต้องคำนวณว่ายอด Grandtotal = หนี้คงเหลือหรือไม่
    //DebStatus,PayDate,RefPay
    mysqli_query($link, "UPDATE `debt` SET `DebStatus`= '$DebStatus', `PayDate` = '$RecDate', `RefPay` = '$RecId' WHERE `DebId` = '$RefId'");
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#central\">"; 
    exit;    
}

if(isset($_POST['edit'])){
    
    $url = $_POST['url'];   
    $RecId = $_POST['RecId'];
    $RecDate = datemysql543($_POST['RecDate']);    
    $CusName = $_POST['CusName'];
    $CusAddress = $_POST['CusAddress'];
    $RecName = $_POST['RecName'];
    $Rec1 = $_POST['Rec1'];
    $Rec2 = $_POST['Rec2'];
    $Rec3 = $_POST['Rec3'];
    $Rec4 = $_POST['Rec4'];
    $RecNote1 = $_POST['RecNote1'];
    $RecNote2 = $_POST['RecNote2'];
    $RecNote3 = $_POST['RecNote3'];
    $RecNote4 = $_POST['RecNote4'];
    $RecNote5 = $_POST['RecNote5'];
    $RecNote6 = $_POST['RecNote6'];  
    $RecGrandtotal = $Rec1+$Rec2+$Rec3+$Rec4;  
    
    mysqli_query($link, "UPDATE `receipt` SET "
            . "`RecDate` = '$RecDate', "
            . "`CusName` = '$CusName', "
            . "`CusAddress` = '$CusAddress', "
            . "`RecName` = '$RecName', "
            . "`Rec1` = '$Rec1', "
            . "`Rec2` = '$Rec2', "
            . "`Rec3` = '$Rec3', "
            . "`Rec4` = '$Rec4', "
            . "`RecNote1` = '$RecNote1', "
            . "`RecNote2` = '$RecNote2', "
            . "`RecNote3` = '$RecNote3', "
            . "`RecNote4` = '$RecNote4', "
            . "`RecNote5` = '$RecNote5', "
            . "`RecNote6` = '$RecNote6', "
            . "`RecGrandtotal` = '$RecGrandtotal', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `RecId` = '$RecId'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."?RecDateStart=".$_POST['RecDate']."&RecDateEnd=".$_POST['RecDate']."&RecVol=%25&order=DESC\">"; 
    exit;    
}

//------รับค่ามาจากหน้าหลัก--
if(isset($_GET['RecId'])){
    $RecId = trim($_GET['RecId']);
    $result_receipt = mysqli_query($link, "SELECT * FROM `receipt` WHERE `RecId` = '$RecId' AND `BId` = '$BId'");
    $receipt = mysqli_fetch_array($result_receipt);
    $RecVol = $receipt['RecVol'];
    $RecNo= $receipt['RecNo'];
    $RecDate = $receipt['RecDate'];
    $RecName = $receipt['RecName'];
    $RefId = $receipt['RefId'];
    $ParNo = $receipt['ParNo'];
    $ParAddress = $receipt['ParAddress'];
    $url = $_GET['url']; 
    $CName = ""; 
    $RemainPay = 0;
    $CusId = $receipt['CusId'];
    $CAName = $receipt['CusName'];
    $CAAddress = $receipt['CusAddress'];
    $Rec1= $receipt['Rec1'];
    $Rec2= $receipt['Rec2'];
    $Rec3= $receipt['Rec3'];
    $Rec4= $receipt['Rec4'];
    $RecNote1= $receipt['RecNote1'];
    $RecNote2= $receipt['RecNote2'];
    $RecNote3= $receipt['RecNote3'];
    $RecNote4= $receipt['RecNote4'];
    $RecNote5= $receipt['RecNote5'];
    $RecNote6= $receipt['RecNote6'];
    $RecGrandtotal = $Rec1+$Rec2+$Rec3+$Rec4;
    
    $url = trim($_GET['url']);    
    $title = "แก้ไขใบเสร็จ เล่มที่".$RecVol." เลขที่ ".$RecNo;
    $action = 'edit';  
    
    $ReadOnly = array("");   
    
}else{
    //สำหรับบันทึกใหม่ เริ่มที่นี่ Start
    $RecId = "";  //เลขที่ใบเสร็จ      
    $RecDate = date('Y-m-d');
    $type = $_GET['type']; //เล่มใบเสร็จ    
    $RemainPay = 0;
    $ReadOnly = array(""); //ห้ามแก้ไข
    if($type =="central"){
        //ค่าส่วนกลาง
        $ReadOnly = array("RemainPay","CusName","CusAddress","RecName");        
    }
    
    if(isset($_GET['RefId'])){
        $RefId = trim($_GET['RefId']);  //จากตารางตั้งหนี้ debt      
        $result_debt = mysqli_query($link, "SELECT * FROM `debt` "
                . "WHERE `BId` = '$BId' AND `DebId` = '$RefId'");
        
        $debt = mysqli_fetch_array($result_debt);
        $ParNo = $debt['ParNo']; //เลขที่แปลง
        $ParAddress = $debt['RefId']; //บ้านเลตที่
        $CusId = LastCAId1($link,$BId,$ParNo); //หา CAId ลูกค้าที่เป็นเจ้าบ้าน
        $CAName = CAName($link, $BId, $CusId); //หาชื่อจาก CAId ด้านบน        
        $CAAddress = CAAddress($link, $BId, $CusId); //หาที่อยู่  
        $RecName = $debt['DebName']; //รายการในใบเสร็จ
        $DebTotal = $debt['DebTotal']; //เงินสด จำนวนเงิน
        
        //หารายการที่ชำระไว้บางส่วนแล้ว
        $result_receipt = mysqli_query($link, "SELECT SUM(RecGrandtotal) FROM `receipt` "
                . "WHERE `BId` = '$BId' AND `RefId` = '$RefId' AND `RecStatus` = '1'");
        //ถ้ามีการชำระไว้บ้างแล้ว
        if(mysqli_num_rows($result_receipt)==1){
            $receipt = mysqli_fetch_array($result_receipt);
            $paid =  $receipt[0];
        }else{
            $paid = 0;
        }        
        $RemainPay = $DebTotal - $paid; //คำนวนเงินคงเหลือที่ต้องจ่าย
        $Rec1 = $RemainPay;              
        //นำค่าไปแสดงรายการข้างล่าง
    }else{
        //สำหรับบันทึกอื่นๆ
        $RefId = "";
        $ParNo = ""; 
        $ParAddress = "";
        $CusId = "";
        $CAName = "";
        $CAAddress = "";
        $RecName = "";
        $Rec1 = ""; //เงินสด
    }    
    
    
    $Rec2 = ""; //เช็ค
    $Rec3 = ""; //บัตรเครดิต
    $Rec4 = ""; //อื่นๆ
    $RecNote1 = ""; //เช็คเลขที่
    $RecNote2 = ""; //เช็คธนาคาร
    $RecNote3 = ""; //เช็คลงวันที่
    $RecNote4 = ""; //บัตรเครดิตเลขที่
    $RecNote5 = ""; //บัตรเครดิตธนาคาร
    $RecNote6 = ""; //บัตรเครดิตวันหมดอายุ
    
    //---สำหรับงานซ่อม
    if(isset($_GET['SId'])){
        $RefId = trim($_GET['SId']);
        $result_service = mysqli_query($link, "SELECT * FROM `service` WHERE `SId` = '$RefId' AND `BId` = '$_SESSION[BId]'");
        $service = mysqli_fetch_array($result_service);
        
        $ParNo = $service['ParNo'];
        $ParAddress = $service['ParAddress'];
        $CAName = $service['SCustomer'];
        $RecName = "ค่าบริการงานซ่อมบำรุง";        
        $RemainPay = $service['SPrice'];
        $Rec1 = $service['SPrice'];        
    }
    //----จบสำหรับงานซ่อม
    
    
    $url = trim($_GET['url']);    
    $title = "รับชำระเงิน ".$RecName;
    $action = 'newsave';  
    
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="POST" action="modal_receipt.php">
                <input type="hidden" name="RecId" value="<?php echo $RecId; ?>"> 
                <input type="hidden" name="RefId" value="<?php echo $RefId; ?>"> 
                <input type="hidden" name="ParNo" value="<?php echo $ParNo; ?>"> 
                <input type="hidden" name="ParAddress" value="<?php echo $ParAddress; ?>"> 
                <input type="hidden" name="url" value="<?php echo $url; ?>"> 
                
                
            <div class="form-group">
                <label class="control-label col-sm-2">วันที่ชำระ</label>            
                
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="RecDate" value="<?php echo viewdate($RecDate); ?>">
                </div> 
                
                <label class="control-label col-sm-2">ต้องชำระ</label>            
                
                <div class="col-sm-4">
                    <input type="hidden" name="RemainPay" value="<?php echo $RemainPay; ?>">
                    <input type="text" class="form-control" value="<?php echo number_format($RemainPay,2); ?>" <?php if(in_array("RemainPay",$ReadOnly)) echo "readonly"; ?>>
                </div>
                               
            </div> 
                
            <div class="form-group">
                <label class="control-label col-sm-2">ผู้ชำระ</label>             
                <input type="hidden" name="CusId" value="<?php echo $CusId; ?>">
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="CusName" value="<?php echo $CAName; ?>" <?php if(in_array("CusName",$ReadOnly)) echo "readonly"; ?> placeholder="ชื่อผู้ชำระ">
                </div>                           
                
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="CusAddress" value="<?php echo $CAAddress; ?>" <?php if(in_array("CusAddress",$ReadOnly)) echo "readonly"; ?> placeholder="ที่อยู่ผู้ชำระ">
                </div>
                               
            </div>     
                
            <div class="form-group">
                <label class="control-label col-sm-2">ชื่อรายการ</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="RecName" value="<?php echo $RecName; ?>" <?php if(in_array("RecName",$ReadOnly)) echo "readonly"; ?>>
                </div>               
            </div>    
             
            <div class="form-group">
                <label class="control-label col-sm-2 text-info">ชำระโดย</label>
                <div class="col-sm-10">
                    <hr>
                </div>               
            </div>  
            
            <div class="form-group">
                <label class="control-label col-sm-2">เงินสด</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="Rec1" value="<?php echo $Rec1; ?>">
                </div>               
            </div>   
            <div class="form-group">
                <label class="control-label col-sm-2">เช็ค</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="Rec2" value="<?php echo $Rec2; ?>">
                </div>    
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="RecNote1" placeholder="เลขที่เช็ค" value="<?php echo $RecNote1; ?>">
                </div>  
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="RecNote2" placeholder="ธนาคาร" value="<?php echo $RecNote2; ?>">
                </div>  
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="RecNote3" placeholder="เช็คลงวันที่" value="<?php echo $RecNote3; ?>">
                </div>  
            </div>     
            <div class="form-group">
                <label class="control-label col-sm-2">บัตรเครดิต</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="Rec3" value="<?php echo $Rec3; ?>">
                </div>    
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="RecNote4"placeholder="เลขที่บัตรเครดิต" value="<?php echo $RecNote4; ?>">
                </div>  
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="RecNote5" placeholder="ธนาคาร" value="<?php echo $RecNote5; ?>">
                </div>  
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="RecNote6" placeholder="วันที่บัตรหมดอายุ" value="<?php echo $RecNote6; ?>">
                </div>  
            </div>    
            <div class="form-group">
                <label class="control-label col-sm-2">อื่นๆ</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="Rec4" value="<?php echo $Rec4; ?>">
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
           $(document).ready(function() {
                
                 $('.datepicker').datepicker();
                 
           
            });
</script>