<?php
//แสดง dropdown รายการสินค้า ตามกลุ่มที่ส่งมา
include 'mainfn.php';
$SGId = trim(@$_REQUEST['SGId']); //กลุ่มสินค้าที่ส่งมา
echo "<select class=\"form-control\" id=\"LV1\" name=\"LV1\" required>";
echo "<option value=\"%\">ทั้งหมด</option>";
$result_product = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGParentId` = '$SGId' AND `SGStatus` = '1'");
while ($product = mysqli_fetch_array($result_product)){
    echo "<option value=\"".$product['SGId']."\" >".$product['SGName']."</option>";
}
echo "</select>";

                            
                        