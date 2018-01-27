<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

if(isset($_GET['RecId'])){
    $RecId = trim($_GET['RecId']);
    $RefId = trim($_GET['RefId']);
    
     mysqli_query($link, "UPDATE `receipt` SET `RecStatus`='0', `UpdateBy`='$UpdateBy', `UpdateDate`='$UpdateDate' "
            . "WHERE `BId` = '$BId' AND `RecId` = '$RecId'");
   
     
     //ปรับสถานะใน debt
     $result_receipt = mysqli_query($link, "SELECT SUM(RecGrandtotal) FROM `receipt` "
              . "WHERE `RefId` = '$RefId' AND `BId` = '$_SESSION[BId]' AND `RecStatus` = '1'");
     if(mysqli_num_rows($result_receipt)>0){
          $receipt_sum  = mysqli_fetch_array($result_receipt);
          if($receipt_sum[0]==0){
            mysqli_query($link, "UPDATE `debt` SET `DebStatus` ='1' WHERE `DebId` = '$RefId' AND `BId` = '$BId'");
          }else{
            mysqli_query($link, "UPDATE `debt` SET `DebStatus` ='2' WHERE `DebId` = '$RefId' AND `BId` = '$BId'"); 
          }
     }
     
     
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receipt.php\">"; 
    exit;
    
    
}else{
    //location to 
}


?>
