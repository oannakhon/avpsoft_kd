<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';



class PDF extends FPDF
{
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    
      function SetDash($black=false, $white=false)
    {
        if($black and $white)
            $s=sprintf('[%.3f %.3f] 0 d', $black*$this->k, $white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
}


$pdf=new PDF();
$pdf->AddFont('cordia','','cordia.php');
$pdf->AddFont('cordia','B','cordiab.php');
$pdf->SetMargins( 16,10,15 ); // left, top, right
$pdf->SetAutoPageBreak(1,5); // buttom


if(isset($_GET['ParNo'])){
    $ParNo = trim($_GET['ParNo']);
}else{
    $ParNo = "%";
}

//หาว่ามีกี่แปลง

$result_numparcel = mysqli_query($link,"SELECT `ParNo`,`RefId`,`DebDate` FROM `debt` "
        . "WHERE `BId` = '$_SESSION[BId]' "
        . "AND `ParNo` LIKE '$ParNo' "
        . "AND `DebStatus` IN (1,2) "
        . "GROUP BY `ParNo`"
        . "ORDER BY `ParNo`");

while($numparcel = mysqli_fetch_array($result_numparcel)){
    $pdf->AddPage('P', 'A4');
    $ComId = CBValue($link,$_SESSION['BId'],"COForcentral");
    $img = $ComId.".jpg";
    $pdf->Image('assets/images/company/'.$img,16,12,80,0);

$pdf->SetFont('cordia','',16);
$pdf->SetXY(16,40);
$date = thaifulldate2($numparcel['DebDate']);
$BName = BName($link, $_SESSION['BId']);
$ParAddress = 1/1;
$pdf->Cell( 160 , 10 , iconv( 'UTF-8','cp874' , "$date" ),0,1,'R');    
$pdf->Cell( 160 , 10 , iconv( 'UTF-8','cp874' , "เรื่อง   การชำระเงินค่าบริการสาธารณะ" ),0,1,''); 
$pdf->Cell( 160 , 10 , iconv( 'UTF-8','cp874' , "เรื่อง   ท่านสมาชิกโครงการ $BName" ),0,1,'');
$pdf->Cell( 160 , 10 , iconv( 'UTF-8','cp874' , "          บ้านเลขที่ $numparcel[RefId]" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "          บริษัทฯ ขอเรียนแจ้งท่านสมาชิกเรื่องเงินค่าบริการสาธารณะที่ค้างชำระดังรายการด้านล่าง โดยขอความกรุณา" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "ท่านสมาชิก ได้โปรดชำระภายในกำหนดชำระ" ),0,1,''); 
$pdf->ln();
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "ลำดับที่" ),1,0,'C'); 
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "รายกาย" ),1,0,'C');
$pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลาบริการ" ),1,0,'C');
$pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "จำนวนเงิน" ),1,0,'C');
$pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "กำหนดชำระเงิน" ),1,1,'C');

$result_debt = mysqli_query($link, "SELECT * FROM `debt` "
        . "WHERE `ParNo` = '$numparcel[ParNo]' "
        . "AND `BId` = '$_SESSION[BId]' "
        . "AND `DebStatus` IN (1,2) "
        . "ORDER BY `id`");
$i = 0;
$total = 0;
while ($debt = mysqli_fetch_array($result_debt)){
    $i++;
    //จำนวนเงินคงเหลือ
    if($debt['DebStatus']==2){
        $result_receipt = mysqli_query($link, "SELECT SUM(RecGrandtotal) FROM `receipt` "
                      . "WHERE `RefId` = '$debt[DebId]' AND `BId` = '$_SESSION[BId]' AND `RecStatus` = '1'");
        $receipt_sum  = mysqli_fetch_array($result_receipt);    
        $balance = $debt['DebTotal']-$receipt_sum[0];
        $showbalance =  number_format(($debt['DebTotal']-$receipt_sum[0]),2);
    }else{
        $balance = $debt['DebTotal'];
        $showbalance =  number_format($debt['DebTotal'],2);
    }
    
    
    $time = viewdate($debt['ServiceStart'])."-".viewdate($debt['ServiceEnd']);
    $DueDate = viewdate($debt['DueDate']);
    $pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "$i" ),1,0,'C'); 
    $pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$debt[DebName]" ),1,0,'');
    $pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "$time" ),1,0,'C');
    $pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "$showbalance" ),1,0,'R');
    $pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "$DueDate" ),1,1,'C');
    $total = $total+$balance;
}
$showtotal = number_format($total,2);

$pdf->Cell( 120 , 7 , iconv( 'UTF-8','cp874' , "รวมทั้งสิ้น" ),1,0,'R');
$pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "$showtotal" ),1,0,'R');
$pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');


$pdf->SetFont('cordia','',16);

$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "*** หากท่านสมาชิกไม่ชำระค่าบริการสาธารณะภายในเวลาดังกล่าว จะมีผลกระทบต่อการทำงานของระบบ EasyPass ***" ),0,1,''); 

$pdf->SetFont('cordia','BU',16);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "วิธีการชำระเงิน" ),0,1,'');
$pdf->SetFont('cordia','',16);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "1. โอนชำระเข้าบัญชี ชื่อ บริษัท เค.แคป จำกัดของธนาคาร" ),0,1,'');
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "           1.1 กสิกรไทย" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "สาขาสุขาภิบาล 1 (บางบอน)" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ 716-2-58388-8" ),0,1,'');
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "           1.2 กรุงไทย" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "สาขาพันท้ายนรสิงห์ (ถนนพระราม2 กม.17)" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ 982-2-55626-8" ),0,1,'');
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "           1.3 กรุงศรี" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "สาขาพันท้ายนรสิงห์" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ 705-1-01908-1" ),0,1,'');
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "           1.4 ไทยพาณิชย์" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "สาขาถนนพระราม2 กม.13 (พันท้ายนรสิงห์)" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ 404-9-50348-3" ),0,1,'');


$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "           หลังจากท่านโอนเงินเรียบร้อยแล้วขอความกรุณาแจ้งหรือนำส่งหลักฐานการโอนเงินพร้อมระบุโครงการ" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "และบ้านเลขที่ให้ชัดเจนได้ที่ฝ่ายบริการหลังการขาย หรือที่ E-Mail : kanda.kcab.a@gmail.com" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "           หรือ Line ID : 034867118 หรือ Fax.034-867-134" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "2. ชำระได้ที่ สำนักงานบริการหลังการขาย ระหว่างเวลา 08.30 ถึง 17.30 น." ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "           ทั้งนี้ หากท่านชำระเงินค่าส่วนกลางให้กับบริษัท เรียบร้อยแล้ว บริษัทต้องขออภัยมา ณ ที่นี้และขอขอบพระคุณ" ),0,1,'');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "ในความร่วมมือของท่านมา ณ โอกาสนี้" ),0,1,'');

$pdf->SetXY(120,250);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "ขอแสดงความนับถือ" ),0,1,'C');
$pdf->Image('assets/images/signature.png',137,258,18,0);
$pdf->SetXY(120,270);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "(นายพิทักษ์ สมพงษ์)" ),0,2,'C');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "กรรมการบริหาร ด้านบริการหลังการขาย" ),0,2,'C');


$pdf->SetFont('cordia','B',12);
$pdf->SetXY(16,260);
$pdf->Cell( 60 , 21 , iconv( 'UTF-8','cp874' , "" ),1,0,'');
$pdf->SetXY(16,260);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "***หากมีข้อสงสัยประการใดกรุณาติดต่อ" ),0,1,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "    โทร 034-867-118" ),0,1,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "    มือถือ 091-120-0423" ),0,1,'');
}



$filename = "ใบแจ้งหนี้.pdf";
$pdf->Output($filename,'I');
?>