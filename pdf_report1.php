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


$perpage = 35;  // จำนวนรายการต่อหน้า



//คำนวนว่าต้องมีกี่หน้า
$result = mysqli_query($link, "SELECT * FROM `debt` AS `a` LEFT JOIN `parcel` AS `b` "
        . "ON `a`.`ParNo` = `b`.`ParNo` "
        . "AND `a`.`BId` = `b`.`BId` "
        . "WHERE `a`.`BId` = '$BId' "
        . "AND `a`.`DebStatus` != '0' "
        . "ORDER BY `a`.`ParNo`");
$num = mysqli_num_rows($result); //จำนวนรายการทั้งหมด
$numpage  = ceil($num/$perpage);  //จำนวนหน้า



//------------------- แต่ละหน้า --------------------------------------------------
for($i=0;$i<$numpage;$i++){
    
    $pagenumber = $i+1;  //หน้าที่
    $pagetotal = $numpage ; //หน้าทั้งหมด
    $pdf->AddPage('P', 'A4');
    
    $pdf->SetFont('cordia','B',16);
    $pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "รายงานค่าส่วนกลางรายแปลง" ),0,1,'C');
    $pdf->SetFont('cordia','',14);
    $pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "สาขา $BName " ),0,1,'');
    $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "แปลง" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "วันที่โอน" ),1,0,'C');
    $pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "ขนาดที่ดิน" ),1,0,'C');
    $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
    $pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "ระยะเวลา" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ยอดชำระ" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "ชำระแล้ว" ),1,1,'C');   
    
    
    for($j=1;$j<=$perpage;$j++){
    $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');  
    }
    
    
    //หน้า
    $pdf->SetXY(166, 10);
    $pdf->SetFont('cordia','',14);
    $pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "หน้า $pagenumber/$pagetotal" ),0,0,'R');
    
    //--- fill ข้อมูล---------------------------------------------------------
    $pdf->SetFont('cordia','',14);
    $pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
    $a = ($i*$perpage);
    $b = $perpage;
    
    
    
    $sql = "SELECT * FROM `debt` AS `a` LEFT JOIN `parcel` AS `b` "
        . "ON `a`.`ParNo` = `b`.`ParNo` "
        . "AND `a`.`BId` = `b`.`BId` "
        . "WHERE `a`.`BId` = '$BId' "
        . "AND `a`.`DebStatus` != '0' "
        . "ORDER BY `a`.`ParNo` LIMIT $a,$b ";
    $result = mysqli_query($link, $sql);
    
    while($r = mysqli_fetch_array($result)){
        //สำหรับแสดงผล
        $parcel = $r['ParNo'];
        $ParDaytransferowner = viewdate($r['ParDaytransferowner']);
        $DebName = $r['DebName'];   
        $DebTotal = number_format($r['DebTotal'],2);
        $ParArea = number_format($r['ParArea'],1);
        $ServiceTime = viewdate($r['ServiceStart'])." - ".viewdate($r['ServiceEnd']);
        
        if($r['DebStatus']==3){
            $Debpay = $DebTotal;
        }else{
            $Debpay = "";
        }
        
        $pdf->Cell( 10 , 7 , iconv( 'UTF-8','cp874' , "$parcel" ),0,0,'C');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$ParDaytransferowner" ),0,0,'C');
        $pdf->Cell( 15 , 7 , iconv( 'UTF-8','cp874' , "$ParArea" ),0,0,'C');
        $pdf->SetFont('cordia','',12);
        $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$DebName" ),0,0,'');
        $pdf->Cell( 35 , 7 , iconv( 'UTF-8','cp874' , "$ServiceTime" ),0,0,'C');
        $pdf->SetFont('cordia','',14);
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$DebTotal" ),0,0,'R');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$Debpay" ),0,1,'R'); 
        
        
        
    }
    
         
}











$pdf->Output("บันทึกค่าใช้จ่าย.pdf",'I');
//END Save File-----------------------------------------------------------------

?>