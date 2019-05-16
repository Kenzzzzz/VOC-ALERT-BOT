<?php 
  require('libs/database/connect-db.php');
  $manager_id = $_GET['manager_id'];
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

            function clearUserId(managerId){
                if(!$('#uid').val()){
                    alert('สามารถเคลียร์ค่า User ID ได้เนื่องจากไม่มีข้อมูล');
                    return;
                }

                $.ajax({
                    url: "api/clear_user_id.php",
                    method: "POST",
                    data: {
                        managerId: managerId
                    },
                    async: true,
                    cache: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>กำลังลบ User id...</h3>' });
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
        <?php   
            $fetch_manager = "SELECT * FROM tbl_manager WHERE id = $manager_id";
            $manager_object = mysqli_query($conn, $fetch_manager);
            $manager = $manager_object->fetch_assoc();
            $office_id = $manager['office_id'];
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2 style='text-align:center'>แก้ไขข้อมูล</h2>
                    <form name="edit-regis-form" id="edit-regis-form" method="POST">
                        <div class="form-group">
                            <label for="name">ชื่อ</label>
                            <input type="text" class="form-control" id="name" placeholder="กรอกชื่อจริง" value='<?=$manager['name']?>' required>
                        </div>
                        <div class="form-group"> 
                            <label for="surname">นามสกุล</label>
                            <input type="text" class="form-control" id="surname" placeholder="กรอกนามสกุล" value='<?=$manager['surname']?>' required>
                        </div>
                        <div class="form-group">
                            <label for="position">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="position" placeholder="กรอกตำแหน่งพร้อมระดับของท่าน" value='<?=$manager['position']?>' required>
                        </div>
                        <div class="form-group">
                            <label for="pea_office">การไฟฟ้าสังกัด</label>
                            <select class="form-control" name="pea_office" id="pea_office">
                            <?php 
                                $option_html = "";
                                $fetch_office = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND office_code LIKE '%101' ORDER BY office_code";
                                $office_result = mysqli_query($conn, $fetch_office);
                                while($office = $office_result->fetch_assoc()){
                                    $selected = '';
                                    if($office['id'] == $office_id){
                                        $selected = 'selected';
                                    }
                                    $option_html .= "<option $selected value='".$office['id']."'>".$office['office_code'].":".$office['office_name']."  (".$office['office_type'].")</option>";
                                    
                                    $fetch_branch = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND parent_level_1 = ".$office['id']." ORDER BY office_code";
                                    $branch_result = mysqli_query($conn, $fetch_branch);
                                    while($branch = $branch_result->fetch_assoc()){
                                        if($branch['office_type'] == "กฟย."){
                                            continue;
                                        }
                                        if($branch['id'] == $office_id){
                                            $selected = 'selected';
                                        }else{
                                            $selected = '';
                                        }
                                        $option_html .= "<option $selected value='".$branch['id']."'>&nbsp;&nbsp;&nbsp;".$branch['office_code'].":".$branch['office_name']."  (".$branch['office_type'].")</option>";
                                    }
                                }
                                echo $option_html;
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="regisCode">Register Code</label>
                            <input type="text" class="form-control" id="regisCode" value='<?=$manager['code']?>' readonly/>
                        </div>
                        <div class="form-group input-group mb-3">
                            <input type="text" id='uid' class="form-control" value='<?=(isset($manager['uid'])?$manager['uid']:"ยังไม่ได้ยืนยันการใช้งาน")?>' readonly>
                            <div class="input-group-append">
                                <?php 
                                    if(isset($manager['uid'])){
                                ?>
                                <button class="btn btn-outline-secondary" onclick='clearUserId(<?=$manager['id']?>);' type="button">CLEAR</button>
                                <?php
                                    }else{
                                ?>
                                        <button class="btn btn-outline-secondary" type="button" disabled>CLEAR</button>
                                <?php
                                    }
                                    mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="ปรับปรุง" />
                            <input class="btn btn-danger" onclick='window.opener.location.reload();window.close();' type="button" value="ปิด" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $('[id="edit-regis-form"]').submit(function(event){
                event.preventDefault();
                var formData = new FormData();
                formData.append('manager_id', '<?=$manager_id ?>');
                formData.append('name', $("#name").val());
                formData.append('surname', $("#surname").val());
                formData.append('position', $("#position").val());
                formData.append('pea_office', $("#pea_office").val());
                $.ajax({
                    url: './api/edit_manager_data.php',
                    method: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>กำลังแก้ไขข้อมูล...</h3>' });
                    },
                    success: function(response) {
                        alert(response);
                    },
                    complete: function() {
                        $.unblockUI();
                        location.reload();
                    }
                });
                return false;
            });
        </script>
    </body>
</html>