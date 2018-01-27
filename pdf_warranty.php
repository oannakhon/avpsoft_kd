<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';
$BId = $_SESSION['BId'];
$BName = BName($link, $BId);
//Set ค่าเริ่มต้น
$OldDate = ""; //สำหรับเก็บวันที่บรรทัดก่อน
$OldEpId = ""; //เก็บเลขที่เอกสารบรรทัดก่อน
$totalsum = 0; //ยอดรวมแต่ละหน้า


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
$ParNo = "";
$ParNo2 = "";
$ParNo3 = "";
$ParNo4 = "";
$ParNo5 = "";
$ParNo6 = "";
$ParNo7 = "";
$ParNo8 = "";


//---------------------ตาราง----------------------------------------------------


$pdf->AddPage('L', 'A4');

$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "รายงานบ้านที่อยู่ในประกัน" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "โครงการ ". BName($link, $BId) ),0,1,'C');    
    
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ที่" ),1,0,'C');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลา" ),1,0,'C');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บ้านที่อยู่ในประกัน" ),1,1,'C');

$result = mysqli_query($link, "SELECT * FROM `parcel` WHERE `BId` = '$BId' AND `ParDaytransferowner` != '0000-00-00' ORDER BY `id`");
while ($parcel = mysqli_fetch_array($result)){


$ParDaytransferowner = ParDaytransferowner($link, $BId, $parcel['ParNo']);
$now = strtotime(date('Y-m-d'));
$numday = ($now - strtotime($ParDaytransferowner))/(60*60*24);

 $war = 365*5;
 if($numday<$war){$ParNo .= $parcel['ParNo'].", ";}
 
 $war = 365;
 if($numday<$war){$ParNo2 .= $parcel['ParNo'].", ";}
 
 $war = 30*3;
 if($numday<$war){$ParNo3 .= $parcel['ParNo'].", ";}
 
 $war = 365*2;
 if($numday<$war){$ParNo4 .= $parcel['ParNo'].", ";}
 
 $war = 30*3;
 if($numday<$war){$ParNo5 .= $parcel['ParNo'].", ";}
 
  $war = 365*1;
 if($numday<$war){$ParNo6 .= $parcel['ParNo'].", ";}
 
   $war = 365*4;
 if($numday<$war){$ParNo7 .= $parcel['ParNo'].", ";}
 
   $war = 30*3;
 if($numday<$war){$ParNo8 .= $parcel['ParNo'].", ";}
}
//-----ตาราง ----------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "1" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "โครงสร้าง" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "5 ปี" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo ),0,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
//-----รอยร้าว------------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "2" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "รอยร้าวภายนอกอาคาร" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "1 ปี" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo2 ),0,'L');



//-----------------------------------------------------------------------------
$pdf->AddPage('L', 'A4');

$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "รายงานบ้านที่อยู่ในประกัน" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "โครงการ ". BName($link, $BId) ),0,1,'C');    
    
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ที่" ),1,0,'C');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลา" ),1,0,'C');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บ้านที่อยู่ในประกัน" ),1,1,'C');

//-----ระบบไฟฟ้า----------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "3" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "ระบบไฟฟ้า" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "3 เดือน" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo3 ),0,'L');


//-----หลอดไฟฟ้า LED------------------------------------------------------------------
$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "4" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "หลอดไฟฟ้า LED" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "2 ปี" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo4 ),0,'L');


//-----------------------------------------------------------------------------
$pdf->AddPage('L', 'A4');

$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "รายงานบ้านที่อยู่ในประกัน" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "โครงการ ". BName($link, $BId) ),0,1,'C');    
    
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ที่" ),1,0,'C');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลา" ),1,0,'C');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บ้านที่อยู่ในประกัน" ),1,1,'C');

//-----ระบบไฟฟ้า----------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "5" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "ระบบประปา" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "3 เดือน" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo5 ),0,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
//-----หลอดไฟฟ้า LED------------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');
$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "6" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "รั้วและกำแพง" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "1 ปี" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo6 ),0,'L');



//-----------------------------------------------------------------------------
$pdf->AddPage('L', 'A4');

$pdf->SetFont('cordia','B',16);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "รายงานบ้านที่อยู่ในประกัน" ),0,1,'C');
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "โครงการ ". BName($link, $BId) ),0,1,'C');    
    
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ที่" ),1,0,'C');
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
$pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลา" ),1,0,'C');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บ้านที่อยู่ในประกัน" ),1,1,'C');

//-----ระบบไฟฟ้า----------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "7" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "การรั่วซึม" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "4 ปี" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo7 ),0,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
//-----หลอดไฟฟ้า LED------------------------------------------------------------------
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
$pdf->Cell( 0 , 80 , iconv( 'UTF-8','cp874' , "" ),1,1,'L');

$pdf->SetXY(16,31+80); //ตำแหน่งเริ่มพิมพ์รายการย่อย
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "8" ),0,0,'C');
$pdf->Cell( 60 , 80 , iconv( 'UTF-8','cp874' , "สุขภัณฑ์" ),0,0,'L');
$pdf->Cell( 20 , 80 , iconv( 'UTF-8','cp874' , "3 เดือน" ),0,0,'C');
$pdf->MultiCell( 0 , 7 , iconv( 'UTF-8','cp874' , $ParNo8 ),0,'L');




$pdf->Output();
//END Save File-----------------------------------------------------------------

?>