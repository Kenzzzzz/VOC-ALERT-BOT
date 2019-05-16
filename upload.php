<?php 
    require('./libs/database/connect-db.php');
    
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        //$strHour= date("H",strtotime($strDate));
        //$strMinute= date("i",strtotime($strDate));
        //$strSeconds= date("s",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>Upload VOC xlsx file</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />

        <!-- js -->
        <script src="./assets/jquery/jquery-3.3.1.min.js"></script>
        <script src="./assets/bootstrap/js/bootstrap.js"></script>
        <script src="./assets/blockUI/jquery.blockUI.js"></script>
        <script src="./assets/jqueryScrollTableBody/jqueryScrollTableBody.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <form name="voc-form" id="voc-form" method="POST" class="text-center" enctype="multipart/form-data">
                        <div class="form-group">
                            <h1>อัพโหลด VOC FILE</h1>
                            <label for="vocfile">ไฟล์ VOC</label>
                            <input type="file" required name="vocfile" class="form-control-file btn btn-dark" id="vocfile" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="อัพโหลดข้อมูล">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <?php 
                        $fetch_lasted_timestamp = "SELECT MAX(file_upload_timestamp) AS lasted_time FROM tbl_log_voc_file";
                        $result = mysqli_query($conn, $fetch_lasted_timestamp);
                        $row = $result->fetch_assoc();
                    ?>
                    <h4>รายการข้อร้องเรียน<div style="float:right;padding-right:20px;">สถานะข้อมูลข้อร้องเรียน: <?php echo DateThai($row['lasted_time']); ?></div></h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">กฟข.</th>
                                    <!-- <th scope="col">รหัสสำนักงาน</th> -->
                                    <th scope="col">ชื่อสำนักงาน</th>
                                    <th scope="col">หมายเลขคำร้อง</th>
                                    <th scope="col">ชื่อผู้ร้องเรียน</th>
                                    <th scope="col">ประเภทข้อร้องเรียน</th>
                                    <!-- <th scope="col">หัวข้อย่อยข้อร้องเรียน</th> -->
                                    <th scope="col">สถานะข้อร้องเรียน</th>
                                    <th scope="col">จำนวนวัน</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $sql = "SELECT * FROM tbl_complaint WHERE complaint_status <> 'ปิด'";
                                $results = mysqli_query($conn, $sql);
                                $i = 0;
                                while($row = $results->fetch_assoc()){
                            ?>
                                <tr>
                                    <th scope="row"><?php echo ++$i; ?></th>
                                    <td><?php echo $row['main_office']; ?></td>
                                    <!-- <td><?php echo $row['office_code']; ?></td> -->
                                    <td><?php echo $row['office_name']; ?></td>
                                    <td><?php echo $row['complaint_id']; ?></td>
                                    <td><?php echo $row['complainant_name']; ?></td>
                                    <td><?php echo $row['complaint_type']; ?></td>
                                    <!-- <td><?php echo $row['sub_complaint_type']; ?></td> -->
                                    <td><?php echo $row['complaint_status']; ?></td>
                                    <td><?php echo $row['number_of_day']; ?></td>
                                </tr>
                            <?php 
                                }
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

            $('table').scrollTableBody({ rowsToDisplay:10 });

            $('[id="voc-form"]').submit(function(event){
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: './api/upload-voc-data.php',
                    method: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>Uploading xlsx file...</h3>' });
                    },
                    success: function(response) {
                        alert(response);
                    },
                    error: function(response){
                        console.log('[error]', response);
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
	<?php include("ga_analytics.php"); ?>
</html>