<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

$ParAddress = $_GET['ParAddress'];
$ParNo = ParNobyParAddress($link, $BId, $ParAddress);

$ParDaytransferowner = ParDaytransferowner($link, $BId, $ParNo);
$now = strtotime(date('Y-m-d'));
$numday = ($now - strtotime($ParDaytransferowner))/(60*60*24);
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">การรับประกัน บ้านเลขที่ <?php echo $ParAddress; ?> โอน <?php echo viewdate($ParDaytransferowner); ?></h4>
        </div>          
        <div class="modal-body">            
            <table class="table table-bordered">
                  <thead>
                    <tr>
                      
                      <th>รายการ</th>
                      <th width="20%">เวลารับประกัน</th>
                      <th width="20%">สถานะ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>                      
                      <td>โครงสร้าง</td>
                      <td>5 ปี</td>
                      <td>
                        <?php 
                              $war = 365*5;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> 
                      </td>
                    </tr>
                    <tr>                      
                      <td>รอยร้าวภายนอกอาคาร</td>
                      <td>1 ปี</td>
                      <td><?php 
                              $war = 365;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>ระบบไฟฟ้า</td>
                      <td>3 เดือน</td>
                      <td><?php 
                              $war = 30*3;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>หลอดไฟฟ้า LED</td>
                      <td>2 ปี</td>
                      <td><?php 
                              $war = 365*2;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>ระบบประปา</td>
                      <td>3 เดือน</td>
                      <td><?php 
                              $war = 30*3;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>ปลวก</td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>                      
                      <td>รั้วและกำแพง</td>
                      <td>1 ปี</td>
                      <td><?php 
                              $war = 365*1;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>การรั่วซึม</td>
                      <td>4 ปี</td>
                      <td><?php 
                              $war = 365*4;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    <tr>                      
                      <td>สุขภัณฑ์</td>
                      <td>3 เดือน</td>
                      <td><?php 
                              $war = 30*3;
                              if($numday>$war) echo '<font color=red>หมดประกัน</font>';
                        ?> </td>
                    </tr>
                    
                  </tbody>
                </table>
            
        </div>