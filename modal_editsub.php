<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");




if(isset($_POST['table'])){
    $id = $_POST['id'];
    $table = $_POST['table'];
    $field = $_POST['field'];
    $value = trim($_POST['value']);
    $url = $_POST['url'];

    mysqli_query($link, "UPDATE `$table` SET `$field` = '$value', `UpdateBy` = '$UpdateBy', `UpdateDate`= '$UpdateDate' WHERE `id`='$id'");
    header("Location: ".$url);    
    exit;    
}

$id = $_GET['id'];
$table = $_GET['table'];
$field = $_GET['field'];
$value = $_GET['value'];
$url = $_GET['url'];
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">แก้ไขรายการ</h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="modal_editsub.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="table" value="<?php echo $table; ?>">
                <input type="hidden" name="field" value="<?php echo $field; ?>">
                <input type="hidden" name="url" value="<?php echo $url; ?>">
              <div class="form-group">
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="value" value="<?php echo $value; ?>">                   
                </div>      
              </div>
                
              <div class="form-group">
                  <div class="col-sm-6 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary col-sm-4">บันทึก</button>      
                  </div>                     

              </div>  
            </form>            
        </div>