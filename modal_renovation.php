<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

$ParAddress = $_GET['ParAddress'];
$ParNo = ParNobyParAddress($link, $BId, $ParAddress);

$ParDaytransferowner = ParDaytransferowner($link, $BId, $ParNo);
$now = strtotime(date('Y-m-d'));
$numday = ($now - strtotime($ParDaytransferowner))/(60*60*24);
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">การต่อเติม บ้านเลขที่ <?php echo $ParAddress; ?> โอน <?php echo viewdate($ParDaytransferowner); ?></h4>
        </div>          
        <div class="modal-body">            
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      
                      <th>รายการ</th>
                      <th width="20%">เงินประกัน</th>
                      <th width="20%">สถานะ</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php
                     $result = mysqli_query($link, "SELECT * FROM `renovation` WHERE `BId` = '$_SESSION[BId]' AND `ParNo` = '$ParNo' AND `RVStatus` !='0' ");
                     while ($renovation = mysqli_fetch_array($result)){
                     ?>
                    <tr>                      
                      <td><?php
                      echo viewdate($renovation['RVDate'])." ";
                      $sub = mysqli_query($link, "SELECT * FROM `renovationsub` WHERE `RVId` = '$renovation[RVId]' AND `RVSubStatus`='1'");
                      while ($resub = mysqli_fetch_array($sub)){
                          echo $resub['RVSubDetail'].", ";
                      }
                      ?></td>
                      <td class="text-right"><?php echo number_format($renovation['RVDeposit'],2); ?></td>
                      <td><?php 
                      if($renovation['RVStatus']==1){
                          echo "กำลังต่อเติม";
                      }else{
                          echo "คืนเงินประกันแล้ว";
                      }
                          ?></td>
                       
                    </tr>
            
                     <?php } ?>
                  </tbody>
                </table>
            
        </div>