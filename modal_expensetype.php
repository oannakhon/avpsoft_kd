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
    $ETId = newid($link, 23);
    $ETName = $_POST['ETName'];
    
    mysqli_query($link, "INSERT INTO `expensetype` (`ETId`, `ETName`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) "
            . "VALUES ('$ETId','$ETName','$CreateBy','$CreateDate','$UpdateBy','$UpdateDate')");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=expensetype.php\">"; 
    exit;   
}

if(isset($_POST['edit'])){
    $ETId = $_POST['ETId'];
    $ETName = $_POST['ETName'];
    
    mysqli_query($link, "UPDATE `expensetype` SET "
            . "`ETName` = '$ETName' "
            . "WHERE `expensetype`.`ETId` = '$ETId'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=expensetype.php\">";
    exit;
   
}


if(isset($_GET['del'])){
    $ETId = trim($_GET['del']);
    
    mysqli_query($link, "UPDATE `expensetype` SET `ETStatus` = '0' WHERE `ETId` = '$ETId' ");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=expensetype.php\">";
    exit;   
}

    

if(isset($_GET['ETId'])){
    $type = "edit";//ปุ่ม
    //เปิดของเก่ามา Edit
    $title = "แก้ไขหมวดหมู่ ".$_GET['ETId'];
    
    $ETId = trim($_GET['ETId']);
    
    //ค้นจากใน table มาเพื่อรอให้ user แก้ไข
    $result_changemoney = mysqli_query($link, "SELECT * FROM `expensetype` WHERE `ETId` = '$ETId' ");    
    $cm = mysqli_fetch_array($result_changemoney);
    
    $ETId = $cm['ETId'];
    $ETName = $cm['ETName'];

}else{
    $title = "เพิ่มหมวดหมู่ค่าใช้จ่าย";
    $type = "newsave";
    $ETId = "";
    $ETName = "";
}


?>
  <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal" >&times;</button>
          <h4 class="modal-title"><?php echo $title; ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_expensetype.php">
                <input type="hidden" name="ETId" value="<?php echo $ETId; ?>">
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อหมวดหมู่  </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="ETName" value="<?php echo $ETName; ?>">
                </div>        
            </div>        
            
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-lg-4" id="save" name="<?php echo $type; ?>">บันทึก</button>   
                  </div>                     

              </div>  
            </form>            
        </div>
     

 <script type="text/javascript">

        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        
        </script>