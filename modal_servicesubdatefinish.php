<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

//---ซ่อมเสร็จแล้ว SS-03
if(isset($_POST['finish'])){
    $id = $_POST['id'];
    $SSId = trim($_POST['SSId']);
    $SId = trim($_POST['SId']); 
    $SSStatus = trim($_POST['SSStatus']); 
    $SSDateFinish = datemysql543($_POST['SSDateFinish']);
    mysqli_query($link, "UPDATE `servicesub` SET "
            . "`SSStatus` = '$SSStatus', "
            . "`SSDateFinish` = '$SSDateFinish'"
            . "WHERE `id` = '$id' ");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=".$SId."\">"; 
    exit; 
}


//------รับค่ามาจากหน้าหลัก
if(isset($_GET['SSId'])){
    //เปิดของเก่ามา Edit
    $title = "เปลี่ยนสถานะรายการ ".$_GET['SSId'];
    $SId = trim($_GET['SId']);
    $SSId = trim($_GET['SSId']);
    $id = $_GET['id'];
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` "
            . "WHERE `SSId` = '$SSId' AND `BId` = '$BId'");
    $ss = mysqli_fetch_array($result_servicesub);    
    $SSName = $ss['SSName'];
    $SSStatus = "SS-03";
    $SSDateFinish = viewdate543($ss['SSDateFinish']);
    
    if($SSDateFinish=="00/00/0000"){$SSDateFinish = date('Y-m-d');}
}

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_servicesubdatefinish.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="SId" value="<?php echo $SId; ?>">
                <input type="hidden" name="SSId" value="<?php echo $SSId; ?>"> 
                <div class="form-group">
                    <label class="control-label col-sm-3">รายการแจ้งซ่อม </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="SSName" value="<?php echo $SSName; ?>" readonly>
                    </div>          
                </div>
              

                
                
                <div class="form-group">
                    <label class="control-label col-sm-3">เปลี่ยนสถานะงานเป็น </label>
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
                    <label class="control-label col-sm-3">วันที่ซ่อมเสร็จ</label>
                    <div class="col-sm-6">
                        <div class='input-group' >
                            <input type='text' id="datepicker2" name="SSDateFinish" class="form-control datepicker2" data-date-format="dd/mm/yyyy" data-date=""  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                    </div>          
                </div>
                
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-12" name="finish">อัพเดทสถานะ</button>      
                  </div>
                      

              </div>  
            </form>
            
        </div>

<script type="text/javascript">
        
        $(document).ready(function () {
            var myDate2 = $("#datepicker2").data("date");
            $('.datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate2);  //กำหนดเป็นวันปัจุบัน
            
        });

</script>