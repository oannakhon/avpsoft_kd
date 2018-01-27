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


$perpage = 33;  // จำนวนรายการต่อหน้า


//รับค่าที่ GET มา
$EpDateStart = trim($_GET['EpDateStart']);
$EpDateEnd = trim($_GET['EpDateEnd']);
$order = trim($_GET['order']);
//-- จบรับค่า GET
//---จัด format วันที่สำหรับแสดงผล
$ShowEpDateStart = viewdate($EpDateStart);
$ShowEpDateEnd = viewdate($EpDateEnd);
if($order=="DESC"){
    $showorder = "ใหม่ไปเก่า";
}
 else {
    $showorder = "เก่าไปใหม่";
}
//คำนวนว่าต้องมีกี่หน้า
$result = mysqli_query($link, "SELECT `b`.`EpDate`, `b`.`EpId`, `a`.`EpSubName`, `a`.`EpSubAmount` "
        . "FROM `expensesub` AS `a` JOIN `expense` AS `b` "
        . "ON `a`.`EpId` = `b`.`EpId` "
        . "WHERE `b`.`BId` = '$BId' "
        . "AND `b`.`EpStatus` = '1' "
        . "AND `a`.`EpSubStatus` = '1' "
        . "ORDER BY `a`.`id` $order");
$num = mysqli_num_rows($result); //จำนวนรายการทั้งหมด
$numpage  = ceil($num/$perpage);  //จำนวนหน้า



//------------------- แต่ละหน้า --------------------------------------------------
for($i=0;$i<$numpage;$i++){
    
    $pagenumber = $i+1;  //หน้าที่
    $pagetotal = $numpage ; //หน้าทั้งหมด
    $pdf->AddPage('P', 'A4');
    
    $pdf->SetFont('cordia','B',16);
    $pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "บันทึกค่าใช้จ่าย" ),0,1,'C');
    $pdf->SetFont('cordia','',14);
    $pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "สาขา $BName วันที่ $ShowEpDateStart - $ShowEpDateEnd เรียงลำดับ $showorder " ),0,1,'');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "วันที่" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "เลขที่เอกสาร" ),1,0,'C');
    $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "รายการ" ),1,0,'C');
    $pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "หมวดหมู่" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "จำนวนเงิน" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "หมายเหตุ" ),1,1,'C');    
    
    
    for($j=0;$j<=$perpage;$j++){
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,1,'C');    
    }
    
    $pdf->SetFont('cordia','B',14);
    $pdf->Cell( 140 , 7 , iconv( 'UTF-8','cp874' , "รวม" ),1,0,'R');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'C');
    
    //หน้า
    $pdf->SetXY(166, 10);
    $pdf->SetFont('cordia','',14);
    $pdf->Cell( 30 , 7 , iconv( 'UTF-8','cp874' , "หน้า $pagenumber/$pagetotal" ),0,0,'R');
    
    //--- fill ข้อมูล---------------------------------------------------------
    $pdf->SetFont('cordia','',14);
    $pdf->SetXY(16,31); //ตำแหน่งเริ่มพิมพ์รายการย่อย
    $a = ($i*$perpage);
    $b = $perpage;
    
    //ยกมา
    if($pagenumber!=1){
        $Showtotalsum = number_format($totalsum,2);
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,'C');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),0,0,'C');
        $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "ยกมา" ),0,0,'');
        $pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "ยกมา" ),0,0,'');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$Showtotalsum" ),0,0,'R');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),0,1,'C');  
    }    
    
    $sql = "SELECT `b`.`EpDate`, `b`.`EpId`, `a`.`EpSubName`, `a`.`EpSubAmount`, `a`.`ETId` "
        . "FROM `expensesub` AS `a` JOIN `expense` AS `b` "
        . "ON `a`.`EpId` = `b`.`EpId` "
        . "WHERE `b`.`BId` = '$BId' "
        . "AND `b`.`EpStatus` = '1' "
        . "AND `a`.`EpSubStatus` = '1' "
        . "ORDER BY `a`.`id` $order LIMIT $a,$b ";
    $result = mysqli_query($link, $sql);
    while($r = mysqli_fetch_array($result)){
        //สำหรับแสดงผล
        $ShowDate  = viewdate($r[0]);
        $ShowAmount = number_format($r[3],2);
        $ShowEpId = $r[1];
        $ETId = $r[4];
        
        //---ถ้าซ้ำกับรายการก่อนหน้าแสดงค่าว่าง
        if($OldDate==$r[0]){$ShowDate = "";}
        if($OldEpId==$r[1]){$ShowEpId = "";}
        //---จบ            
        
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$ShowDate" ),0,0,'C');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$ShowEpId" ),0,0,'C');
        $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$r[2]" ),0,0,'');
        
        $result_etname = mysqli_query($link, "SELECT `ETName` FROM `expensetype` WHERE `ETId` = '$ETId' ");
        while ($ETName = mysqli_fetch_array($result_etname)){
        $pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "$ETName[0]" ),0,0,'');
        }
        
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$ShowAmount" ),0,0,'R');
        $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "" ),0,1,''); 
        
        
        //ใส่ค่าใว้ให้รอบต่อไปใช้งาน
        $OldDate  = $r[0];
        $OldEpId = $r[1];
        $totalsum = $totalsum + $r[3];

    }
    
    //แสดงผลรวมท้ายหน้า
    $Showtotalsum = number_format($totalsum,2);
    $pdf->SetFont('cordia','B',14);
    $pdf->SetXY(156, 269);
    $pdf->Cell( 20 , 7 , iconv( 'UTF-8','cp874' , "$Showtotalsum" ),0,0,'R');  
    //---จบ fill รายการย่อย-------------------------------------------------------        
}











$pdf->Output("บันทึกค่าใช้จ่าย.pdf",'I');
//END Save File-----------------------------------------------------------------

?>