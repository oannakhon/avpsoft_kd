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
$pdf->AddPage('P', 'A4');

$pdf->SetFont('cordia','B',14);
$now = date('d/m/Y H:i:s');
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "ประเภทงานซ่อม" ),0,1,'C');
$pdf->SetFont('cordia','',12);
$pdf->Cell( 0 , 7 , iconv( 'UTF-8','cp874' , "$now" ),0,1,'C');

$result_max = mysqli_query($link, "SELECT MAX(`SGLevel`) FROM `servicegroup` WHERE `SGStatus` = '1'");
$sgMax = mysqli_fetch_array($result_max);
$old1 = "";
$old2 = "";

$pdf->SetFont('cordia','',12);

$result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGLevel` = '0' AND `SGStatus` = '1'");
while($sg = mysqli_fetch_array($result)){  
    $SGId = $sg['SGId'];
    
    
    $result1 = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGParentId` = '$SGId' AND `SGStatus` = '1'");
    while($sg1 = mysqli_fetch_array($result1)){  
       $SGId = $sg1['SGId'];
       //$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$sg1[SGName]" ),1,0,''); 
       
       $result2 = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGParentId` = '$SGId' AND `SGStatus` = '1'");
       while($sg2 = mysqli_fetch_array($result2)){
           
           if($old1!=$sg['SGName']){
               $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$sg[SGName]" ),1,0,'');
           }else{
               $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'');
           }
           
           if($old2!=$sg1['SGName']){
               $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$sg1[SGName]" ),1,0,'');
           }else{
               $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "" ),1,0,'');
           }
           
           
           
           
           $pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "$sg2[SGName]" ),1,1,''); 
           $old1 = $sg['SGName'];
           $old2 = $sg1['SGName'];
       }

       
    }
        

}

$filename = "ประเภทงานซ่อม.pdf";
$pdf->Output($filename,'I');
?>