<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");


if(isset($_GET['RPId'])){   
   foreach($_POST as $key => $value){
    mysqli_query($link, "UPDATE `receiveproductsubsn` "
            . "SET `RPSubSNName` = '$value' "
            . "WHERE `RPSubSNId` = '$key'");    
   }
   echo "<meta http-equiv=refresh CONTENT=\"0; URL=managereceiveproduct.php?RPId=".$_GET['RPId']."\">"; 
   exit;
}

$RPSubId = $_GET['RPSubId'];
$result_receiveproductsub = mysqli_query($link,"SELECT * FROM `receiveproductsub` WHERE `RPSubId` = '$RPSubId'");
$rps = mysqli_fetch_array($result_receiveproductsub);

?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">จัดการเลขเครื่อง(SN) <?php echo PName($link, $rps['PId']); ?></h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_receiveproductsubsn.php?RPId=<?php echo $rps['RPId']; ?>">
             
              
              <?php
              $result_receiveproductsubsn = mysqli_query($link,"SELECT * FROM `receiveproductsubsn` "
                      . "WHERE `RPSubId`='$RPSubId' ");
              while($rpssn = mysqli_fetch_array($result_receiveproductsubsn)){
              ?>
              
              <div class="form-group">
                <label class="control-label col-sm-3">SN 1 </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="<?php echo $rpssn['RPSubSNId']; ?>" value="<?php echo $rpssn['RPSubSNName']; ?>">
                </div>      
              </div>
              <?php } ?>  
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4">บันทึก</button>      
                  </div>                     

              </div>  
            </form>            
        </div>