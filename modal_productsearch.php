<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave

//------รับค่ามาจากหน้าหลัก
    $PCode = $_GET['PCode'];
    $PName = $_GET['PName'];
    $PModel = $_GET['PModel'];
    $PGId = $_GET['PGId'];
    $BrandId = $_GET['BrandId'];
    $PSize = $_GET['PSize'];
    $PColor = $_GET['PColor'];
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ค้นหาสินค้า</h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="manageproduct.php">               
              <div class="form-group">
                <label class="control-label col-sm-3">บาร์โค้ด </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="PCode" value="<?php echo $PCode; ?>">
                </div>         
                
              </div>
                <div class="form-group">
                <label class="control-label col-sm-3">ชื่อสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="PName" value="<?php echo $PName; ?>">
                </div>        
              </div>
            <div class="form-group">
                <label class="control-label col-sm-3">รุ่นสินค้า </label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="PModel" value="<?php echo $PModel; ?>">
                </div>        
            </div>
                <div class="form-group">
                <label class="control-label col-sm-3">กลุ่ม </label>
                <div class="col-sm-4">
                    <select class="chosen-select" name="PGId" required>
                        <option value="%" <?php if($PGId=='%') echo "selected"; ?>>ทั้งหมด</option>
                        <?php
                        $result_productgroup = mysqli_query($link,"SELECT * FROM `productgroup` WHERE `PGStatus` = '1'");
                        while($productgroup = mysqli_fetch_array($result_productgroup)){
                            if($productgroup['PGId'] == $PGId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$productgroup['PGId']."\" ".$selected.">".$productgroup['PGName']."</option>";
                        }
                        ?>
                        
                    </select>
                </div>        
              
                <label class="control-label col-sm-1">ยี่ห้อ </label>
                <div class="col-sm-4">
                  <select class="chosen-select" name="BrandId" required>
                      <option value="%" <?php if($BrandId=='%') echo "selected"; ?>>ทั้งหมด</option>
                        <?php
                        $result_brand = mysqli_query($link,"SELECT * FROM `brand`");
                        while($brand = mysqli_fetch_array($result_brand)){
                            if($brand['BrandId'] == $BrandId){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"".$brand['BrandId']."\" ".$selected.">".$brand['BrandNameTH']."</option>\n";
                        }
                        ?>
                        
                  </select>
                </div>        
              </div>              
            <div class="form-group">
                <label class="control-label col-sm-3">ขนาด </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="PSize" value="<?php echo $PSize; ?>">
                </div>        
            
                <label class="control-label col-sm-1">สี </label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="PColor" value="<?php echo $PColor; ?>">
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">เรียง </label>
               
                <div class="col-sm-4">
                    <select class="form-control" name="order">
                        <option value="ASC">เก่าไปใหม่</option>
                        <option value="DESC">ใหม่ไปเก่า</option>
                    </select>
                </div> 
            </div>     
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4">ค้นหา</button>      
                  </div>                     

              </div>  
            </form>            
        </div>