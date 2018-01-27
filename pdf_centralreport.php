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
$pdf->AddPage('L', 'A4');

$pdf->SetFont('cordia','B',16);
$pdf->SetXY(16,10);
$date = date('Y');
$pdf->Cell( 270 , 10 , iconv( 'UTF-8','cp874' , "รายงานค่าส่วนกลางประจำปี $date" ),0,0,'L');    

//หัวตาราง-------------------
$pdf->SetXY(16,20);
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 54 , 14 , iconv( 'UTF-8','cp874' , "เดือน" ),1,0,'C');
$pdf->Cell( 54 , 7 , iconv( 'UTF-8','cp874' , "ค้างชำระ(ก่อนหน้า)" ),1,0,'C');
$pdf->Cell( 54 , 7 , iconv( 'UTF-8','cp874' , "ชำระเงิน" ),1,0,'C');
$pdf->Cell( 54 , 7 , iconv( 'UTF-8','cp874' , "ค่าปรับ" ),1,0,'C');
$pdf->Cell( 54 , 14 , iconv( 'UTF-8','cp874' , "หมายเหตุ" ),1,0,'C');

$pdf->SetXY(70,27);
$pdf->SetFont('cordia','B',14);

$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "หลัง" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "บาท" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "หลัง" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "บาท" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "หลัง" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "บาท" ),1,0,'C');
//---จบหัวตาราง--------------------------


//-----เดือน----------------------------
$pdf->SetXY(16,34);
$pdf->SetFont('cordia','',14);
$Month = array("มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
for($i=0;$i<12;$i++){
$pdf->Cell( 54 , 7 , iconv( 'UTF-8','cp874' , "$Month[$i]" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 27 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 54 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');
}


//-----จบเดือน---------------------------

$pdf->Output();
?>