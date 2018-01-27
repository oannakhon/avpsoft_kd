<?php
include_once 'mainfn.php';
$to = CValue($link, 'EmailBackup');
$NumBackup = CValue($link, 'NumBackup');

        $directory = "bs";
        $filename =  $directory."/".db_name."-".date('Ymd');
        $filenamesql        = $filename.".sql";
        $filenamezip        = $filename.".zip";
        $filePath = dirname($filenamezip);        
        
        //Check filename sql ตรวจสอบว่าวันนี้ Backup หรือยัง
        if(!file_exists($filenamesql)){
            Export_Database($tables=false, $filenamesql );
            $zip = new ZipArchive();
            $zip->open($filenamezip, ZipArchive::CREATE);
            $zip->addFile($filenamesql);
            $zip->close();
            
            /*----disable sendmail by adisak 17/11/2560
            //sendmail by gmail
            require_once('class.phpmailer.php');
            $mail = new PHPMailer();
            $mail->CharSet = "utf-8";
            $mail->IsHTML(true);
            $mail->IsSMTP();
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
            $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
            $mail->Port = 465; // set the SMTP port for the GMAIL server
            $mail->Username = "thaismartapps.sender@gmail.com"; // GMAIL username
            $mail->Password = "0840846171"; // GMAIL password
            $mail->From = "no-reply@avp.co.th"; // "name@yourdomain.com";
            $mail->FromName = "AVP Enterprise Co.,Ltd."; // set from Name
            $mail->Subject = "[Backup]-".date('d/m/Y')."-".db_name." [".$_SERVER['SERVER_NAME']."]"; 
            $mail->AddAddress($to); // to Address 
            $mail->set('X-Priority', '3'); //Priority 1 = High, 3 = Normal, 5 = low
            $mail->Body = "[Backup]-".date('d/m/Y')."-".db_name; 
            $mail->AddAttachment($filenamezip);
            $mail->Send();
            */
            //Clear Files
            //DELETE ZIP All
            $zipfiles = array();
                foreach (glob("$directory/*.zip") as $file) {            
                unlink($file);
            }
            $sqlfiles = array();
                foreach (glob("$directory/*.sql") as $file) {            
                $sqlfiles[] = $file;
            }
            rsort($sqlfiles);
            $num = count($sqlfiles);
            for($i=$NumBackup;$i<$num;$i++){ //จำนวนวันที่แบคอัพย้อนหลัง
                unlink($sqlfiles[$i]);
            }
        }
        
?>
