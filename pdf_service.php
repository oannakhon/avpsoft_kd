<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';

$SId = trim($_GET['SId']);
$BId = trim($_GET['BId']);



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
$pdf->SetMargins( 16,5,10 ); // left, top, right
$pdf->SetAutoPageBreak(1,5); // buttom
$pdf->AddPage('P', 'A4');

$result_service = mysqli_query($link,"SELECT * FROM `service` "
        . "WHERE `SId` = '$SId' AND `BId` = '$BId'");
$service = mysqli_fetch_array($result_service); 
 
$BranchId = $service['BId'];
$BranchName = BName($link, $BranchId);
$DocId = $BranchId."/".$SId;

//for qrcode
$url_satisfaction = "https://cssurvey.avpapp.com/index.php?id=kd--".$BranchId."--".$SId;

//$pdf->Image('genqrcode.php?w=dd',60,30,90,0,'PNG');

//$pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=450x450&chl='.$link[$d].'.png',70,100,90);
$pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=50x50&chl='.$url_satisfaction.'.png',160,7,40);

$pdf->SetFont('cordia','',12);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "(สำหรับผู้แจ้งซ่อม)" ),0,0,'L');
$pdf->Cell( 94 , 7 , iconv( 'UTF-8','cp874' , "เลขที่เอกสาร $DocId" ),0,1,'R'); 
$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "ใบรับงานแจ้งซ่อม บริการหลังการขาย" ),0,1,'C');
$pdf->SetFont('cordia','',14);
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "โครงการ $BranchName" ),0,1,'C');
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "ชื่อผู้แจ้งซ่อม....................................................." ),0,0,'');
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "วันที่แจ้งซ่อม....................................................." ),0,1,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "เบอร์โทรศัพท์...................................................." ),0,0,'');
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ผู้รับเรื่อง............................................................" ),0,1,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "บ้านเลขที่..........................................................." ),0,0,'');
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "วันที่โอน............................................................." ),0,1,'');
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ลำดับที่" ),1,0,'C');
$pdf->Cell( 164 , 7 , iconv( 'UTF-8','cp874' , "รายการแจ้งซ่อม" ),1,1,'C');
$pdf->Cell( 20 , 50 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 164 , 50 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "" ),0,1,'C');
$pdf->SetFont('cordia','',14);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ................................................................ผู้รับเรื่อง" ),0,0,'');
$pdf->Cell( 84 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ................................................................ผู้แจ้งซ่อม" ),0,1,'');
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "                   วันที่..........................................." ),0,0,'');
$pdf->Cell( 84 , 7 , iconv( 'UTF-8','cp874' , "                วันที่..........................................." ),0,1,'');


$pdf->SetFont('cordia','',10);

$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "ตัดตามรอบปรุ- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -" ),0,1,'');

$pdf->SetFont('cordia','',12);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "(สำหรับบริษัท)" ),0,0,'L');
$pdf->Cell( 94 , 7 , iconv( 'UTF-8','cp874' , "เลขที่เอกสาร $DocId" ),0,1,'R'); 
$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "ใบรับงานแจ้งซ่อม บริการหลังการขาย" ),0,1,'C');
$pdf->SetFont('cordia','',14);
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "โครงการ $BranchName" ),0,1,'C');
$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "ชื่อผู้แจ้งซ่อม....................................................." ),0,0,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "วันที่แจ้งซ่อม........................................." ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "บ้านเลขที่............................................." ),0,1,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "เบอร์โทรศัพท์...................................................." ),0,0,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "ผู้รับเรื่อง................................................" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "วันที่โอน..............................................." ),0,1,'');

$pdf->Cell( 105 , 7 , iconv( 'UTF-8','cp874' , "รายการแจ้งซ่อม" ),1,1,'C');
$pdf->Cell( 105 , 70 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');

$pdf->SetXY(123,171);
$pdf->Cell( 19 , 7 , iconv( 'UTF-8','cp874' , "นัดซ่อมวันที่" ),1,2,'C');
$pdf->Cell( 19 , 70 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->SetXY(142,171);
$pdf->Cell( 42 , 7 , iconv( 'UTF-8','cp874' , "รายละเอียด" ),1,2,'C');
$pdf->Cell( 42 , 70 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->SetXY(184,171);
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อลูกค้า" ),1,2,'C');
$pdf->Cell( 20 , 70 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');

$pdf->Cell( 0 , 5 , iconv( 'UTF-8','cp874' , "" ),0,1,'C');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บันทึกอื่นๆ............................................................................................................................................................................................" ),0,1,'');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "..............................................................................................................................................................................................................." ),0,1,'');

$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "งานแล้วเสร็จวันที่............................................" ),0,0,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "ประมาณการค่าใช้จ่าย.....................บาท" ),0,0,'');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "ค่าใช่จ่ายจริง.................................บาท" ),0,1,'');
$pdf->SetFont('cordia','',14);
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ................................................................ผู้รับเรื่อง" ),0,0,'');
$pdf->Cell( 84 , 7 , iconv( 'UTF-8','cp874' , "ลงชื่อ................................................................ผู้แจ้งซ่อม" ),0,1,'');
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "                   วันที่..........................................." ),0,0,'');
$pdf->Cell( 84 , 7 , iconv( 'UTF-8','cp874' , "                วันที่..........................................." ),0,1,'');


// Fill ข้อมูล
$SDate = thaifulldate($service['SDate']);
$User = showUserFullName($link, $service['UserId']);
$ParDaytransferowner = thaifulldate(ParDaytransferowner($link, $service['BId'], $service['ParNo']));


$pdf->SetFont('cordia','',14);
$pdf->SetXY(40,28);
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$service[SCustomer]" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "$SDate" ),0,1,'');

$pdf->Cell( 24 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$service[SCustomerMobile]" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "$User" ),0,1,'');

$pdf->Cell( 24 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$service[ParAddress]" ),0,0,'');
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "$ParDaytransferowner" ),0,1,'');


//Loop ในตาราง
$pdf->SetXY(16,57);
$i = 1;
$result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` WHERE `SId` = '$SId' AND `BId` = '$BranchId' AND `SSStatus` != '0'");
while($sub = mysqli_fetch_array($result_servicesub)){
    if($sub['SSDetail']!=""){
        $item = $sub['SSName']."(".$sub['SSDetail'].")";
    }else{
        $item = $sub['SSName'];
    }
    
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$i" ),0,0,'C');
    $pdf->MultiCell( 164 , 7 , iconv( 'UTF-8','cp874' , "$item" ),0,'L');
    $i++;
}


//Fill ส่วนของบริษัท
$pdf->SetFont('cordia','',14);
$pdf->SetXY(40,156);
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$service[SCustomer]" ),0,0,'');
$pdf->Cell( 55 , 7 , iconv( 'UTF-8','cp874' , "$SDate" ),0,0,'');
$pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "$service[ParAddress]" ),0,1,'');

$pdf->Cell( 24 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,'');
$pdf->Cell( 70 , 7 , iconv( 'UTF-8','cp874' , "$service[SCustomerMobile]" ),0,0,'');
$pdf->Cell( 55 , 7 , iconv( 'UTF-8','cp874' , "$User" ),0,0,'');
$pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "$ParDaytransferowner" ),0,1,'');

//Loop ในตาราง
$pdf->SetXY(16,178);
$i = 1;
$result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` WHERE `SId` = '$SId' AND `BId` = '$BranchId' AND `SSStatus` != '0'");
$itemall = "";
while($sub = mysqli_fetch_array($result_servicesub)){
    if($sub['SSDetail']!=""){
        $item = $sub['SSName']."(".$sub['SSDetail'].")";
    }else{
        $item = $sub['SSName'];
    }    
    $itemall .= $i.". ".$item."\n";
    $i++;
}

$pdf->MultiCell( 105  , 7 , iconv( 'UTF-8','cp874' , "$itemall" ),0,'L' );


$pdf->SetXY(16,123);
$pdf->SetFont('cordia','',12);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "*ในกรณีที่มีค่าใช้จ่าย กรุณาตรวจสอบใบเสร็จรับเงินให้ตรงกับเงินที่ท่านชำระ" ),0,1,'R');

$pdf->SetXY(16,285);
$pdf->SetFont('cordia','',12);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "*ในกรณีที่มีค่าใช้จ่าย กรุณาตรวจสอบใบเสร็จรับเงินให้ตรงกับเงินที่ท่านชำระ" ),0,1,'R');

$filename = "ใบรับงานแจ้งซ่อม-".$BId."-".$SId.".pdf";
$pdf->Output($filename,'I');
?>