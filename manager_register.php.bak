<?php 
    require('./libs/database/connect-db.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Manager register</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
        
        <!-- js -->
        <script src="./assets/jquery/jquery-3.3.1.min.js"></script>
        <script src="./assets/bootstrap/js/bootstrap.js"></script>
        <script src="./assets/blockUI/jquery.blockUI.js"></script>
        <script src="./assets/jqueryScrollTableBody/jqueryScrollTableBody.js"></script>
        <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).val()).select();
                document.execCommand("copy");
                $temp.remove();
                alert('คัดลอกไปยัง Clipboard เรียบร้อยแล้ว');
            }

            function editManagerId(managerId){
                var newwindow = window.open("edit_manager.php?manager_id="+managerId, "", "width=500,height=650,left=10,top=10,titlebar=no,toolbar=no,menubar=no,location=no,directories=no,status=no");
                if (window.focus) {
                    newwindow.focus();
                }
                return false;
            }

            function deleteManagerId(userId, name){
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

            function view_manager(officeId){
                var newwindow = window.open("view_manager.php?office_id="+officeId, "รายชื่อผู้จัดการ", "width=900,height=400,left=10,top=10,titlebar=no,toolbar=no,menubar=no,location=no,directories=no,status=no");
                if (window.focus) {
                    newwindow.focus();
                }
                return false;
            }
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <form name="manager-regis-form" id="manager-regis-form" method="POST">
                        <h2>ลงทะเบียนผู้จัดการ</h2>
                        <div class="form-group">
                            <label for="name">ชื่อ</label>
                            <input type="text" class="form-control" id="name" placeholder="กรอกชื่อจริง" required>
                        </div>
                        <div class="form-group">
                            <label for="surname">นามสกุล</label>
                            <input type="text" class="form-control" id="surname" placeholder="กรอกนามสกุล" required>
                        </div>
                        <div class="form-group">
                            <label for="position">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="position" placeholder="กรอกตำแหน่งพร้อมระดับของท่าน" required>
                        </div>
                        <div class="form-group">
                            <label for="pea_office">การไฟฟ้าสังกัด</label>
                            <select class="form-control" name="pea_office" id="pea_office">
                            <?php 
                                $option_html = "";
                                $fetch_office = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND office_code LIKE '%101' ORDER BY office_code";
                                $office_result = mysqli_query($conn, $fetch_office);
                                while($office = mysqli_fetch_array($office_result)){
                                    $option_html .= "<option value='".$office['id']."'>".$office['office_code'].":".$office['office_name']."  (".$office['office_type'].")</option>";
                                    
                                    $fetch_branch = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND parent_level_1 = ".$office['id']." ORDER BY office_code";
                                    $branch_result = mysqli_query($conn, $fetch_branch);
                                    while($branch = mysqli_fetch_array($branch_result)){
                                        if($branch['office_type'] == "กฟย."){
                                            continue;
                                        }
                                        $option_html .= "<option value='".$branch['id']."'>&nbsp;&nbsp;&nbsp;".$branch['office_code'].":".$branch['office_name']."  (".$branch['office_type'].")</option>";
                                    }
                                }
                                echo $option_html;
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="regisCode">Register Code</label>
                            <input type="text" class="form-control" id="regisCode" readonly/>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="สมัคร" />
                        </div>
                    </form>
                </div>
                <div class="col-lg-8">
                    <!-- <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="district_1-tab" data-toggle="tab" href="#south_district_1" role="tab" aria-selected="true">กฟต.1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="district_2-tab" data-toggle="tab" href="#south_district_2" role="tab" aria-selected="false">กฟต.2</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="district_3-tab" data-toggle="tab" href="#south_district_3" role="tab" aria-selected="false">กฟต.3</a>
                        </li>
                    </ul> -->
                    <!-- <div class="tab-content"> -->
                    <?php 
                        // $tabs_name = array("J"=>"south_district_1","K"=>"south_district_2","L"=>"south_district_3");
                        // foreach($tabs_name as $key=>$district){
                    ?>
                        <!-- <div class="tab-pane fade <?php //if($key == "J") echo 'show active'; ?>" id="<?php //echo $district; ?>" role="tabpanel"> -->
                            <table class="table table-sm table-hover table-borderless">
                                <thead class="thead-light">
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>รหัสการไฟฟ้า</th>
                                        <th>ชื่อการไฟฟ้า</th>
                                        <th>จำนวนเรื่อง</th>
                                        <th>ผู้รับผิดชอบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $fetch_office_district = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND office_code LIKE '%101' ORDER BY office_code";
                                    $office_results = mysqli_query($conn, $fetch_office_district);
                                    $count = 0;
                                    while($office = mysqli_fetch_array($office_results)){
                                        // count complaint
                                        $fetch_count_complaint= "SELECT * FROM tbl_complaint WHERE office_name = '".$office['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 10";
                                        $complaint_result = mysqli_query($conn, $fetch_count_complaint);
                                        $count_complaint = mysqli_num_rows($complaint_result);
                                        // count manager
                                        $fetch_count_manager = "SELECT * FROM tbl_manager WHERE office_id = ".$office['id']." AND status = 'A'";
                                        $manager_result = mysqli_query($conn, $fetch_count_manager);
                                        $count_manager = mysqli_num_rows($manager_result);
                                ?>
                                    <tr style="background: #C0C0C0;">
                                        <!-- <td><?=$count+1 ?></td> -->
                                        <td align='center'><?=$office['office_code'] ?></td>
                                        <td><b><?=$office['office_name'] ."  (".$office['office_type'].")" ?></b></td>
                                        <td><?=($count_complaint==0)?"<b>ไม่มีข้อร้องเรียน</b>":"<i style='color:red;'>".$count_complaint." เรื่อง</i>" ?> </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary <?=($count_manager==0)?"disabled":"" ?>" onclick="view_manager(<?=$office['id'] ?>);">ผู้รับการแจ้งเตือน (<?= $count_manager?>)</button>
                                        </td>
                                    </tr>
                                    <?php 
                                        $count++;
                                        $fetch_branch = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND parent_level_1 = ".$office['id']." ORDER BY office_code";
                                        $branch_results = mysqli_query($conn, $fetch_branch);
                                        while($branch = mysqli_fetch_array($branch_results)){
                                            // count complaint
                                            $fetch_count_complaint= "SELECT * FROM tbl_complaint WHERE office_name = '".$branch['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 10";
                                            $complaint_result = mysqli_query($conn, $fetch_count_complaint);
                                            $count_complaint = mysqli_num_rows($complaint_result);
                                            // count manager
                                            $fetch_count_manager = "SELECT * FROM tbl_manager WHERE office_id = ".$branch['id']." AND status = 'A'";
                                            $manager_result = mysqli_query($conn, $fetch_count_manager);
                                            $count_manager = mysqli_num_rows($manager_result);
                                    ?>
                                    <tr style="background: #E0E0E0;">
                                        <!-- <td><?=$count+1 ?></td> -->
                                        <td align='center'><?=$branch['office_code'] ?></td>
                                        <td><?="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$branch['office_name']."  (".$branch['office_type'].")" ?></td>
                                        <td><?=($count_complaint==0)?"<b>ไม่มีข้อร้องเรียน</b>":"<i style='color:red;'>".$count_complaint." เรื่อง</i>" ?> </td>
                                        <td>
                                        <?php 
                                            if($branch['office_type'] == "กฟส."){
                                        ?>
                                            <button class="btn btn-sm btn-secondary <?=($count_manager==0)?"disabled":"" ?>" onclick="view_manager(<?=$branch['id'] ?>);">ผู้รับการแจ้งเตือน (<?=$count_manager ?>)</button>
                                        <?php 
                                            }
                                        ?>
                                        </td>
                                    </tr>
                                    <?php 
                                                $count++;
                                                $fetch_sub_branch = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND parent_level_1 = ".$branch['id']." ORDER BY office_code";
                                                $sub_branch_results = mysqli_query($conn, $fetch_sub_branch);
                                                while($sub_branch = mysqli_fetch_array($sub_branch_results)){
                                                    $fetch_count_complaint= "SELECT * FROM tbl_complaint WHERE office_name = '".$sub_branch['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 10";
                                                    $complaint_result = mysqli_query($conn, $fetch_count_complaint);
                                                    $count_complaint = mysqli_num_rows($complaint_result);
                                    ?>
                                    <tr style="background:#F8F8F8;">
                                        <!-- <td><?=$count+1 ?></td> -->
                                        <td align='center'><?=$sub_branch['office_code'] ?></td>
                                        <td><?="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$sub_branch['office_name']."  (".$sub_branch['office_type'].")" ?></td>
                                        <td><?=($count_complaint==0)?"<b>ไม่มีข้อร้องเรียน</b>":"<i style='color:red;'>".$count_complaint." เรื่อง</i>" ?> </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                                $count++;
                                                }
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <!-- </div> -->
                        <?php 
                            // }
                        ?>
                    <!-- </div> -->
                </div>
                <hr />
                <br/>
                <div class="col-lg-12">
                    <h2>รายชื่อผู้รับการแจ้งเตือน</h2>
                    <!-- <form method='post' class='form-inline'> -->
                        <!-- <b>แสดงรายชื่อตามเขต:</b> 
                        <div class="form-group">
                            <select name='district' class="form-control" id="district">
                                <option value='all'>ทั้งหมด</option>
                                <option value='J'>กฟต.1</option>
                                <option value='K'>กฟต.2</option>
                                <option value='L'>กฟต.3</option>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <input type="text" class="form-control" id="keyword" placeholder="ชื่อ.., การไฟฟ้า...">
                            <input type='submit' class='btn' value='ค้นหา'/>
                        </div>
                        <br class='clearfix'/> -->
                        <div class="table-responsive">
                            <table class="table table-hover" id='manager_table'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ตำแหน่ง</th>
                                        <th>กฟข.</th>
                                        <th>การไฟฟ้า</th>
                                        <th>สถานะ</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $select_manager = "SELECT manager.id AS id, manager.name, manager.surname, manager.position, manager.code, office.office_code, office.office_name, uid FROM tbl_manager manager JOIN tbl_pea_office office ON manager.office_id = office.id WHERE manager.status = 'A' ORDER BY office.office_code ASC ";
                                        $results_manager = mysqli_query($conn, $select_manager);
                                        $count = 0;
                                        while($manager = mysqli_fetch_array($results_manager)){
                                            ?>
                                    <tr>
                                        <td><?=$count+1 ?></td>
                                        <td><?=$manager['name']." ".$manager['surname'] ?></td>
                                        <td><?=$manager['position'] ?></td>
                                        <td>
                                            <?php 
                                                switch(substr($manager['office_code'], 0, 1)){
                                                    case "J":
                                                        echo "กฟต.1";
                                                        break;
                                                    case "K":
                                                        echo "กฟต.2";
                                                        break;
                                                    case "L":
                                                        echo "กฟต.3";
                                                        break;
                                                    default:
                                                        break;
                                                } 
                                            ?>
                                        </td>
                                        <td><?=$manager['office_name'] ?></td>
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
                                            <button class='btn btn-sm btn-secondary' onclick='editManagerId(<?=$manager['id']?>);'>Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteManagerId(<?=$manager['id']?>, '<?=$manager['name']." ".$manager['surname'] ?>')">Delete</button>
                                        </td>
                                    </tr>
                                    <?php 
                                            $count++;
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    <!-- </form> -->
                    <?php 
                        mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(function(){
            $('table').DataTable();

            $.ajax({
                type: "GET",
                url: './api/find_regis_code.php',
                dataType: 'text',
                success: function(response) {
                    $("#regisCode").val(response);
                }
            });

            $('[id="manager-regis-form"]').submit(function(event){
                event.preventDefault();
                var formData = new FormData();
                formData.append('name', $("#name").val());
                formData.append('surname', $("#surname").val());
                formData.append('position', $("#position").val());
                formData.append('pea_office', $("#pea_office").val());
                formData.append('regisCode', $("#regisCode").val());
                $.ajax({
                    url: './api/add_manager_data.php',
                    method: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>กำลังนำเข้าสู่ระบบ...</h3>' });
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
        });
    </script>
</html>