<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';

$RecId = $_GET['RecId'];
$BId = $_SESSION['BId'];


$result = mysqli_query($link, "SELECT * FROM `receipt` WHERE `RecId` = '$RecId'");
$rec = mysqli_fetch_array($result);
$RecVol = @sprintf('%03d',$rec['RecVol']);
$RecNo = @sprintf('%03d',$rec['RecNo']);

//หาหัวบริษัท
$result_receiptvol = mysqli_query($link, "SELECT `ComId` FROM `receiptvol` "
        . "WHERE `RVId` = '$rec[RVId]' AND `BId` = '$BId' ORDER BY `id` DESC");
$receiptvol = mysqli_fetch_array($result_receiptvol);


$result_company = mysqli_query($link, "SELECT * FROM `company` WHERE `ComId` = '$receiptvol[0]'");
$co = mysqli_fetch_array($result_company);
$ComNameTH = $co['ComNameTH'];
$ComNameEN = $co['ComNameEN'];

$RefId = $rec['RefId'];

//เช็คว่ามีการชำระเงินหรือไม่-------------------
    
    $result_check = mysqli_query($link, "SELECT * FROM `debt` WHERE `DebId` = '$RefId' AND `DebStatus` = '2' ");
    if(mysqli_num_rows($result_check)!=0){
        $ReadOnly = "(ชำระบางส่วน)";
    }else{
        $ReadOnly = "";
    }

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



$pdf=new PDF( 'L' , 'mm' , array( 228.6,139.7) );
$pdf->AddPage();
$pdf->AddFont('cordia','','cordia.php');
$pdf->AddFont('cordia','B','cordiab.php');
$pdf->SetMargins( 16,5,15 ); // left, top, right
$pdf->SetAutoPageBreak(1,5); // buttom

//Draw Form
$pdf->SetXY(16, 5);
$pdf->SetFont('cordia','B',20);
$pdf->Cell( 0 , 6 , iconv( 'UTF-8','cp874' , "$ComNameTH" ),0,1,'C'); 
$pdf->Cell( 0 , 6 , iconv( 'UTF-8','cp874' , "$ComNameEN" ),0,1,'C');
$pdf->SetFont('cordia','',14);
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "โทรศัพท์ $co[ComTel]" ),0,1,'C'); 
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "$co[ComAddress]" ),0,1,'C'); 
$pdf->SetFont('cordia','B',20);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "ใบเสร็จรับเงิน" ),0,1,'C'); 
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "RECEIPT" ),0,1,'C');
$pdf->ln();
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "ได้รับเงินจาก..............................................................................................................." ),0,1,'');
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "RECEIVED FROM" ),0,1,'');

$pdf->ln();
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "ที่อยู่............................................................................................................................." ),0,1,'');
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "ADDRESS" ),0,1,'');

$pdf->ln();
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "เพื่อชำระค่า................................................................................................................." ),0,1,'');
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "IN PAYMENT OF" ),0,1,'');

$pdf->ln();
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "จำนวนเงิน..................................................................................................................." ),0,1,'');
$pdf->Cell( 120 , 4 , iconv( 'UTF-8','cp874' , "THE SUM OF BAHT" ),0,1,'');

$pdf->SetFont('cordia','',14);
$pdf->ln();
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "ใบเสร็จรับเงินจะสมบูรณ์ต่อเมื่อมีลายเซนต์ผู้จัดการและผู้เก็บเงิน พร้อมทั้งประทับตราของ" ),0,1,'');
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "บริษัทฯ เป็นสำคัญ หากชำระด้วยเช็คต้องผ่านการเรียกเก็บเงินตามเช็คธนาคารเรียบร้อยแล้ว" ),0,1,'');
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "No receipt will be unless signed by manager and collector with the company" ),0,1,'');
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "seal and if paid by cheque, the receipt is not valid until cheque is clear" ),0,1,'');
$pdf->ln();
$pdf->ln();
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "..........................................                                      .........................................." ),0,1,'');
$pdf->Cell( 120 , 5 , iconv( 'UTF-8','cp874' , "     ผู้จัดการ MANAGER                                                 ผู้รับเงิน COLLECTOR" ),0,1,'');



$pdf->SetXY(140, 28);
$pdf->Cell( 70 , 4 , iconv( 'UTF-8','cp874' , "เลขประจำตัวผู้เสียภาษี.........................................." ),0,2,'');
$pdf->Cell( 70 , 4 , iconv( 'UTF-8','cp874' , "INCOME TAX PANDER NO" ),0,2,'');
$pdf->Cell( 70 , 4 , iconv( 'UTF-8','cp874' , "" ),0,2,'');//เว้นบรรทัด
$pdf->Cell( 70 , 4 , iconv( 'UTF-8','cp874' , "วันที่......................................................................" ),0,2,'');
$pdf->Cell( 70 , 4 , iconv( 'UTF-8','cp874' , "DATE" ),0,2,'');


$pdf->Cell( 70 , 6, iconv( 'UTF-8','cp874' , "          เงินสด/CASH.............................................." ),0,2,'');
$pdf->Cell( 70 , 2 , iconv( 'UTF-8','cp874' , "" ),0,2,'');//เว้นบรรทัด

$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          เช็ค/CHEQUE............................................." ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          เลขที่/NO....................................................." ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          ธนาคาร/BANK............................................" ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          ลงวันที่/DATE.............................................." ),0,2,'');
$pdf->Cell( 70 , 2 , iconv( 'UTF-8','cp874' , "" ),0,2,'');//เว้นบรรทัด

$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          บัตรเครดิต/CREDIT CARD.........................." ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          เลขที่บัตร/NO..............................................." ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          ธนาคาร/BANK............................................" ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          หมดอายุ/EXPIRE DATE..............................." ),0,2,'');
$pdf->Cell( 70 , 2 , iconv( 'UTF-8','cp874' , "" ),0,2,'');//เว้นบรรทัด

$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          โอนเงิน/TRANSFER....................................." ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          เลขบัญชี/NO................................................" ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          ธนาคาร/BANK............................................" ),0,2,'');
$pdf->Cell( 70 , 6 , iconv( 'UTF-8','cp874' , "          ลงวันที่/DATE.............................................." ),0,2,'');
$pdf->Cell( 70 , 2 , iconv( 'UTF-8','cp874' , "" ),0,2,'');//เว้นบรรทัด

$pdf->Image('fpdf17/square.png',142,48,6);
$pdf->Image('fpdf17/square.png',142,56,6);
$pdf->Image('fpdf17/square.png',142,82,6);
$pdf->Image('fpdf17/square.png',142,108,6);

//-------จบวาดฟอร์ม
$pdf->SetFont('cordia','B',20);
//เล่มที่
$pdf->SetXY(16, 15);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "เล่มที่ $RecVol" ),0,0,''); 
//เลขที่ 
$pdf->SetXY(190, 15);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ $RecNo" ),0,0,'');

$pdf->SetFont('cordia','',16);
//ชื่อ
$pdf->SetXY(40, 41);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "$rec[CusName]" ),0,0,'');
//ที่อยู่
$pdf->SetFont('cordia','',14);
$pdf->SetXY(25, 53);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "$rec[CusAddress]" ),0,0,'');

$pdf->SetFont('cordia','',16);
//ชื่อรายการ
$pdf->SetXY(35, 65);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "$rec[RecName] $ReadOnly" ),0,0,'');
//จำนวนเงินที่ชำระ
$RecGrandtotal = number_format($rec['RecGrandtotal'],2);
$pdf->SetXY(35, 77);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "$RecGrandtotal" ),0,0,'');

//จำนวนเงินที่ชำระ ตัวอักษร
$txt_RecGrandtotal = bahttext($rec['RecGrandtotal']);
$pdf->SetXY(50, 83);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "- $txt_RecGrandtotal -" ),0,0,'');

//เลขประจำตัวผู้เสียภาษี
$pdf->SetXY(175, 25);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "$co[ComTaxId]" ),0,0,''); 

//วันที่
$RecDate = thaifulldate($rec['RecDate']);
$pdf->SetXY(155, 37);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "$RecDate" ),0,0,''); 

//เครื่องหมายถูก
if($rec['Rec1']>0){
    $pdf->Image('fpdf17/checked.png',143,49);
}
if($rec['Rec2']>0){
    $pdf->Image('fpdf17/checked.png',143,57);
}
if($rec['Rec3']>0){
    $pdf->Image('fpdf17/checked.png',143,83);
}
if($rec['Rec4']>0){
    $pdf->Image('fpdf17/checked.png',143,109);
}

//จำนวนเงินสด
if($rec['Rec1']>0){
    $pdf->SetXY(170, 47);
    $Rec1 = number_format($rec['Rec1'],2);
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$Rec1" ),0,0,''); 
}
//จำนวนเช็ค
if($rec['Rec2']>0){
    $pdf->SetXY(170, 55);
    $Rec2 = number_format($rec['Rec2'],2);
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$Rec2" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote1]" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote2]" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote3]" ),0,2,'');
}
//จำนวนบัตรเครดิต
if($rec['Rec3']>0){
    $pdf->SetXY(170, 81);
    $Rec3 = number_format($rec['Rec3'],2);
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "                 $Rec3" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote4]" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote5]" ),0,2,'');
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "              $rec[RecNote6]" ),0,2,'');
}

//จำนวนเงินสด
if($rec['Rec4']>0){
    $pdf->SetXY(170, 107);
    $Rec4 = number_format($rec['Rec4'],2);
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "        $Rec4" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote1]" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote2]" ),0,2,''); 
    $pdf->Cell( 40 , 6 , iconv( 'UTF-8','cp874' , "$rec[RecNote3]" ),0,2,'');
}


//โครงการ
$pdf->SetFont('cordia','',12);
$pdf->SetXY(16, 8);
$BName = BName($link, $rec['BId']);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "$BName" ),0,0,''); 

$filename = "ใบเสร็จรับเงินเลขที่-".$BId."-".$RecId.".pdf";
$pdf->Output($filename,'I');
?>