<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//---Del
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
    $url = $_POST['url'];    
    
    $BId = $_SESSION['BId'];    
    $ParNo = $_POST['ParNo'];
    $RefId = $_POST['ParAddress'];
    $DebId = newidBId($link, 18);    
    $DebDate = datemysql543($_POST['DebDate']); 
    $DebName = $_POST['DebName'];
    $ServiceStart = datemysql543($_POST['ServiceStart']);
    $ServiceEnd = datemysql543($_POST['ServiceEnd']);
    $DueDate = datemysql543($_POST['DueDate']);
    $DebTotal = $_POST['DebTotal'];
    $DebStatus = $_POST['DebStatus'];    
   
    mysqli_query($link, "INSERT INTO `debt` (`BId`, `ParNo`, `RefId`, `DebId`, `DebDate`, `DebName`, `ServiceStart`, `ServiceEnd`, `DueDate`, `DebTotal`, `DebStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$BId','$ParNo','$RefId','$DebId','$DebDate','$DebName','$ServiceStart','$ServiceEnd','$DueDate','$DebTotal','$DebStatus','$CreateBy','$CreateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#central\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $DebId = $_POST['DebId'];
    $url = $_POST['url'];
    
    $DebDate = datemysql543($_POST['DebDate']); 
    $DebName = $_POST['DebName'];
    $ServiceStart = datemysql543($_POST['ServiceStart']);
    $ServiceEnd = datemysql543($_POST['ServiceEnd']);
    $DueDate = datemysql543($_POST['DueDate']);
    $DebTotal = $_POST['DebTotal'];
    $DebStatus = $_POST['DebStatus'];       
    
    mysqli_query($link, "UPDATE `debt` SET "
            . "`DebDate` = '$DebDate', "
            . "`DebName` = '$DebName', "
            . "`ServiceStart` = '$ServiceStart', "
            . "`ServiceEnd` = '$ServiceEnd', "
            . "`DueDate` = '$DueDate', "
            . "`DebTotal` = '$DebTotal', "
            . "`DebStatus` = '$DebStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate' "
            . "WHERE `DebId` = '$DebId'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=".$url."#central\">"; 
    exit;    
}

//------รับค่ามาจากหน้าหลัก
if(isset($_GET['DebId'])){
    //เปิดของเก่ามา Edit    
    $DebId = trim($_GET['DebId']);    
    $url = trim($_GET['url']);
    
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_debt = mysqli_query($link, "SELECT * FROM `debt` WHERE `DebId` = '$DebId' AND `BId` = '$_SESSION[BId]'");    
    $debt = mysqli_fetch_array($result_debt);  
    
    $ParNo = $debt['ParNo'];
    
    $result_parcel = mysqli_query($link, "SELECT `ParArea`, `ParAddress`, `HPId` "
            . "FROM `parcel` "
            . "WHERE `BId` = '$_SESSION[BId]' "
            . "AND `ParNo` = '$ParNo' ");
    
    $parcel = mysqli_fetch_array($result_parcel);
    $ParAddress = $parcel['ParAddress'];
    $ParArea = $parcel['ParArea'];
    $HPName = HPName($link,$parcel['HPId']);    
   
    $title = "แก้ไขตั้งหนี้บ้านเลขที่ ".$ParAddress."  แบบบ้าน ".$HPName." ".$ParArea." ตร.ว.";
    $action = 'edit';    
    $DebDate = viewdate543($debt['DebDate']);  
    $DebName = $debt['DebName'];  
    $ServiceStart = viewdate543($debt['ServiceStart']);   
    $ServiceEnd = viewdate543($debt['ServiceEnd']);    
    $DebTotal = $debt['DebTotal'];    
    $DueDate = viewdate($debt['DueDate']);
    $DebStatus = $debt['DebStatus'];    
    
    //เช็คว่ามีการชำระเงินหรือไม่-------------------
    
    $result_check = mysqli_query($link, "SELECT * FROM `debt` WHERE `DebId` = '$DebId' AND `DebStatus` != '1' ");
    if(mysqli_num_rows($result_check)!=0){
        $ReadOnly = "ReadOnly";
    }else{
        $ReadOnly = "";
    }
    
    
    //--------------------------
    
    
}else{
    //สำหรับบันทึกใหม่
    $DebId = "";
    $ParNo = trim($_GET['ParNo']);
    
    $result_parcel = mysqli_query($link, "SELECT `ParArea`, `ParAddress`, `HPId` "
            . "FROM `parcel` "
            . "WHERE `BId` = '$_SESSION[BId]' "
            . "AND `ParNo` = '$ParNo' ");
    $parcel = mysqli_fetch_array($result_parcel);
    $ParAddress = $parcel['ParAddress'];
    $ParArea = $parcel['ParArea'];
    $HPName = HPName($link,$parcel['HPId']);
    
    $url = trim($_GET['url']);    
    $title = "ตั้งหนี้บ้านเลขที่ ".$ParAddress."  แบบบ้าน ".$HPName." ".$ParArea." ตร.ว.";
    $action = 'newsave';    
    $DebDate = date('d/m/Y');  
    $DebName = "";    
    $ServiceStart = ""; 
    $ServiceEnd =  "";  
    $DebTotal = "";    
    $DueDate = "";
    $DebStatus = 1;
    $ReadOnly = "";
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="POST" action="modal_debpublicutility.php">
                <input type="hidden" name="DebId" value="<?php echo $DebId; ?>"> 
                <input type="hidden" name="ParNo" value="<?php echo $ParNo; ?>"> 
                <input type="hidden" name="ParAddress" value="<?php echo $ParAddress; ?>"> 
                <input type="hidden" name="url" value="<?php echo $url; ?>"> 
            <div class="form-group">
                <label class="control-label col-sm-3">วันที่ตั้งหนี้</label>              
                
                <div class="col-sm-4">
                    <input type="text" name="DebDate" class="form-control" data-provide="datepicker" data-date-language="th-th"  value="<?php echo viewdatebe($DebDate); ?>">
                
                
                </div>  
                               
            </div> 
                
            <div class="form-group">
                <label class="control-label col-sm-3">ชื่อรายการ</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="DebName" value="<?php echo $DebName; ?>">
                </div>               
            </div>
                
            <div class="form-group">
                <label class="control-label col-sm-3">ระยะเวลาบริการ</label>
                <div class="col-sm-4">
                    <input type="text" name="ServiceStart" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdatebe($ServiceStart); ?>" >
                </div> 
                <label class="control-label col-sm-1">ถึง</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdatebe($ServiceEnd); ?>" name="ServiceEnd" >
                </div> 
                
            </div>   
                
            <div class="form-group">
                <label class="control-label col-sm-3">จำนวนเงิน</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="DebTotal" value="<?php echo $DebTotal; ?>" <?php echo $ReadOnly; ?>>
                </div>                                
            </div> 
                
             <div class="form-group">
                <label class="control-label col-sm-3">กำหนดชำระเงิน</label>
                <div class="col-sm-4">
                    <input type="text" id="datepicker3" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo $DueDate; ?>" name="DueDate"  >
                </div>
            </div>
                
            <div class="form-group">
                <label class="control-label col-sm-3">สถานะ</label>
                <div class="col-sm-4">
                    <select class="form-control" name="DebStatus">
                        <?php
                        $result_debtstatus = mysqli_query($link, "SELECT * FROM `debtstatus`");
                        while($ds = mysqli_fetch_array($result_debtstatus)){
                            if($ds['DSId']==$DebStatus){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$ds['DSId']."\" ".$selected.">".$ds['DSName']."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>                
                
              <div class="form-group">
                  <div class="col-sm-3 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-12" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                  
              </div>  
            </form>            
        </div>
<script type="text/javascript">
          $(document).ready(function () {              
              $('.datepicker').datepicker();             
        });
</script>