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
          <h4 class="modal-title">รายการค้างชำระ บ้านเลขที่ <?php echo $ParAddress; ?> โอน <?php echo viewdate($ParDaytransferowner); ?></h4>
        </div>          
        <div class="modal-body">            
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      
                      <th>รายการ</th>
                      <th width="20%">จำนวนเงิน</th>
                      <th width="20%">ชำระแล้ว</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php
                     $result = mysqli_query($link, "SELECT * FROM `debt` WHERE `BId` = '$_SESSION[BId]' AND `ParNo` = '$ParNo' AND `DebStatus` IN (1,2) ");
                     while ($debt = mysqli_fetch_array($result)){
                         if($debt['DebStatus']==2){
                             $result_receipt = mysqli_query($link, "SELECT SUM(RecGrandtotal) FROM `receipt` "
                                     . "WHERE `RefId` = '$debt[DebId]' AND `BId` = '$BId' AND `RecStatus` = '1'");
                             $re = mysqli_fetch_array($result_receipt);
                             $paid = $re[0];
                         }else{
                             $paid = 0;
                         }
                     ?>
                    <tr>                      
                      <td><?php
                      echo $debt['DebName']." กำหนดชำระ ".viewdate($debt['DueDate']);
                     
                      ?></td>
                      <td class="text-right"><?php echo number_format($debt['DebTotal'],2); ?></td>
                      <td class="text-right"><?php echo number_format($paid,2); ?></td>
                       
                    </tr>
            
                     <?php } ?>
                  </tbody>
                </table>
            
        </div>