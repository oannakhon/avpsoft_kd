<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
header('Content-Type: text/html; charset=utf-8');
define('FPDF_FONTPATH','fpdf17/fonts/');
require('fpdf17/fpdf.php');
include_once './mainfn.php';
require_once 'fpdi2/fpdi.php';

$pdf = new FPDI();
$pdf->AddFont('cordia','','cordia.php');
$pdf->AddFont('cordia','B','cordiab.php');

$pdf->setSourceFile('form-pdf/mpdf.pdf');

$pdf->AddPage();
$tplidx = $pdf->ImportPage(1);
$pdf->useTemplate($tplidx, 0, 0, 0);          

$output = $pdf->Output(); 


?>