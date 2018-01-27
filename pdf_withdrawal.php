<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';

$RVId = $_GET['RVId'];

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
$pdf->AddPage('P', 'A4');

$result_renovation = mysqli_query($link,"SELECT * FROM `renovation` WHERE `RVId` = '$RVId'");
if(mysqli_num_rows($result_renovation)==1){

    $Company = CValue($link, "CompanyAftersale");
$pdf->SetFont('cordia','B',20);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "หลักฐานการขอรับเงินประกันคืน" ),0,1,'C');    
$pdf->SetFont('cordia','B',16);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "$Company" ),0,1,'C'); 

$pdf->SetXY(16,45);
$pdf->SetFont('cordia','B',16);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,''); //ได้รับเงินจาก
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "บ้านเลขที่" ),0,1,'');


$pdf->SetFont('cordia','B',16);
$pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "ลำดับ" ),1,0,'C');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
$pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "จำนวนเงิน" ),1,0,'C');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "หมายเหตุ" ),1,1,'C');
    
//สร้างฟอร์ม 4 บรรทัด
for($i=0;$i<4;$i++){
    $pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');
}
$pdf->Cell( 85 , 7 , iconv( 'UTF-8','cp874' , "รวมเงิน" ),1,0,'C');
$pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');

$pdf->SetFont('cordia','',16);
$pdf->ln(); 
$pdf->ln(); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ..........................................................ผู้จ่ายเงิน" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ..........................................................ผู้รับเงิน" ),0,1,'');
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "       (..........................................................)" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "       (..........................................................)" ),0,1,'');  
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "                   ฝ่ายบริการหลังการขาย" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "                         ผู้รับเงินประกันคืน" ),0,1,'');
$pdf->ln(); 
$pdf->ln(); 
$pdf->SetFont('cordia','BU',16);
$pdf->Cell( 85 , 7 , iconv( 'UTF-8','cp874' , "การพิจารณาขอเงินประกันคืน" ),0,0,'');


//fill DATA
$pdf->SetFont('cordia','',16);
$renovation = mysqli_fetch_array($result_renovation);
$fulldate = thaifulldate2($renovation['RVDatewithdrawal']);
$RVWithdrawal = number_format($renovation['RVWithdrawal'],2);
if($renovation['RVWithdrawaltype']==1){
    $note = "(เงินสด)";
}else{
    $note = "(".$renovation['RVWithdrawalcheque'].")";
}
$baht = bahttext($renovation['RVWithdrawal']);
$user = showUserFullName($link, $renovation['UpdateBy']);

$pdf->SetXY(16,30);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "$fulldate" ),0,0,'R'); 
$pdf->SetXY(40,45);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,''); //$renovation[RVWithdrawalName]
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$renovation[ParAddress]" ),0,1,'');


$pdf->SetXY(16,60);
$pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "1" ),0,0,'C');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "ขอเงินประกันคืน" ),0,0,'');
$pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "$RVWithdrawal" ),0,0,'R');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$note" ),0,1,'C');

$pdf->SetXY(101,88);
$pdf->SetFont('cordia','B',16);
$pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "$RVWithdrawal" ),0,0,'R');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "-$baht-" ),0,1,'C');

$pdf->SetFont('cordia','',16);
$pdf->SetXY(25,114);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$user" ),0,0,'C'); 
$pdf->SetXY(115,114);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$renovation[RVWithdrawalName]" ),0,0,'C'); 


$pdf->SetXY(16,153);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "1. อนุมัติคืนเงินประกันได้" ),0,0,''); 
    if($renovation['RVWithdrawalapprove']==1){
        $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "( / )" ),0,1,'');
    }else{
        $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "(   )" ),0,1,'');
    }
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "2. ไม่อนุมัติคืนเงินประกัน เนื่องจาก" ),0,0,''); 
    if($renovation['RVWithdrawalapprove']==0){
        $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "( / )" ),0,1,'');
    }else{
        $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "(   )" ),0,1,'');
    }



$pdf->SetXY(16,167);
$result_sub = mysqli_query($link, "SELECT * FROM `renovationsub` WHERE "
        . "`RVId` = '$RVId' "
        . "AND `RVSubType`='2' "
        . "AND `RVSubStatus` = '1'");
$i=1;
while($sub = mysqli_fetch_array($result_sub)){
   $pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "      2.$i. $sub[RVSubDetail]" ),0,1,'');   
   $i++;
}


$pdf->SetXY(16,260);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ..........................................................ผู้ตรวจสอบ" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ..........................................................ผู้อนุมัติ" ),0,1,'');
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "       (..........................................................)" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "       (..........................................................)" ),0,1,'');  
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "                   ฝ่ายบริการหลังการขาย" ),0,0,''); 
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "                         $Company" ),0,1,'');
}else{
    exit;
}


$filename = "หลักฐานการวางเงินประกัน-".$RVId.".pdf";
$pdf->Output($filename,'I');
?>