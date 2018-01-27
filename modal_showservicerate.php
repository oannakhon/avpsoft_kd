<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];


?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">อัตราค่าบริการซ่อมแซมบ้าน</h4>
        </div>          
        <div class="modal-body">            
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      
                      <th>รายการ</th>
                      <th width="20%">ค่าบริการ</th>
                      <th width="20%">หมายเหตุ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>                      
                      <td>กระเบื้องร่อน/ร้าว</td>
                      <td>ตรม.ละ120บาท</td>
                      <td>ลูกค้าซื้อวัสดุเอง</td>
                    </tr>

                    
                  </tbody>
                </table>
            
        </div>