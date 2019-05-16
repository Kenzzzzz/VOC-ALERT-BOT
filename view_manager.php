<?php 
  require('libs/database/connect-db.php');
  $officeId = $_GET['office_id'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Manager register</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
        
        <!-- js -->
        <script src="./assets/jquery/jquery-3.3.1.min.js"></script>
        <script src="./assets/bootstrap/js/bootstrap.js"></script>
        <script src="./assets/blockUI/jquery.blockUI.js"></script>
        <script src="./assets/jqueryScrollTableBody/jqueryScrollTableBody.js"></script>
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).val()).select();
                document.execCommand("copy");
                $temp.remove();
                alert('คัดลอกไปยัง Clipboard เรียบร้อยแล้ว');
            }

            function deleteUserId(userId, name){
                if(!confirm("ต้องการลบข้อมูลของ "+name+" หรือไม่")){
                    return;
                }

                $.ajax({
                    url: "api/delete_user.php",
                    method: "POST",
                    data: {
                        userId: userId
                    },
                    async: true,
                    cache: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>deleting...</h3>' });
                    },
                    error: function(response){
                        console.log('[error]', response);
                    },
                    complete: function() {
                        $.unblockUI();
                        location.reload();
                    }
                })
            }
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2 style='text-align:center'>รายชื่อผู้รับการแจ้งเตือนแบบ Individual Alert</h2>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>ตำแหน่ง</th>
                                    <th>การไฟฟ้า</th>
                                    <th>รหัสสมัคร</th>
                                    <th>สถานะ</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $select_manager = "SELECT manager.id AS id, manager.name, manager.surname, manager.position, manager.code, office.office_name, uid FROM tbl_manager manager JOIN tbl_pea_office office ON manager.office_id = office.id WHERE manager.office_id = $officeId AND manager.status ='A'";
                                $manager_results = mysqli_query($conn, $select_manager);
                                if(mysqli_num_rows($manager_results) == 0){
                              ?>
                                <tr>
                                  <td style="text-align:center;" colspan="7">ไม่มีข้อมูลผู้จัดการ</td>
                                </tr>
                              <?php
                                }
                                $count = 0;
                                while($manager = mysqli_fetch_array($manager_results)){
                              ?>
                              <tr>
                                <td><?=$count+1 ?></td>
                                <td><?=$manager['name']." ".$manager['surname'] ?></td>
                                <td><?=$manager['position'] ?></td>
                                <td><?=$manager['office_name'] ?></td>
                                <td><?=$manager['code'] ?></td>
                                <td>
                                    <?=($manager['uid'] == NULL)?"ยังไม่ได้ลงทะเบียน":"ลงทะเบียนแล้ว" ?>
                                </td>
                                <td>
                                    <?php 
                                        if($manager['uid'] != NULL){
                                            $hidden_uid = "uid-".$manager['id'];
                                    ?>
                                        <button class="btn btn-sm btn-default" onclick="copyToClipboard('#<?=$hidden_uid ?>')">Copy uid</button>
                                        <input type="hidden" name="<?=$hidden_uid ?>" id="<?=$hidden_uid ?>" value="<?=$manager['uid'] ?>" />
                                    <?php 
                                        }
                                    ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUserId(<?=$manager['id']?>, '<?=$manager['name']." ".$manager['surname'] ?>')">delete</button>
                                </td>
                              </tr>
                              <?php
                              $count++;
                                }
                                mysqli_close($conn);
                              ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(function(){
            $('table').scrollTableBody({ rowsToDisplay:5 });
        });
    </script>
</html>