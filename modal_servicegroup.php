<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

if(isset($_POST['save'])){
    $SGId = trim($_POST['SGId']);
    $SGParentId = trim($_POST['SGParentId']);
    $SGName = trim($_POST['SGName']);
    
    mysqli_query($link, "UPDATE `servicegroup` SET `SGName` = '$SGName' WHERE  `SGId` = '$SGId'");
    header("Location: set_servicegroup.php?SGId=$SGParentId");
    exit;
}


?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">แก้ไข</h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_servicegroup.php">
                <?php
                $SGId = trim($_GET['SGId']);
                $result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGId` = '$SGId' ");
                $sg = mysqli_fetch_array($result);
                
                ?>
                
                <input type="hidden" name="SGId" value="<?php echo $SGId; ?>">
                <input type="hidden" name="SGParentId" value="<?php echo $sg['SGParentId']; ?>"> 
                
                <div class="form-group">
                    <label class="control-label col-sm-3">รายการ</label>
                    <div class="col-sm-6">
                        
                        <input type="text" class="form-control" name="SGName" value="<?php echo $sg['SGName']; ?>" required autofocus>
                    </div>          
                </div>
                <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4" name="save">บันทึก</button>      
                  </div>
               </div> 
                
            </form>
            
        </div>
