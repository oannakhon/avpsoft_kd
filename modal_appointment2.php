<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");




if(isset($_POST['newappo'])){
    $Date = $_POST['APDate'];
    $APDate = datemysql543($Date);
    $ParNo = $_POST['ParNo'];
    $APDetail = $_POST['APDetail'];
    $BId = $_SESSION['BId'];
    $id = $_POST['id'];
    mysqli_query($link, "INSERT INTO `appointment` (`BId`, `ParNo`, `APDate` , `APDetail`, `CreateBy`, `CreateDate`)"
            . "VALUES ('$BId','$ParNo','$APDate','$APDetail','$CreateBy','$CreateDate')");
    
    mysqli_query($link, "UPDATE `appointment` SET "
            . "`APStatus` = '3' "
            . "WHERE `id` = '$id'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=index.php\">";
    exit;
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo "ตารางนัดหมายแปลงที่ ".$_GET['ParNo']; ?></h4>
        </div>          
        <div class="modal-body">  
            <form method="POST" action="modal_appointment2.php" >
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <input type="hidden" name="ParNo" value="<?php echo $_GET['ParNo']; ?>">
                <div class=" text-center">
                    <a href="modal_appointment.php?success=<?php echo $_GET['id']; ?>" onclick="return confirm('ยืนยันการนัดหมายเรียบร้อย?')" class="btn btn-success btn-lg" >พบลูกค้าแล้ว</a>
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-sm-4 text-right">
                        <label>เลื่อนนัด</label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="APDate" class="form-control" data-provide="datepicker" data-date-language="th-th" required >
                    </div>
                </div>
                
                <div class="row"></div>
                <br>
                <div class="form-group">
                    <div class="col-sm-4 text-right">
                        <label>ข้อความ</label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="APDetail" class="form-control" required >
                    </div>
                </div>
                
                <div class="row"></div>
                <br>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        <button type="submit" class="btn btn-info btn-lg" name="newappo">บันทึก</button>
                    </div>
                    
                </div>
                                             </form>           
           
                
            <br>
            
        </div>
<script type="text/javascript">
            $(function () {            
                $('.dateadd').datetimepicker({
                    format: 'DD/MM/YYYY',locale: 'th'
                });            
            });
            
            $(document).ready(function () {
            var myDate = $("#datepicker").data("date");
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
        });
            
             


</script>