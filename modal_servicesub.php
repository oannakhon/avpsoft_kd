<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

//---ลบรายการย่อย
if(isset($_GET['del'])){
    $SSId = trim($_GET['del']);
    $SId = trim($_GET['SId']);
    $url = trim($_GET['url']);    
    
    mysqli_query($link, "UPDATE `servicesub` "
            . "SET `SSStatus` = '0',"
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate'"
            . "WHERE `SSId` = '$SSId' "
            . "AND `BId` = '$BId'");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=".$SId."&url=".$url."\">"; 
    exit; 
}

//-------------newsave
if(isset($_POST['newsave'])){
    
    $SSId = newidBId($link, 20);
    $SId = trim($_POST['SId']);
    
    $SSName = trim($_POST['SSName']);
    $SGId = trim($_POST['SGId']);
    $SEId = trim($_POST['SEId']);
    $SSDetail = trim($_POST['SSDetail']);
    $SSPrice = trim($_POST['SSPrice']);
    $SSStatus = trim($_POST['SSStatus']);  
    $url = trim($_POST['url']);    
    $SSNote = trim($_POST['SSNote']);
    
    mysqli_query($link, "INSERT INTO `servicesub` (`SSId`, `SId`, `BId`, `SSName`, `SGIdL2`, `SEId`, `SSDetail`, `SSPrice`, `SSStatus`, `CreateBy`, `CreateDate`, `SSNote`) "
            . "VALUES ('$SSId','$SId','$BId','$SSName','$SGId','$SEId','$SSDetail','$SSPrice','$SSStatus','$CreateBy','$CreateDate','$SSNote')");
    
    
    // Find LV1 ---------------------------------------------------
    $result = mysqli_query($link, "SELECT `a`.`SGParentId`, `b`.`SGIdL2` FROM "
        . "`servicegroup` AS `a` "
        . "JOIN `servicesub` AS `b`"
        . "ON `a`.`SGId` = `b`.`SGIdL2` "
        . "WHERE `b`.`SGIdL2` = '$SGId'");

    $servicegroup = mysqli_fetch_array($result);
    
    mysqli_query($link, "UPDATE `servicesub` SET"
            . "`SGIdL1` = '$servicegroup[0]'"
            . "WHERE `SGIdL2` = '$servicegroup[1]'"
            . "AND `SSId` = '$SSId'");
    //-----------------------------------------------------------------
    
    // Find LV0 ---------------------------------------------------
    $result0 = mysqli_query($link, "SELECT `a`.`SGParentId`, `b`.`SGIdL1` FROM "
        . "`servicegroup` AS `a` "
        . "JOIN `servicesub` AS `b`"
        . "ON `a`.`SGId` = `b`.`SGIdL1` "
        . "WHERE `b`.`SGIdL1` = '$servicegroup[0]'");

    $servicegroup0 = mysqli_fetch_array($result0);
    
    mysqli_query($link, "UPDATE `servicesub` SET"
            . "`SGIdL0` = '$servicegroup0[0]'"
            . "WHERE `SGIdL1` = '$servicegroup0[1]'"
            . "AND `SSId` = '$SSId'");
    //-----------------------------------------------------------------
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=".$SId."&url=".$url."\">"; 
    exit;    
}
if(isset($_POST['edit'])){
    $SSId = trim($_POST['SSId']);
    $SId = trim($_POST['SId']);
    
    $SSName = trim($_POST['SSName']);
    $SGId = trim($_POST['SGId']);
    $SEId = trim($_POST['SEId']);
    $SSDetail = trim($_POST['SSDetail']);
    $SSPrice = trim($_POST['SSPrice']);
    $SSStatus = trim($_POST['SSStatus']);  
    $url = trim($_POST['url']);     
    $SSNote = trim($_POST['SSNote']); 
    
    mysqli_query($link, "UPDATE `servicesub` SET "
            . "`SSName` = '$SSName', "
            . "`BId` = '$BId', "
            . "`SGIdL2` = '$SGId', "
            . "`SEId` = '$SEId', "
            . "`SSDetail` = '$SSDetail', "
            . "`SSPrice` = '$SSPrice', "
            . "`SSStatus` = '$SSStatus', "
            . "`UpdateBy` = '$UpdateBy', "
            . "`UpdateDate` = '$UpdateDate', "
            . "`SSNote` = '$SSNote'"
            . "WHERE `SSId` = '$SSId'");
    
    // Find LV1 ---------------------------------------------------
    $result = mysqli_query($link, "SELECT `a`.`SGParentId`, `b`.`SGIdL2` FROM "
        . "`servicegroup` AS `a` "
        . "JOIN `servicesub` AS `b`"
        . "ON `a`.`SGId` = `b`.`SGIdL2` "
        . "WHERE `b`.`SGIdL2` = '$SGId'");

    $servicegroup = mysqli_fetch_array($result);
    
    mysqli_query($link, "UPDATE `servicesub` SET"
            . "`SGIdL1` = '$servicegroup[0]'"
            . "WHERE `SGIdL2` = '$servicegroup[1]'"
            . "AND `SSId` = '$SSId'");
    //-----------------------------------------------------------------
    
    // Find LV0 ---------------------------------------------------
    $result0 = mysqli_query($link, "SELECT `a`.`SGParentId`, `b`.`SGIdL1` FROM "
        . "`servicegroup` AS `a` "
        . "JOIN `servicesub` AS `b`"
        . "ON `a`.`SGId` = `b`.`SGIdL1` "
        . "WHERE `b`.`SGIdL1` = '$servicegroup[0]'");

    $servicegroup0 = mysqli_fetch_array($result0);
    
    mysqli_query($link, "UPDATE `servicesub` SET"
            . "`SGIdL0` = '$servicegroup0[0]'"
            . "WHERE `SGIdL1` = '$servicegroup0[1]'"
            . "AND `SSId` = '$SSId'");
    //-----------------------------------------------------------------
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=".$SId."&url=".$url."\">";  
    exit;    
}
//------รับค่ามาจากหน้าหลัก
if(isset($_GET['SSId'])){
    //เปิดของเก่ามา Edit
    $title = "แก้ไข/อัพเดทรายการ ".$_GET['SSId'];
    $action = 'edit';
    $SId = trim($_GET['SId']);
    $url = trim($_GET['url']);
    $SSId = trim($_GET['SSId']);
    
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` "
            . "WHERE `SSId` = '$SSId' AND `BId` = '$BId'");
    $ss = mysqli_fetch_array($result_servicesub);
    
    $SSName = $ss['SSName'];
    $SGId = $ss['SGIdL2'];
    $SGName = SGName($link, $ss['SGIdL2']);
    $SEId = $ss['SEId']; 
    $SSDetail = $ss['SSDetail'];
    $SSPrice = $ss['SSPrice'];
    $SSStatus = $ss['SSStatus'];  
    $SSNote = $ss['SSNote'];
}else{
    //สำหรับบันทึกใหม่
    $title = "เพิ่มรายการแจ้งซ่อม";
    $action = 'newsave';
    $SId = trim($_GET['SId']);    
    $url = trim($_GET['url']);
    $SSId = "";
    $SGId = "";
    $SSName = ""; 
    $SSPrice = "0";
    $SGName = "";
    $SSDetail="";
    $SSNote = "";
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_servicesub.php">
                <input type="hidden" name="SId" value="<?php echo $SId; ?>">
                <input type="hidden" name="SSId" value="<?php echo $SSId; ?>"> 
                <input type="hidden" name="url" value="<?php echo $url; ?>">
                <div class="form-group">
                    <label class="control-label col-sm-3">รายการแจ้งซ่อม </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="SSName" value="<?php echo $SSName; ?>" required autofocus>
                    </div>          
                </div>
              
                <div class="form-group">
                    <label class="control-label col-sm-3">ประเภทงาน </label>
                    <div class="col-sm-6">
                        <input type="hidden" id="SGId" name="SGId" value="<?php echo $SGId; ?>" >
                        <?php 
                        //-------แสดงประเกทของการซ่อมม----------------------------
                        $result_SGgroup = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGName` = '$SGName' ");
                        $SGgroup = mysqli_fetch_array($result_SGgroup);
                        $SGParentId = $SGgroup['SGParentId'];
                        $result_SGG = mysqli_query($link, "SELECT `SGName` FROM `servicegroup` WHERE `SGId` = '$SGParentId' ");
                        $SGG = mysqli_fetch_array($result_SGG);
                        //-------จบแสดงประเกทของการซ่อมม--------------------------
                       
                        //-------เคลียร์ช่องประเภทงาน--------------------------------
                        if($SGName == NULL){$echo = ""; $disabled = "";}
                        else {$echo = $SGG[0]."->".$SGName; $disabled = "disabled";}
                        //-------จบเคลียร์ช่องประเภทงาน------------------------------
                        ?>
                        <input type="text" class="form-control" id="SGName" placeholder="กด spacebar 2 ครั้ง"  value="<?php echo $echo; ?>" <?php echo $disabled; ?> >
                    </div>
                    <button class="btn btn-sm btn-warning" id="editsub" type="button"><i class="fa fa-pencil"></i></button>
                    
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">รายละเอียด </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" rows="3" name="SSDetail" placeholder="ระบบสาเหตุและวิธีแก้ไข"><?php echo $SSDetail; ?></textarea>  
                    </div>        
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">ผู้รับผิดชอบ </label>
                    <div class="col-sm-6">
                        <select class="form-control" name="SEId">
                            <?php
                            $result_serviceengineer = mysqli_query($link, "SELECT * FROM `serviceengineer` WHERE `SEStatus`='1'");
                            while ($se = mysqli_fetch_array($result_serviceengineer)){
                                if($se['SEId']==$SEId){
                                    $selected = "selected";
                                }else{
                                    $selected = "";
                                }
                                echo "\n<option value=\"".$se['SEId']."\" ".$selected.">".$se['SEName']."</option>";
                            }
                            ?>
                        </select>
                    </div>        
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">ช่างหน้างาน </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="SSNote" value="<?php echo $SSNote; ?>">
                    </div>  
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">ค่าซ่อม </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control text-right" name="SSPrice" value="<?php echo $SSPrice; ?>">
                    </div>  
                    <label class="control-label col-sm-1">บาท </label>
                </div>
                
                
                <div class="form-group">
                    <label class="control-label col-sm-3">สถานะงาน </label>
                    <div class="col-sm-3">
                        <select class="form-control" name="SSStatus">
                            <?php
                            $result_servicestatus = mysqli_query($link, "SELECT * FROM `servicestatus` WHERE `SSStatus`='1'");
                            while ($ss = mysqli_fetch_array($result_servicestatus)){
                                if($ss['SSId']==$SSStatus){
                                    $selected = "selected";
                                }else{
                                    $selected = "";
                                }
                                echo "\n<option value=\"".$ss['SSId']."\" ".$selected.">".$ss['SSName']."</option>";
                            }
                            ?>
                        </select>
                    </div>  
                </div>
                
                
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" id="button"  name="<?php echo $action; ?>">บันทึก</button>      
                  </div>
              </div>  
            </form>
           
        </div>

<script type="text/javascript">
    //ส่วนของการค้นหา
     $(function() { 
                $( "#SGName" ).autocomplete({
                    source: "search_servicegroup.php",
                    minLength: 2,      
                    select: function( event, ui ) {
                        $( "#SGId" ).val(ui.item.id);                        
                        $('#SGName').attr("disabled", "disabled");
                    }
                });
            });  
            
            
            /* ยกเลิกsubform //Clear form */
            $("#editsub").click(function() {
                $( "#SGId" ).val('');
                $( "#SGName" ).val('');
                $('#SGName').removeAttr('disabled');
            });
            
            
          
            
</script>