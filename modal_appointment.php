<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");


if(isset($_GET['success'])){ //กดปุ่มนัดหมายในแจ้งซ่อม
    $id = $_GET['success'];
    mysqli_query($link, "UPDATE `appointment` SET"
            . "`APStatus` = '2' "
            . "WHERE `id` = '$id'");
    
    
    
    if(!isset($_GET['SId'])){
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=index.php\">";//กดจากหน้า index
    }else{
        $SId = $_GET['SId'] ;
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=$SId\">";//กดจากหน้าแจ้งซ่อม
    }
    
    exit;
}

if(isset($_GET['del'])){
    $id = $_GET['del'];
    $SId = $_GET['SId'];
    mysqli_query($link, "DELETE FROM `appointment` WHERE `appointment`.`id` = '$id'");
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=$SId\">";
    exit;
} 

if(isset($_POST['save-appo'])){
    $Date = $_POST['APDate'];
    $APDate = datemysql543($Date);
    $ParNo = $_POST['ParNo'];
    $APDetail = $_POST['APDetail'];
    $SId = $_POST['SId'];
    $BId = $_SESSION['BId'];
    
    mysqli_query($link, "INSERT INTO `appointment` (`BId`, `ParNo`, `APDate` , `APDetail`, `CreateBy`, `CreateDate`)"
            . "VALUES ('$BId','$ParNo','$APDate','$APDetail','$CreateBy','$CreateDate')");
    
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=$SId\">";
    exit;
}else{
    $ParNo = $_GET['ParNo'];
    $SId = $_GET['SId'];
}
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo "ตารางนัดหมายแปลงที่ ".$ParNo; ?></h4>
        </div>          
        <div class="modal-body">  
            <form method="POST" action="modal_appointment.php?SId=<?php echo $SId; ?>" > 
                       <table class="table table-bordered table-hover table-responsive" style="font-family: tahoma">
                                              <thead>
                                                <tr>
                                                  <th class="text-center" width="20%">วันที่</th>
                                                  <th class="text-center">รายการ</th>
                                                  <th class="text-center" width="15%">กระทำ</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                  <?php
                                                  $result_appointment = mysqli_query($link, "SELECT * FROM `appointment` "
                                                          . "WHERE `BId` = '$_SESSION[BId]' "
                                                          . "AND `ParNo` = '$ParNo'"
                                                          . "AND `APStatus` = 1");
                                                  while ($appointment = mysqli_fetch_array($result_appointment)){
                                                  ?>
                                                  <tr>
                                                      <td>
                                                          <span><?php echo viewdate($appointment['APDate']); ?></span>
                                                      </td>
                                                      <td>
                                                          <span><?php echo $appointment['APDetail']; ?></span>
                                                      </td>
                                                      <td>
                                                          <a href="modal_appointment.php?del=<?php echo $appointment['id']; ?>&SId=<?php echo $SId; ?>" class="btn btn-xs btn-danger" onclick="return confirm('ยืนยันลบรายการนี้?')"><i class="fa fa-trash-o"></i></a>
                                                      <a href="modal_appointment.php?success=<?php echo $appointment['id']; ?>&SId=<?php echo $SId; ?>" class="btn btn-xs btn-success" data-toggle="modal" data-target="#hrefModal"><i class="fa fa-check"></i></a>
                                                      </td>
                                                  </tr>
                                                  <?php } ?>
                                              
                                                  <tr>
                                                     
                                                      <td>
                                                          <input type="hidden" name="SId" value="<?php echo $SId; ?>">
                                                          <input type="hidden" name="ParNo" value="<?php echo $ParNo; ?>">
                                                          <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="APDate" required>
                                                      </td>
                                                      <td>
                                                          <input type="text" class="form-control" name="APDetail" required>  
                                                      </td>
                                                      <td>
                                                          <button type="submit" class="btn btn-primary" name="save-appo"  value="save-appo">save</button> 
                                                      </td>
                                                      
                                                  </tr>
                                              
                                                  </tbody>
                                                </table>
                                             </form>           
           
                
                
            
        </div>
<script type="text/javascript">
            $(function () {            
                $('.dateadd').datetimepicker({
                    format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
                });            
            });
            
             


</script>