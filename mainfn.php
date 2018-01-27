<?php
/* 
 * เก็บค่า Config Database
 * รวม function ทั้งหมด
 * เริ่มเขียนโปรแกรม 20 กรกฎาคม 2559
 */

//DB Server
define("db_host", "localhost");
define("db_username", "avpapp_web");
define("db_password", "ts2327");
define("db_name", "avpsoftw_kd");

date_default_timezone_set('Asia/Bangkok');

// Connect DB
$link = mysqli_connect(db_host, db_username, db_password) or die("Error " . mysqli_error($link));
mysqli_select_db($link, db_name);
mysqli_query($link, "SET NAMES utf8");


 function month_diff($link,$DateStart,$DateEnd){
          
          //-- 2017-01-01
          $Start = explode("-", $DateStart);
          $End = explode("-", $DateEnd);          
          
          return ($End[1]*1)-($Start[1]*1)+1;
      }

function facebook_time_ago($timestamp)  
 {  
      $time_ago = strtotime($timestamp);  
      $current_time = time();  
      $time_difference = $current_time - $time_ago;  
      $seconds = $time_difference;  
      $minutes      = round($seconds / 60 );           // value 60 is seconds  
      $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
      $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
      $weeks          = round($seconds / 604800);          // 7*24*60*60;  
      $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
      $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  
      if($seconds <= 60)  
      {  
     return "เมื่อสักครู่";  
   }  
      else if($minutes <=60)  
      {  
     if($minutes==1)  
           {  
       return "1 นาทีที่แล้ว";  
     }  
     else  
           {  
       return "$minutes นาทีที่แล้ว";  
     }  
   }  
      else if($hours <=24)  
      {  
     if($hours==1)  
           {  
       return "1 ชั่วโมงที่แล้ว";  
     }  
           else  
           {  
       return "$hours ชั่วโมงที่แล้ว";  
     }  
   }  
      else if($days <= 7)  
      {  
     if($days==1)  
           {  
       return "เมื่อวานนี้";  
     }  
           else  
           {  
       return "$days วันที่แล้ว";  
     }  
   }  
      else if($weeks <= 4.3) //4.3 == 52/12  
      {  
     if($weeks==1)  
           {  
       return "1 สัปดาห์ที่แล้ว";  
     }  
           else  
           {  
       return "$weeks สัปดาห์ที่แล้ว";  
     }  
   }  
       else if($months <=12)  
      {  
     if($months==1)  
           {  
       return "1 เดือนที่แล้ว";  
     }  
           else  
           {  
       return "$months เดือนที่แล้ว";  
     }  
   }  
      else  
      {  
     if($years==1)  
           {  
       return "1 ปีที่แล้ว";  
     }  
           else  
           {  
       return "$years ปีที่แล้ว";  
     }  
   }  
 }  

function  notif($link,$UserId,$NoText,$NoLink,$Icon){
    $nowDate = date('Y-m-d H:i:s');
    mysqli_query($link, "INSERT INTO `notification` (`UserId`, `NoDate`, `NoText`, `NoLink`, `NoIcon`, `NoStatus`)"
            . "VALUES ('$UserId', '$nowDate', '$NoText', '$NoLink', '$Icon', '1')");
     
 }


function format_phone($phone)
{
	$phone = preg_replace("/[^0-9]/", "", $phone);
 
	if(strlen($phone) == 10)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
		return $phone;
}

function RandomId(){    
        $letters='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string='';
        for($x=0; $x<10; ++$x){
            $string.=$letters[rand(0,25)];
        }
        return $string;
}

//---Function for login
function log_login($link,$email,$pass,$successful,$note,$computerkey,$browser){   
    $result_browser = mysqli_query($link, "SELECT `id` FROM `ad_browser` WHERE `BrowserName` = '$browser'");
    if(mysqli_num_rows($result_browser)==0){
        mysqli_query($link, "INSERT INTO `ad_browser` (`BrowserName`) VALUES ('$browser')");
    }
    $result_browser = mysqli_query($link, "SELECT `id` FROM `ad_browser` WHERE `BrowserName` = '$browser'");
    $browserid = mysqli_fetch_array($result_browser);
    
    
    $ip = clientip();
    $tureip = explode(",", $ip);
    $ip = $tureip[0];
    $datelog = date("Y-m-d H:i:s");    
    mysqli_query($link, "INSERT INTO `log_login` (`ip`, `username`, `password`, `date`, `successful`, `note`,`computerkey`,`browser`) "
            . "VALUE ('$ip','$email', '$pass', '$datelog', '$successful', '$note','$computerkey','$browserid[0]') ");
}


function clientip(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function DRIdtoDRFileName($link,$DRId){
    $result = mysqli_query($link, "SELECT `DRFileName` FROM `dr` "
            . "WHERE `DRId` = '$DRId'");
    if(mysqli_num_rows($result)!=0){
        $sg = mysqli_fetch_array($result);
        return $sg[0];
    }else{
        return null;
    } 
}

function SGParentId($link,$SGId){
    $result = mysqli_query($link, "SELECT `SGParentId` FROM `servicegroup` "
            . "WHERE `SGId` = '$SGId'");
    if(mysqli_num_rows($result)!=0){
        $sg = mysqli_fetch_array($result);
        return $sg[0];
    }else{
        return null;
    } 
}


function newVolNo($link,$BId,$type){
    $result = mysqli_query($link, "SELECT `RVId` FROM `receiptvoltype` WHERE `BId` = '$BId' AND `RVType` = '$type'");
    $receiptvoltype = mysqli_fetch_array($result);    
    $RVId = $receiptvoltype[0];
    
    $result_receiptvol = mysqli_query($link, "SELECT * FROM `receiptvol` WHERE `RVId` = '$RVId'");
    $receiptvol = mysqli_fetch_array($result_receiptvol);
    
    if($receiptvol['RVNoNow'] < $receiptvol['RVIDMax']){
        //เลขที่เอกสาร +1
        $newRVNoNow = $receiptvol['RVNoNow']+1;
        mysqli_query($link, "UPDATE `receiptvol` SET "
                . "`RVNoNow` = '$newRVNoNow' "
                . "WHERE `RVId` = '$RVId' ");
        $VolNo_arr = array($receiptvol['RVBook'],$newRVNoNow);
        
    }else{
        //สร้างเล่มใหม่
        $newRVId = newid($link, 41);
        $RVBook = $receiptvol['RVBook']+1;
        $newRVNoNow = 1;
        $ComId = BIdToComId($link, $BId);
        
        mysqli_query($link, "INSERT INTO `receiptvol` (`RVId`, `RVBook`, `RVIdNext`, `RVNoNow`, `RVIDMax`, `BId`, `ComId`) "
                . "VALUES ('$newRVId', '$RVBook', '0', '$newRVNoNow', '999', '$BId', '$ComId')");
        //UPDATE ของเก่า RVIdNext
        mysqli_query($link, "UPDATE `receiptvol` SET "
                . "`RVIdNext` = '$newRVId' "
                . "WHERE `RVId` = '$RVId' ");
        //ตามไปอัพเดท ผูกเล่ม ในตาราง receiptvoltype
        mysqli_query($link, "UPDATE `receiptvoltype` SET "
                . "`RVId` = '$newRVId' "
                . "WHERE `BId` = '$BId' AND `RVType` = '$type'");        
        $VolNo_arr = array($RVBook,1);        
    }
    return $VolNo_arr;
    
}


function BIdToComId($link,$BId){
    $result_company = mysqli_query($link, "SELECT `ComId` FROM `branch` "
            . "WHERE `BId` = '$BId'");
    $ComId = mysqli_fetch_array($result_company);
    return $ComId[0];
}


function BName($link,$BId){
    $result_branch = mysqli_query($link, "SELECT `BName` FROM `branch` "
            . "WHERE `BId` = '$BId'");
    $branch = mysqli_fetch_array($result_branch);
    return $branch[0];
}

function BrandName($link,$BrandId){
    $result_brand = mysqli_query($link, "SELECT `BrandNameTH` FROM `brand` "
            . "WHERE `BrandId` = '$BrandId'");
    $brand= mysqli_fetch_array($result_brand);
    return $brand[0];
}

function PGName($link,$PGId){
    $result_productgroup = mysqli_query($link, "SELECT `PGName` FROM `productgroup` "
            . "WHERE `PGId` = '$PGId'");
    $productgroup= mysqli_fetch_array($result_productgroup);
    return $productgroup[0];
}

function PName($link,$PId){
    $result_product = mysqli_query($link, "SELECT `PName` FROM `product` "
            . "WHERE `PId` = '$PId'");
    $product= mysqli_fetch_array($result_product);
    return $product[0];
}

function WName($link,$WId){
    $result_warehouse = mysqli_query($link, "SELECT `WName` FROM `warehouse` "
            . "WHERE `WId` = '$WId'");
    $warehouse= mysqli_fetch_array($result_warehouse);
    return $warehouse[0];
}

function SuppName($link,$SuppId){
    $result_supplier = mysqli_query($link, "SELECT `SuppName` FROM `supplier` "
            . "WHERE `SuppId` = '$SuppId'");
    $supplier= mysqli_fetch_array($result_supplier);
    return $supplier[0];
}

function updatestock($link, $PId, $WId, $num){
    $result_stock = mysqli_query($link, "SELECT * FROM `stock` "
            . "WHERE `PId` = '$PId' AND `WId` = '$WId'");
    if(mysqli_num_rows($result_stock)==0){
        //Add new
        mysqli_query($link,"INSERT INTO `stock` (`PId`, `WId`, `STNum`) VALUES ('$PId','$WId','$num')");
    }else{
        //Update
        mysqli_query($link,"UPDATE `stock` SET `STNum`=`STNum`+'$num' "
                . "WHERE `PId` = '$PId' "
                . "AND `WId` = '$WId'");
    }    
}

function chkfullsn($link,$RPId){
    $result_receiveproductsubsn = mysqli_query($link,"SELECT `id` FROM `receiveproductsubsn` "
            . "WHERE `RPSubSNName` = '' AND `RPSubSNStatus` = '1'");
    if(mysqli_num_rows($result_receiveproductsubsn)==0){
        return 1;
    }else{
        return 0;
    }
}

function MAXDoc($link,$table,$field){
   $result = mysqli_query($link,"SELECT MAX($field) FROM $table");   
   if(mysqli_num_rows($result)==1){
       $doc = mysqli_fetch_array($result);
       return $doc[0];
   }else{
       return null;
   }
}

function MINDoc($link,$table,$field){
   $result = mysqli_query($link,"SELECT MIN($field) FROM $table");   
   if(mysqli_num_rows($result)==1){
       $doc = mysqli_fetch_array($result);
       return $doc[0];
   }else{
       return null;
   }
}

function ParNobyParAddress($link,$BId,$ParAddress){
    $result = mysqli_query($link,"SELECT `ParNo` FROM `parcel` WHERE `BId` = '$BId' AND `ParAddress` = '$ParAddress'");
    if(mysqli_num_rows($result)==1){
        $parcel = mysqli_fetch_array($result);
        return $parcel[0]; 
    }else{
        return null;
    }
}


function ParAddressbyParNo($link,$BId,$ParNo){
    $result = mysqli_query($link,"SELECT `ParAddress` FROM `parcel` WHERE `BId` = '$BId' AND `ParNo` = '$ParNo'");
    if(mysqli_num_rows($result)==1){
        $parcel = mysqli_fetch_array($result);
        return $parcel[0]; 
    }else{
        return null;
    }
}

function ParArea($link,$BId,$ParNo){
    $result = mysqli_query($link, "SELECT `ParArea` FROM `parcel` WHERE `BId` = '$BId' AND `ParNo` = '$ParNo'");
    if(mysqli_num_rows($result)==1){
        $parcel = mysqli_fetch_array($result);
        return $parcel[0]; 
    }else{
        return null;
    }
}

function ParDaytransferowner($link,$BId,$ParNo){
    $result = mysqli_query($link, "SELECT `ParDaytransferowner` FROM `parcel` WHERE `BId` = '$BId' AND `ParNo` = '$ParNo'");
    if(mysqli_num_rows($result)==1){
       $doc = mysqli_fetch_array($result);
       return $doc[0];
   }else{
       return null;
   }
}

function STName($link,$STId){
    $result = mysqli_query($link, "SELECT `STName` FROM `servicetype` WHERE `STId` = '$STId'");
    if(mysqli_num_rows($result)==1){
       $doc = mysqli_fetch_array($result);
       return $doc[0];
   }else{
       return null;
   }
}

function SEName($link,$SEId){
    $result = mysqli_query($link, "SELECT `SEName` FROM `serviceengineer` WHERE `SEId` = '$SEId'");
    if(mysqli_num_rows($result)==1){
       $doc = mysqli_fetch_array($result);
       return $doc[0];
   }else{
       return null;
   }
}

function HPName($link,$HPId){
    $result = mysqli_query($link, "SELECT `HPName` FROM `homeplan` WHERE `HPId` = '$HPId' AND `HPStatus` ='1'");
    if(mysqli_num_rows($result)){
        $hp = mysqli_fetch_array($result);
        return $hp[0];
    }else{
        return null;
    }
}

function LastCAId($link,$BId,$ParNo){
    //customeraftersale
    $result = mysqli_query($link, "SELECT `CAId` FROM `customeraftersale` WHERE `BId` = '$BId' AND `ParNo` = '$ParNo' ORDER BY `id` DESC");
    if(mysqli_num_rows($result)!=0){
        $ca = mysqli_fetch_array($result);
        return $ca[0];
    }else{
        return null;
    }    
}

function LastCAId1($link,$BId,$ParNo){
    //customeraftersale
    $result = mysqli_query($link, "SELECT `CAId` FROM `customeraftersale` "
            . "WHERE `BId` = '$BId' "
            . "AND `ParNo` = '$ParNo' AND `CAStatus` = '1' "
            . "ORDER BY `id` DESC");
    if(mysqli_num_rows($result)!=0){
        $ca = mysqli_fetch_array($result);
        return $ca[0];
    }else{
        return null;
    }    
}

function CAName($link,$BId,$CAId){
    //customeraftersale
    $result = mysqli_query($link, "SELECT * FROM `customeraftersale` WHERE `BId` = '$BId' AND `CAId` = '$CAId'");
    if(mysqli_num_rows($result)!=0){
        $ca = mysqli_fetch_array($result);
        return $ca['CAPreName'].$ca['CAName']." ".$ca['CALastName'];
    }else{
        return null;
    }    
}

function CAAddress($link,$BId,$CAId){
    //customeraftersale
    $result = mysqli_query($link, "SELECT `CAAddress` FROM `customeraftersale` WHERE `BId` = '$BId' AND `CAId` = '$CAId'");
    if(mysqli_num_rows($result)!=0){
        $ca = mysqli_fetch_array($result);
        return $ca[0];
    }else{
        return null;
    }    
}

function DRName($link,$DRId){
    $result = mysqli_query($link, "SELECT `DRName` FROM `dr` WHERE `DRId` = '$DRId'");
    if(mysqli_num_rows($result)!=0){
        $r = mysqli_fetch_array($result);
        return $r[0];
    }else{
        return null;
    }    
}

function SCHTime($link,$SCHId){
    $result = mysqli_query($link, "SELECT `SCHTime` FROM `schedule` WHERE `SCHId` = '$SCHId'");
    if(mysqli_num_rows($result)!=0){
        $r = mysqli_fetch_array($result);
        return $r[0];
    }else{
        return null;
    }    
}

function PSName($link,$PSId){
    $result = mysqli_query($link, "SELECT `PSName` FROM `parcelstatus` WHERE `PSId` = '$PSId'");
    if(mysqli_num_rows($result)!=0){
        $r = mysqli_fetch_array($result);
        return $r[0];
    }else{
        return null;
    }    
}


//function convert number to text
function bahttext($number){
        $t1 = array("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $t2 = array("เอ็ด", "ยี่", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
        $zerobahtshow = 0; // ในกรณีที่มีแต่จำนวนสตางค์ เช่น 0.25 หรือ .75 จะให้แสดงคำว่า ศูนย์บาท หรือไม่ 0 = ไม่แสดง, 1 = แสดง
        (string) $number;
        $number = explode(".", $number);
        if(!empty($number[1])){
            if(strlen($number[1]) == 1){
                $number[1] .= "0";
            }elseif(strlen($number[1]) > 2){
                if($number[1]{2} < 5){
                    $number[1] = substr($number[1], 0, 2);
                }else{
                    $number[1] = $number[1]{0}.($number[1]{1}+1);
                }
            }
        }
        for($i=0; $i<count($number); $i++){
            $countnum[$i] = strlen($number[$i]);
            if($countnum[$i] <= 7){
                $var[$i][] = $number[$i];
            }else{
                $loopround = ceil($countnum[$i]/6);
                for($j=1; $j<=$loopround; $j++){
                    if($j == 1){
                        $slen = 0;
                        $elen = $countnum[$i]-(($loopround-1)*6);
                    }else{
                        $slen = $countnum[$i]-((($loopround+1)-$j)*6);
                        $elen = 6;
                    }
                    $var[$i][] = substr($number[$i], $slen, $elen);
                }
            }
            $nstring[$i] = "";
            for($k=0; $k<count($var[$i]); $k++){
                if($k > 0){ 
                    $nstring[$i] .= $t2[7];
                }
                $val = $var[$i][$k];
                $tnstring = "";
                $countval = strlen($val);
                for($l=7; $l>=2; $l--){
                    if($countval >= $l){
                        $v = substr($val, -$l, 1);
                        if($v > 0){
                            if($l == 2 && $v == 1){
                                $tnstring .= $t2[($l)];
                            }elseif($l == 2 && $v == 2){
                                $tnstring .= $t2[1].$t2[($l)];
                            }else{
                                $tnstring .= $t1[$v].$t2[($l)];
                            }
                        }
                     }
                }
                if($countval >= 1){
                    $v = substr($val, -1, 1);
                    if($v > 0){
                        if($v == 1 && $countval > 1 && substr($val, -2, 1) > 0){
                            $tnstring .= $t2[0];
                        }else{
                            $tnstring .= $t1[$v];
                        }
                    }
                }
                $nstring[$i] .= $tnstring;
            }
        }
        $rstring = "";
        if(!empty($nstring[0]) || $zerobahtshow == 1 || empty($nstring[1])){
            if($nstring[0] == "") {
                $nstring[0] = $t1[0];
            }
            $rstring .= $nstring[0]."บาท";
        }
        if(count($number) == 1 || empty($nstring[1])){
            $rstring .= "ถ้วน";
         }else{
            $rstring .= $nstring[1]."สตางค์";
         }
        return $rstring;
    }
   
function ComNameTH($link,$ComId){
    $result_company = mysqli_query($link, "SELECT `ComNameTH` FROM `company` "
            . "WHERE `ComId` = '$ComId'");
    $company= mysqli_fetch_array($result_company);
    return $company[0];
}    

function chkdupp($link,$table,$field,$value){
    $result = mysqli_query($link, "SELECT * FROM $table WHERE $field = '$value'");
    if(mysqli_num_rows($result)==0){
        return 0; //กรณีไม่ซ้ำ
    }else{
        return 1; //กรณีซ้ำ
    }
}

function SGName($link,$SGId){
    $result = mysqli_query($link, "SELECT `SGName` FROM `servicegroup` "
            . "WHERE `SGId` = '$SGId'");
    if(mysqli_num_rows($result)!=0){
        $sg = mysqli_fetch_array($result);
        return $sg[0];
    }else{
        return null;
    } 
}

///----------------NOT USE-------------------
function CValue($link,$CName){
    //แสดงค่าคอนฟิก อ่านจาก table config
    $result = mysqli_query($link, "SELECT `CValue` FROM `config` WHERE `CName` = '$CName'");
    if(mysqli_num_rows($result)==1){
        $config = mysqli_fetch_array($result);
        return $config[0];
    }else{
        return null;
    }
}

function CBValue($link,$BId,$CName){
    //แสดงค่าคอนฟิก อ่านจาก table config
    $result = mysqli_query($link, "SELECT `CValue` FROM `configforbranch` WHERE `CName` = '$CName' AND `BId` = '$BId'");
    if(mysqli_num_rows($result)==1){
        $config = mysqli_fetch_array($result);
        return $config[0];
    }else{
        return null;
    }
}

function colorstatus($link,$RSId){
    $result_color = mysqli_query($link, "SELECT `RSColor` FROM `roomstatus` WHERE `RSId` = '$RSId'");
    $color = mysqli_fetch_array($result_color);
    return "#".$color[0];
}

function statusname($link,$RSId){
    $result_sn = mysqli_query($link, "SELECT `RSName` FROM `roomstatus` WHERE `RSId` = '$RSId'");
    $roomstatus = mysqli_fetch_array($result_sn);
    return $roomstatus[0];
}

function checklogin(){
    if(!isset($_SESSION['UserId'])){
        header('Location: login.php');        
        exit;
    }
}

function checkenablews($link){
    $result_transactionworkshifts = mysqli_query($link,"SELECT `TWId` FROM `transactionworkshifts` WHERE `TWStatus` = '1' ORDER BY `id` DESC LIMIT 1");
    if(mysqli_num_rows($result_transactionworkshifts)==0) {
        echo "disabled";
    }
}

function viewdate($datetime){
    // เดือน/วัน/ปี
    if($datetime=="0000-00-00"){
        return "";
    }else{
    $str = explode(" ", $datetime);
    $date = explode("-", $str[0]);
    $newdate = $date[2]."/".$date[1]."/".($date[0]+543);
    return $newdate;
    }
}

function viewdatebe($date){
    // 01/01/2017 to 01/01/2560
    if($date==""){
        return "";
    }else{
      $date = explode("/", $date);
    $newdate = $date[0]."/".$date[1]."/".($date[2]+543);
    return $newdate;  
    }    
}


function viewdate543($datetime){    
    // เดือน/วัน/ปี
    $str = explode(" ", $datetime);
    $date = explode("-", $str[0]);
    $newdate = $date[2]."/".$date[1]."/".($date[0]);
    return $newdate;
}

function viewtime($datetime){    
    // HH:mm
    $str = explode(" ", $datetime);
    $time = explode(":", $str[1]);
    $newtime = $time[0].":".$time[1];
    return $newtime;
}

function viewdatetime($datetime){    
    // เดือน/วัน/ปี
    $str = explode(" ", $datetime);
    $date = explode("-", $str[0]);
    $newdate = $date[2]."/".$date[1]."/".($date[0]+543);
    $time = explode(":", $str[1]);
    $newtime = $time[0].":".$time[1];
    $new = $newdate." ".$newtime;
    return $new;
}

function datetimemysql($date,$time){
    $str = explode("/", $date);
    $datetime = $str[2]."-".$str[1]."-".$str[0]." ".$time.":00";
    return $datetime;
}

function datemysql($date){
    //Check ค่าว่าง
    if($date!=""){
        $str = explode("/", $date);
        $newdate = $str[2]."-".$str[1]."-".$str[0];
        return $newdate;
    }else{ return null; }
}

function datemysql543($date){
    //Check ค่าว่าง
    if($date!=""){
        $str = explode("/", $date);
        $newdate = ($str[2]-543)."-".$str[1]."-".$str[0];
        return $newdate;
    }else{ return null; }
}

function thaishotmonth($timeold){
	// 2013-01-10 to 10 มกราคม 2556
	$time=strtotime($timeold);
	$thai_month_arr=array(
	"0"=>"",
	"1"=>"ม.ค.",
	"2"=>"ก.พ.",
	"3"=>"มี.ค.",
	"4"=>"เม.ย.",
	"5"=>"พ.ค.",
	"6"=>"มิ.ย.",	
	"7"=>"ก.ค.",
	"8"=>"ส.ค.",
	"9"=>"ก.ย.",
	"10"=>"ต.ค.",
	"11"=>"พ.ย.",
	"12"=>"ธ.ค."					
	);
	$thai_date_return=$thai_month_arr[date("n",$time)];
	if($timeold=="0000-00-00") $thai_date_return = "ยังไม่ได้ลงวันที่";
	return $thai_date_return;
}

function thaimonth($timeold){
	// 2013-01-10 to 10 มกราคม 2556
	$time=strtotime($timeold);
	$thai_month_arr=array(
	"0"=>"",
	"1"=>"มกราคม",
	"2"=>"กุมภาพันธ์",
	"3"=>"มีนาคม",
	"4"=>"เมษายน",
	"5"=>"พฤษภาคม",
	"6"=>"มิถุนายน",	
	"7"=>"กรกฎาคม",
	"8"=>"สิงหาคม",
	"9"=>"กันยายน",
	"10"=>"ตุลาคม",
	"11"=>"พฤศจิกายน",
	"12"=>"ธันวาคม"					
	);
	$thai_date_return=$thai_month_arr[date("n",$time)];
	if($timeold=="0000-00-00") $thai_date_return = "ยังไม่ได้ลงวันที่";
	return $thai_date_return;
}


function thaifulldate($timeold){
	// 2013-01-10 to 10 มกราคม 2556
	$time=strtotime($timeold);
	$thai_month_arr=array(
	"0"=>"",
	"1"=>"มกราคม",
	"2"=>"กุมภาพันธ์",
	"3"=>"มีนาคม",
	"4"=>"เมษายน",
	"5"=>"พฤษภาคม",
	"6"=>"มิถุนายน",	
	"7"=>"กรกฎาคม",
	"8"=>"สิงหาคม",
	"9"=>"กันยายน",
	"10"=>"ตุลาคม",
	"11"=>"พฤศจิกายน",
	"12"=>"ธันวาคม"					
	);
	$thai_date_return= date("j",$time);
	$thai_date_return.=" ".$thai_month_arr[date("n",$time)];
	$thai_date_return.=	" ".(date("Y",$time)+543);
	if($timeold=="0000-00-00") $thai_date_return = "ยังไม่ได้ลงวันที่";
	return $thai_date_return;
}

function thaifulldate2($timeold){
	// 2013-01-10 to วันที่ 10 เดือน มกราคม พ.ศ. 2556
	$time=strtotime($timeold);
	$thai_month_arr=array(
	"0"=>"",
	"1"=>"มกราคม",
	"2"=>"กุมภาพันธ์",
	"3"=>"มีนาคม",
	"4"=>"เมษายน",
	"5"=>"พฤษภาคม",
	"6"=>"มิถุนายน",	
	"7"=>"กรกฎาคม",
	"8"=>"สิงหาคม",
	"9"=>"กันยายน",
	"10"=>"ตุลาคม",
	"11"=>"พฤศจิกายน",
	"12"=>"ธันวาคม"					
	);
	$thai_date_return="วันที่ ".date("j",$time);
	$thai_date_return.=" เดือน ".$thai_month_arr[date("n",$time)];
	$thai_date_return.=" พ.ศ. ".(date("Y",$time)+543);
	if($timeold=="0000-00-00") $thai_date_return = "ยังไม่ได้ลงวันที่";
	return $thai_date_return;
}

function shortdate($datetime){     
    $date = explode("-", $datetime);
    $newdate = $date[2]."/".$date[1]."/".$date[0];
    return $newdate;
}

function newid($link,$id){
    
    //1. เปิดตาราง prefix หาค่า table  ชื่อidใน tabel WHERE $id
    //2. เอาค่าไปเปิดในตารางเอกสารนั้นๆ หา last id where...
    //3. สร้าง newid แล้ว return ค่าดังกล่าว     
    //$docid = ประเภทเอกสาร    
    $result = mysqli_query($link,"SELECT * FROM `prefix` WHERE `id` LIKE '$id'");
    $pre = mysqli_fetch_array($result);
    $prefix = $pre['PrePrefix'];
    $num = $pre['PreNum'];
    $table = $pre['PreTable'];
    $doc_id = $pre['PreIdname'];
    
    $result = mysqli_query($link, "SELECT `$doc_id` FROM `$table` WHERE `$doc_id` LIKE '$prefix%' ORDER BY `id` DESC LIMIT 1");
    if(mysqli_num_rows($result)==0){
        $format = "%0".$num."d";        
        $newid = $prefix.@sprintf($format,1);
    }else{        
        $row  = mysqli_fetch_array($result);
        $old = $row[0];        
        $oldid = explode($prefix, $old);
        $format = "%0".$num."d";        
        $newid = $prefix.@sprintf($format,($oldid[1]+1));         
    }     
    
    return $newid;
    mysqli_close($link);
}

function newidBId($link,$id){
    
    //เหมือนกับ newid แต่ รันตาม BId
    
    $result = mysqli_query($link,"SELECT * FROM `prefix` WHERE `id` LIKE '$id'");
    $pre = mysqli_fetch_array($result);
    $prefix = $pre['PrePrefix'];
    $num = $pre['PreNum'];
    $table = $pre['PreTable'];
    $doc_id = $pre['PreIdname'];
    $BId = $_SESSION['BId'];
   
    $result = mysqli_query($link, "SELECT `$doc_id` FROM `$table` "
            . "WHERE `BId` = '$BId' "
            . "AND `$doc_id` LIKE '$prefix%' ORDER BY `$doc_id` DESC LIMIT 1");
    if(mysqli_num_rows($result)==0){
        $format = "%0".$num."d";        
        $newid = $prefix.@sprintf($format,1);
    }else{        
        $row  = mysqli_fetch_array($result);
        $old = $row[0];        
        $oldid = explode($prefix, $old);
        $format = "%0".$num."d";        
        $newid = $prefix.@sprintf($format,($oldid[1]+1));         
    }     
    
    return $newid;
    mysqli_close($link);
}

function changeRStatus($link,$RId,$old,$new){
    $CreateBy = $_SESSION['UserId'];
    $CreateDate = date("Y-m-d H:i:s");
    //UPDATE in room
    mysqli_query($link,"UPDATE `room` SET `RStatus` = '$new' "
            . "WHERE `RId` = '$RId'"); 
    
    //UPDATE in log_roomstatus
    mysqli_query($link,"INSERT INTO `log_roomstatus` (`RId`,`BeforeStatus`, `AfterStatus`, `CreateBy`, `CreateDate`) "
            . "VALUES ('$RId','$old','$new','$CreateBy','$CreateDate')");    
}

function ROName($link,$ROId){
    $result_roomoption = mysqli_query($link,"SELECT `ROName` FROM `roomoption` WHERE `ROId` = '$ROId'");
    $num = mysqli_num_rows($result_roomoption);
    $roomoption = mysqli_fetch_array($result_roomoption);
    if($num==1){
        return $roomoption[0];
    }else{
        return NULL;
    }   
}

function PNameTh($link,$PField){
    $result_pricename = mysqli_query($link, "SELECT `PNameTh` FROM `pricename` WHERE `PField` = '$PField'");
    $num = mysqli_num_rows($result_pricename);
    $pricename = mysqli_fetch_array($result_pricename);
    if($num==1){
        return $pricename[0];
    }else{
        return NULL;
    }     
}

function RName($link,$RId){
    $result_room = mysqli_query($link, "SELECT `RName` FROM `room` WHERE `RId` = '$RId'");
    $num = mysqli_num_rows($result_room);
    $room = mysqli_fetch_array($result_room);
    if($num==1){
        return $room[0];
    }else{
        return NULL;
    } 
}

function showWSName($link){
    $result_transactionworkshifts = mysqli_query($link,"SELECT `WSName` FROM `transactionworkshifts` WHERE `TWStatus` = '1'");
    $num = mysqli_num_rows($result_transactionworkshifts);
    if($num == 0){
        return "<font color=red>ยังไม่ได้เปิดกะ</font>";
    }else{
        $transactionworkshifts = mysqli_fetch_array($result_transactionworkshifts);
        return $transactionworkshifts[0];
    }
}

function showbrowser($link,$browser){
    $result_ad_user = mysqli_query($link,"SELECT `BrowserName` FROM `ad_browser` WHERE `id` = '$browser'");
    if(mysqli_num_rows($result_ad_user)){
        $ad_user = mysqli_fetch_array($result_ad_user);
        return $ad_user[0];
    }else{
        return null;
    }    
}

function showUserUEmail($link,$UserId){
    $result_ad_user = mysqli_query($link,"SELECT `UEmail` FROM `ad_user` WHERE `UserId` = '$UserId'");
    if(mysqli_num_rows($result_ad_user)){
        $ad_user = mysqli_fetch_array($result_ad_user);
        return $ad_user[0];
    }else{
        return null;
    }    
}

function showUserPosition($link,$UserId){
    $result_ad_user = mysqli_query($link,"SELECT `UserPosition` FROM `ad_user` WHERE `UserId` = '$UserId'");
    if(mysqli_num_rows($result_ad_user)){
        $ad_user = mysqli_fetch_array($result_ad_user);
        return $ad_user[0];
    }else{
        return null;
    }    
}

function showUserFullName($link,$UserId){
    $result_ad_user = mysqli_query($link,"SELECT `UserFullName` FROM `ad_user` WHERE `UserId` = '$UserId'");
    if(mysqli_num_rows($result_ad_user)){
        $ad_user = mysqli_fetch_array($result_ad_user);
        return $ad_user[0];
    }else{
        return null;
    }    
}

function showGGName($link,$GGId){
    $result_guestgroup = mysqli_query($link,"SELECT `GGName` FROM `guestgroup` WHERE `GGId` = '$GGId'");
    if(mysqli_num_rows($result_guestgroup)==1){
        $guestgroup = mysqli_fetch_array($result_guestgroup);
        return $guestgroup[0];
    }else{
        return null;
    }
}

function showPGName($link,$PGId){
    $result_productgroup = mysqli_query($link,"SELECT `PGName` FROM `productgroup` WHERE `PGId` = '$PGId'");
    if(mysqli_num_rows($result_productgroup)==1){
        $productgroup = mysqli_fetch_array($result_productgroup);
        return $productgroup[0];
    }else{
        return null;
    }
}

function GName($link,$GId){
    $result_guest = mysqli_query($link,"SELECT `GName` FROM `guest` WHERE `GId` = '$GId'");
    if(mysqli_num_rows($result_guest)==1){
        $guest = mysqli_fetch_array($result_guest);
        return $guest[0];
    }else{
        return null;
    }
}

function GFullName($link,$GId){
    $result_guest = mysqli_query($link,"SELECT `GName`, `GLastname` FROM `guest` WHERE `GId` = '$GId'");
    if(mysqli_num_rows($result_guest)==1){
        $guest = mysqli_fetch_array($result_guest);
        return $guest[0]." ".$guest[1] ;
    }else{
        return null;
    }
}

function showProName($link,$ProId){
    $result_product = mysqli_query($link,"SELECT `ProName` FROM `product` WHERE `ProId` = '$ProId'");
    if(mysqli_num_rows($result_product)==1){
        $product = mysqli_fetch_array($result_product);
        return $product[0];
    }else{
        return null;
    }
}

function showProUnit($link,$ProId){
    $result_product = mysqli_query($link,"SELECT `ProUnit` FROM `product` WHERE `ProId` = '$ProId'");
    if(mysqli_num_rows($result_product)==1){
        $product = mysqli_fetch_array($result_product);
        return $product[0];
    }else{
        return null;
    }
}

function findTId($link,$RId){
    $result_t = mysqli_query($link,"SELECT `TId` FROM `transaction` "
                                        . "WHERE `RId` = '$RId' "
                                        . "ORDER BY `TId` DESC "
                                        . "LIMIT 1");
    $transaction = mysqli_fetch_array($result_t);
    return $transaction[0];
}

function nowTWId($link){
    $result_transactionworkshifts = mysqli_query($link,"SELECT `TWId` FROM `transactionworkshifts` WHERE `TWStatus` = '1' ORDER BY `id` DESC LIMIT 1");
    $transactionworkshifts = mysqli_fetch_array($result_transactionworkshifts);
    return $transactionworkshifts[0];
    
}

function ProIdStock($link,$ProId,$datetime){
    $result_recproductsub = mysqli_query($link, "SELECT SUM(RPSubNum) "
            . "FROM `recproductsub` "
            . "WHERE `ProId` = '$ProId' "
            . "AND `RPSubStatus` = '1' "
            . "AND `CreateDate`<'$datetime' "
            . "GROUP BY `ProId`");
    $recproductsub = mysqli_fetch_array($result_recproductsub);
    
    $result_requisitionsub = mysqli_query($link, "SELECT SUM(RQSubNum) "
            . "FROM `requisitionsub` "
            . "WHERE `ProId` = '$ProId' "
            . "AND `RQSubStatus` = '1' "
            . "AND `CreateDate`<'$datetime' "
            . "GROUP BY `ProId`");
    $requisitionsub = mysqli_fetch_array($result_requisitionsub);
    
    $result_saleproductsub = mysqli_query($link, "SELECT SUM(SPSubNum) "
            . "FROM `saleproductsub` "
            . "WHERE `ProId` = '$ProId' "
            . "AND `SPSubStatus` = '1' "
            . "AND `CreateDate`<'$datetime' "
            . "GROUP BY `ProId`");
    $saleproductsub = mysqli_fetch_array($result_saleproductsub);
    
    $result_receive = mysqli_query($link, "SELECT SUM(RecNum) "
            . "FROM `receive` "
            . "WHERE `ProId` = '$ProId' "
            . "AND `RecStatus` = '1' "
            . "AND `CreateDate`<'$datetime' "
            . "GROUP BY `ProId`");
    $receive = mysqli_fetch_array($result_receive);
    $sum = $recproductsub[0]-$requisitionsub[0]-$saleproductsub[0]-$receive[0];
    
    return $sum;    
}

function Export_Database($tables=false, $backup_name=true ){
        //**********ฟังก์ชั่นนี้มี BUG สำหรับชื่อตารางที่เว้นวรรค*****************************
        //ถ้าต้องการบันทึกเป็นตารางให้เลือก $table=true แล้วใส่ค่า array เช่น
        //array("mytable1","mytable2","mytable3")
        $mysqli = new mysqli(db_host,db_username,db_password,db_name); 
        $mysqli->select_db(db_name); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
        foreach($target_tables as $table)
        {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            {
                while($row = $result->fetch_row())  
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  
                    {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)  
                    { 
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ; 
                        }
                        else 
                        {   
                            $content .= '""';
                        }     
                        if ($j<($fields_amount-1))
                        {
                                $content.= ',';
                        }      
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                    {   
                        $content .= ";";
                    } 
                    else 
                    {
                        $content .= ",";
                    } 
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        $backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        //สำหรับดาวน์โหลด
        //$backup_name = $backup_name ? $backup_name : $name.".sql";
        //header('Content-Type: application/octet-stream');   
        //header("Content-Transfer-Encoding: Binary"); 
        //header("Content-disposition: attachment; filename=\"".$backup_name."\"");         
        //echo $content; exit;
        $myfile = fopen("$backup_name", "w") or die("Unable to open file!");
        fwrite($myfile, $content);
    }    
   
?>