<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];
//-------------newsave
if(isset($_POST['newsave'])){
    $ParNo = $_POST['ParNo'];
    $ParDeed = $_POST['ParDeed'];
    $ParAddress = $_POST['ParAddress'];
    $ParArea = $_POST['ParArea'];   
    $HPId = $_POST['HPId'];
    $ZoneId = $_POST['ZoneId'];
    $PSId = $_POST['PSId'];
    $HomeZoneId = $_POST['HomeZoneId'];
    $ParMortgagedebt = $_POST['ParMortgagedebt'];
    //check ว่ามีเลขแปลงที่ซ้ำกันหรือไม่ ?
    $result_check = mysqli_query($link, "SELECT * FROM `parcel` WHERE `ParNo` = '$ParNo' "
            . "AND `ParStatus` != '99' AND `BId` = '$BId' ");
    
    if(mysqli_num_rows($result_check)==0){
        mysqli_query($link, "INSERT INTO `parcel` (`BId`, `ZoneId`, `ParNo`, `ParDeed`, `ParAddress`, `ParArea`, `HPId`, `ParStatus`, `CreateBy`, `CreateDate`, `ParStatus`, `HomeZoneId`, `ParMortgagedebt`) "
            . "VALUES ('$BId','$ZoneId','$ParNo','$ParDeed', '$ParAddress', '$ParArea', '$HPId','1','$CreateBy','$CreateDate','$PSId','$HomeZoneId','$ParMortgagedebt')");
    }
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=parcel.php\">"; 
    exit;    
}
if(isset($_POST['edit'])){    
    $id = trim($_POST['id']);
    $ParNo = $_POST['ParNo'];
    $ParDeed = $_POST['ParDeed'];
    $ParAddress = $_POST['ParAddress'];
    $ParArea = $_POST['ParArea'];   
    $HPId = $_POST['HPId'];
    $ZoneId =  $_POST['ZoneId'];
    $PSId = $_POST['PSId'];
    $HomeZoneId = $_POST['HomeZoneId'];
    $ParMortgagedebt = $_POST['ParMortgagedebt'];
    mysqli_query($link, "UPDATE `parcel` SET "
            . "`BId` = '$BId', "
            . "`ParNo` = '$ParNo', "
            . "`ParDeed` = '$ParDeed', "
            . "`ParAddress` = '$ParAddress', "
            . "`ParArea` = '$ParArea', "
            . "`HPId` = '$HPId', "
            . "`ZoneId` = '$ZoneId', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate', "
            . "`ParStatus` = '$PSId',"
            . "`HomeZoneId` = '$HomeZoneId', "
            . "`ParMortgagedebt` = '$ParMortgagedebt' "
            . "WHERE `id` = '$id'");
       
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=parcel.php\">"; 
    exit;    
}



//------รับค่ามาจากหน้าหลัก
if(isset($_GET['id'])){
    //เปิดของเก่ามา Edit
    
    $action = 'edit';
    $id = trim($_GET['id']);
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_parcel = mysqli_query($link, "SELECT * FROM `parcel` WHERE `id` = '$id'");
    $parcel = mysqli_fetch_array($result_parcel);
    $title = "แก้ไขแปลงที่ ";
    $ParNo = $parcel['ParNo'];
    $ParDeed = $parcel['ParDeed'];
    $ParAddress = $parcel['ParAddress'];
    $ParArea = $parcel['ParArea'];   
    $HPId = $parcel['HPId'];
    $ZoneId = $parcel['ZoneId'];
    $HomeZoneId = $parcel['HomeZoneId'];
    $PSId = $parcel['ParStatus'];
    $ParNumland = $parcel['ParNumland'];
    $ParSurvey = $parcel['ParSurvey'];
    $ParBook = $parcel['ParBook'];
    $ParPage = $parcel['ParPage'];
    $ParMortgagedebt = $parcel['ParMortgagedebt'];
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มแปลงใหม่";
    $action = 'newsave';
    $ParNo = "";
    $BId = "";
    $HPId= "";
    $ZoneId = "";
    $id = "";
    $ParDeed = "";
    $ParAddress = "";
    $ParArea = ""; 
    $PSId = "";
    $HomeZoneId = "";
    $ParNumland = "";
    $ParSurvey = "";
    $ParBook = "";
    $ParPage = "";
    $ParMortgagedebt = "";
    
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title." ".$ParNo; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_parcel.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
              <div class="form-group row">
                <label class="control-label col-sm-2">เลขแปลง</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParNo" value="<?php echo $ParNo; ?>" required>
                </div> 
                
                <label class="control-label col-sm-2 text-center">เลขโฉนด</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParDeed" value="<?php echo $ParDeed; ?>" required>
                </div> 
              </div>
                
              
                
                
              <div class="form-group row">
                <label class="control-label col-sm-2">ขนาดที่ดิน</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParArea" value="<?php echo $ParArea; ?>" required>
                </div>
                
                
                <label class="control-label col-sm-2">บ้านเลขที่</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParAddress" value="<?php echo $ParAddress; ?>">
                </div>  
              </div> 
                
              
              <div class="form-group row">
                <label class="control-label col-sm-2">แบบบ้าน</label>
                <div class="col-sm-3">
                    <select class="form-control" name="HPId">
                        <option value="0" <?php if($HPId==""){echo "selected";} ?> >ยังไม่ได้กำหนด</option>
                        <?php
                        $result_homeplan = mysqli_query($link,"SELECT * FROM `homeplan` WHERE `HPStatus` ='1' AND `BId` = '$_SESSION[BId]'");
                        while($homeplan = mysqli_fetch_array($result_homeplan)){
                            if($homeplan['HPId']==$HPId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$homeplan['HPId']."\" ".$selected.">".$homeplan['HPName']."</option>";
                        }                        
                        ?>
                    </select>
                </div>
                
                
                <label class="control-label col-sm-2">โซนที่ดิน</label>
                <div class="col-sm-3">
                <select class="form-control" name="ZoneId">
                    <option value="0" <?php if($ZoneId==""){echo "selected";} ?> >ยังไม่ได้กำหนด</option>
                        <?php
                        $result_zone = mysqli_query($link,"SELECT * FROM `zone` WHERE `BId` = '$_SESSION[BId]'");
                        while($zone = mysqli_fetch_array($result_zone)){
                            if($zone['ZoneId']==$ZoneId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$zone['ZoneId']."\" ".$selected.">".$zone['ZoneName']."</option>";
                        }                        
                        ?>
                    </select>
                </div>
              </div>
               
                
                
              <div class="form-group row">
                <label class="control-label col-sm-2">โซนบ้าน</label>
                <div class="col-sm-3">
                <select class="form-control" name="HomeZoneId">
                    <option value="0" <?php if($HomeZoneId==""){echo "selected";} ?> >ยังไม่ได้กำหนด</option>
                        <?php
                        $result_homezone = mysqli_query($link,"SELECT * FROM `homezone` WHERE `BId` = '$_SESSION[BId]' ORDER BY `HomeZoneId` ASC");
                        while($homezone = mysqli_fetch_array($result_homezone)){
                            
                            //หาราคาโซนบ้าน
                                                                                    
                            $result_homezonesub = mysqli_query($link, "SELECT * FROM `homezonesub` WHERE `HomeZoneId` = '$homezone[HomeZoneId]' ORDER BY `HomeZoneDate` DESC LIMIT 1");
                            $homezonesub = mysqli_fetch_array($result_homezonesub);        
                            
                            if($homezone['HomeZoneId']==$HomeZoneId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$homezone['HomeZoneId']."\" ".$selected.">".$homezone['HomeZoneName']."  ( ".number_format($homezonesub['HomeZonePrice'])." บาท )</option>";
                        }                        
                        ?>
                    </select>
                </div>
                
                <label class="control-label col-sm-2">เลขที่ดิน</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParNumland" value="<?php echo $ParNumland; ?>">
                </div>
              </div>  
                
               
                
                
                
              <div class="form-group row">
                <label class="control-label col-sm-2">หน้าสำรวจ</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParSurvey" value="<?php echo $ParSurvey; ?>">
                </div>
                
                <label class="control-label col-sm-2">เล่ม</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParBook" value="<?php echo $ParBook; ?>">
                </div>
              </div>  
                
             
                
              <div class="form-group row">
                <label class="control-label col-sm-2">หน้า</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="ParPage" value="<?php echo $ParPage; ?>">
                </div>
                
                <label class="control-label col-sm-2">สถานะ</label>
                <div class="col-sm-3">
                <select class="form-control" name="PSId">
                    <option value="0" <?php if($PSId==""){echo "selected";} ?> >ยังไม่ได้กำหนด</option>
                        <?php
                        $result_parcelstatus = mysqli_query($link,"SELECT * FROM `parcelstatus`");
                        while($parcelstatus = mysqli_fetch_array($result_parcelstatus)){
                            if($parcelstatus['PSId']==$PSId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$parcelstatus['PSId']."\" ".$selected.">".$parcelstatus['PSName']."</option>";
                        }                        
                        ?>
                    </select>
                </div>
              </div>  
                
                
                <div class="form-group row">
                <label class="control-label col-sm-2">หนี้จำนองในสัญญา</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="ParMortgagedebt" value="<?php echo $ParMortgagedebt; ?>">
                </div>
             
              </div> 
                
              
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-2">
                      <button type="submit" class="btn btn-primary col-sm-4" name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
                      

              </div>  
            </form>
            
        </div>