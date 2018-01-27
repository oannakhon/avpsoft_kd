<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';


$RPId = $_GET['RPId'];






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

// ส่วนข้อการค้นข้อมูล
$result = mysqli_query($link, "SELECT * FROM `receiveproduct` WHERE `RPId` = '$RPId'");
$rp = mysqli_fetch_array($result);
$BId = $rp['BId'];
$BName = BName($link, $BId);
$RPRefId = $rp['RPRefId'];
$POId = $rp['POId'];
$WId = $rp['WId'];
$WName = WName($link, $WId);
$RPDate = thaifulldate($rp['RPDate']);
$RPNote = $rp['RPNote'];
$SuppId = $rp['SuppId'];
$result_supplier = mysqli_query($link,"SELECT * FROM `supplier` WHERE `SuppId` = '$SuppId'");
$supplier = mysqli_fetch_array($result_supplier);


//หาข้อมูลหัวเอกสาร
$result_company = mysqli_query($link, "SELECT `a`.`ComNameTH`, `a`.`ComAddress`, `a`.`ComTel`, `a`.`ComFax`, `a`.`ComMobile` FROM `company` AS `a` "
        . "INNER JOIN `branch` AS `b` "
        . "ON `a`.`ComId` = `b`.`ComId` "
        . "WHERE `b`.`BId` = '$BId'");
$company = mysqli_fetch_array($result_company);
$ComNameTH = $company[0];
$ComAddress = $company[1];
$ComTel = $company[2];
$ComFax = $company[3];
$ComMobile = $company[4];


 
//คำนวนว่าต้องมีกี่หน้า
$perpage = 16;
$sql_rssub = "SELECT * FROM `receiveproductsub` WHERE `RPId` LIKE '$RPId' AND `RPSubStatus` LIKE 1";
$result_rssub = mysqli_query($link, $sql_rssub);
$num = mysqli_num_rows($result_rssub);
//$numpage  = ceil($num/$perpage);
$numpage  = 1;

$pagetotalprice = 0; //ค่าเริ่มต้น ผลรวมแต่ละหน้า

//แต่ละหน้า
for($i=0;$i<$numpage;$i++){
$pagenumber = $i+1;  //หน้าที่
$pagetotal = $numpage ; //หน้าทั้งหมด
$pdf->AddPage('P', 'A4');
//หัวเอกสาร
$pdf->Image("assets/images/company/CO1.jpg",13,0,30,0,''); // left top width

$pdf->SetXY(16,4);
$pdf->SetFont('cordia','B',20);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "$ComNameTH" ),0,2,'C');

$pdf->SetFont('cordia','',14);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "$ComAddress" ),0,2,'C');
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "โทรศัพท์ $ComTel มือถือ $ComMobile  โทรสาร $ComFax" ),0,2,'C');

$pdf->SetXY(16,28);
$pdf->SetFont('cordia','B',24);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "ใบรับสินค้าเข้า" ),0,0,'C');
//จบหัวเอกสาร







//กรอบโค้ง
$pdf->SetLineWidth(0.5); 
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.3); //กรอบซ้ายขวาบน
$pdf->RoundedRect(15, 40, 120, 42, 2, 'DF'); // x,y,กว้าง,สูง,  
$pdf->RoundedRect(137, 40, 60, 42, 2, 'DF'); // x,y,กว้าง,สูง,

//กรอบกลาง
$pdf->RoundedRect(15, 84, 182, 165, 2, 'DF'); // x,y,กว้าง,สูง,  
$pdf->Line(15, 95, 197, 95);   //1 แนวนอนตั้งหัวตาราง
$pdf->Line(15, 240, 197, 240);   //2 ช่องรวมด้านล่าง
$pdf->Line(40, 84, 40, 240);   //4 แนวตั้งเส้นแรก
$pdf->Line(120, 84, 120, 240);   //5 แนวตั้ง ต่อมา
$pdf->Line(145, 84, 145, 240);   //6
$pdf->Line(170, 84, 170, 249);   //7



//กรอบ 3 อันล่าง
$pdf->RoundedRect(15, 260, 59, 30, 2, 'DF'); // x,y,กว้าง,สูง,  
$pdf->RoundedRect(76, 260, 60, 30, 2, 'DF'); // x,y,กว้าง,สูง, 
$pdf->RoundedRect(138, 260, 59, 30, 2, 'DF'); // x,y,กว้าง,สูง,

//$pdf->RoundedRect(156, 10, 40, 16, 2, 'DF'); // สำหรับลูกค้า


$pdf->SetDash(1, 1); //5mm on, 5mm off
$pdf->Line(15, 284, 74, 284);   //เส้นประ 1
$pdf->Line(76, 284, 136, 284);   //เส้นประ 2
$pdf->Line(138, 284, 197, 284);   //เส้นประ 3
$pdf->SetDash(); //ยกเลิกเส้นประ

//ใส่ข้อความลงในฟอร์ม
$pdf->SetXY(16,40);
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "รหัสเจ้าหนี้" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ชื่อเจ้าหนี้" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "ที่อยู่" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "โทรศัพท์" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "แฟกซ์" ),0,2);

$pdf->SetXY(137,40);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "วันที่เอกสาร" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "เลขที่เอกสาร" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ใบส่งของ" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "เลขที่ใบสั่งซื้อ" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "รับเข้าสาขา" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "คลัง" ),0,2);

$pdf->SetXY(15,84);
$pdf->Cell( 182 , 7 , iconv( 'UTF-8','cp874' , "  รหัสสินค้า                               รายละเอียด                                      จำนวน         หน่วยละ(บาท)   จำนวนเงิน(บาท)" ),0,2);
$pdf->SetXY(15,88);
$pdf->SetFont('cordia','',12);
$pdf->Cell( 182 , 7 , iconv( 'UTF-8','cp874' , " Product Code                                              Description                                                        Quantity                   Unit Price                   Amount" ),0,2);

$pdf->SetXY(15,239);
$pdf->SetFont('cordia','B',10);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "จำนวนเงินรวมทั้งสิ้น(ตัวอักษร)" ),0,2);
$pdf->SetXY(15,243);
$pdf->Cell( 40 , 7 , iconv( 'UTF-8','cp874' , "NET TOTAL WORDS" ),0,2);

$pdf->SetXY(145,241);
$pdf->SetFont('cordia','B',14);
$pdf->Cell( 25 , 7 , iconv( 'UTF-8','cp874' , "รวม" ),0,2,'R');

$pdf->SetFont('cordia','B',14);
$pdf->SetXY(15,250);
$pdf->Cell( 50 , 7 , iconv( 'UTF-8','cp874' , "หมายเหตุ " ),0,2);


$pdf->SetFont('cordia','',14);
$pdf->SetXY(15,250);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "                    $RPNote" ),0,2);
$pdf->SetXY(15,260);
$pdf->Cell( 180 , 7 , iconv( 'UTF-8','cp874' , "                          ผู้จัดทำ                                                       ผู้ตรวจสอบ                                                       ผู้อนุมัติ" ),0,0);

$pdf->SetXY(15,284);
$pdf->Cell( 60 , 7 , iconv( 'UTF-8','cp874' , "วันที่ $RPDate" ),0,0,'C');
$pdf->Cell( 100 , 7 , iconv( 'UTF-8','cp874' , "    วันที่/DATE........./................../...........             วันที่/DATE........./................../..........." ),0,2);






//เริ่มต้น FILL ข้อมูล--------------------------------------TOP----------------------------------------------
//ใส่ข้อความลงในฟอร์ม
$pdf->SetXY(35,40);
$pdf->SetFont('cordia','',14);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$SuppId" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$supplier[SuppName]" ),0,2);
$pdf->SetXY(25,54);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$supplier[SuppAddress]" ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$supplier[SuppAddress2]" ),0,2);
$pdf->SetXY(35,68);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$supplier[SuppTel] " ),0,2);
$pdf->Cell( 90 , 7 , iconv( 'UTF-8','cp874' , "$supplier[SuppFax]" ),0,2);

$pdf->SetXY(160,40);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$RPDate" ),0,2);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$RPId" ),0,2);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$RPRefId" ),0,2);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$POId" ),0,2);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$BId ($BName)"),0,2);
$pdf->Cell( 37 , 7 , iconv( 'UTF-8','cp874' , "$WId ($WName)" ),0,2);




//---END------------------------------------------TOP---------------------------------------------------


 
//เริ่มต้น FILL ข้อมูล--------------------------------------ITEM----------------------------------------------
//รายการย่อย

$pdf->SetFont('cordia','',14);
$a = ($i*$perpage);
$b = $perpage;
$pdf->SetXY(16,96);  //ตำแหน่งเริ่มพิมพ์ 
  /*  
if($i>0){
    //สำหรับพิมพ์ยอดยกมา
        $pdf->Cell( 20  , 6 , iconv( 'UTF-8','cp874' , "" ) );
        $pdf->Cell( 84  , 6 , iconv( 'UTF-8','cp874' , "ยกมา" ));
        $pdf->Cell( 25  , 6 , iconv( 'UTF-8','cp874' , "" ));
        $pdf->Cell( 25  , 6 , iconv( 'UTF-8','cp874' , "" ),0 ,0 , 'R' );
        $pdf->Cell( 27  , 6 , iconv( 'UTF-8','cp874' , "$pagetotalprice_format " ),0 ,0 , 'R' );
        $pdf->Ln(); //ขึ้นบรรทัดใหม่  
    }
*/

//$sql = "SELECT * FROM `receiveproductsub` WHERE `RPId` LIKE '$RPId' AND `RPSubStatus` LIKE 1 LIMIT $a,$b ";
$sql = "SELECT * FROM `receiveproductsub` WHERE `RPId` LIKE '$RPId' AND `RPSubStatus` LIKE '1'";
$result = mysqli_query($link, $sql);
$j = 0;
$total = 0;

while($rpsub = mysqli_fetch_array($result)){
    /*
    $j++;
    $r_price = number_format($rssub['ProsubPrice'], 2 ); 
    $subbtotal = $rssub['ProsubPrice']*$rssub['ProsubAmount'];  //ราคารวมแต่ละรายการ (ราคาต่อหน่วย*จำนวน)
    $rs_totalprice = number_format($subbtotal, 2 ); //ราคาต่อหน่วยใส่ฟอร์แมท
    $ProductId = ProductId($link,$rssub['ProsubId']);
    */   
    $RPSubId = $rpsub['RPSubId'];
    $show_RPSubPU = number_format($rpsub['RPSubPU'],2);
    $show_RPSubPrice = number_format($rpsub['RPSubPrice'],2);
    $PId = $rpsub['PId'];
    $result_product = mysqli_query($link, "SELECT `PCode`, `PName`, `PUnit` FROM `product` WHERE `PId` = '$PId'");
    $product = mysqli_fetch_array($result_product);
    
    //เริ่มต้นพิมพ์รายการ
    $pdf->Cell( 24  , 6 , iconv( 'UTF-8','cp874' , "$product[PCode]" ),0,0 );
    $pdf->Cell( 80  , 6 , iconv( 'UTF-8','cp874' , "$product[PName]" ));
    $pdf->Cell( 25  , 6 , iconv( 'UTF-8','cp874' , "  $rpsub[RPSubNum] $product[PUnit]" ));
    $pdf->Cell( 25  , 6 , iconv( 'UTF-8','cp874' , "$show_RPSubPU " ),0 ,0 , 'R' );
    $pdf->Cell( 27  , 6 , iconv( 'UTF-8','cp874' , "$show_RPSubPrice " ),0 ,0 , 'R' );
    $pdf->Ln(); //ขึ้นบรรทัดใหม่
    //แสดง เลขเครื่อง
    $SN="";
    $result_receiveproductsubsn = mysqli_query($link,"SELECT `RPSubSNName` FROM `receiveproductsubsn` WHERE `RPSubId` = '$RPSubId' AND `RPSubSNStatus` = '1' ");
    while($rpssn = mysqli_fetch_array($result_receiveproductsubsn)){
        $SN .= "#".$rpssn[0].", ";
    }
    $pdf->Cell( 24  , 6 , iconv( 'UTF-8','cp874' , "" ),0,0 );
    $pdf->MultiCell( 80  , 7 , iconv( 'UTF-8','cp874' , "$SN" ),0,'L' );
    $pdf->Ln(); //ขึ้นบรรทัดใหม่
    
    $total = $total + $rpsub['RPSubPrice'];
    //$pagetotalprice = $pagetotalprice + $subbtotal;
}
//---END------------------------------------------ITEM---------------------------------------------------





$bahttext = bahttext($total);
$show_total = number_format($total, 2 ); 



$pdf->SetXY(170,241);
$pdf->Cell( 27  , 7 , iconv( 'UTF-8','cp874' , "$show_total " ),0 ,2 , 'R' );
$pdf->SetXY(48,241);
$pdf->Cell( 105  , 7 , iconv( 'UTF-8','cp874' , "- $bahttext -" ),0 ,0 , '' );  //แสดง Bahttext

/*
//--- แสดงข้อมูลราคาหน้าสุดท้าย ------------------------------------------------------
if($i==($numpage-1)){
    //แสดงรายการด้านล่างหน้าสุดท้าย
$pdf->Cell( 27  , 7 , iconv( 'UTF-8','cp874' , "$RSDiscount " ),0 ,2 , 'R' );
$pdf->Cell( 27  , 7 , iconv( 'UTF-8','cp874' , "$RSSubtotal " ),0 ,2 , 'R' );
$pdf->Cell( 27  , 7 , iconv( 'UTF-8','cp874' , "$RSDeposit " ),0 ,2 , 'R' );
$pdf->Cell( 27  , 7 , iconv( 'UTF-8','cp874' , "$RSRemain " ),0 ,2 , 'R' );


}
//--- END แสดงราคาหน้าสุดท้าย ------------------------------------------------------

*/

}//END PAGE---------------------------------------------------------------------


//-----Save File----------------------------------------------------------------
/*
$pdfdir = "pdfreserve/";
if(!is_dir($pdfdir.$RSId)) {mkdir($pdfdir.$RSId);}// ถ้าไม่มี folder เลขที่ใบเสนอราคา สร้างขึ้นมา
$filename = $pdfdir.$RSId."/".$RSId."-".date("YmdHis").".pdf";
$pdf->Output($filename,'I');    //save file
 * 
 */
$filename = $RPId;
$pdf->Output($filename,'I');
//END Save File-----------------------------------------------------------------

?>