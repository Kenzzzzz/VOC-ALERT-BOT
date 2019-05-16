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
        <title>Upload AUC xlsx file</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
        <link href="https://fonts.googleapis.com/css?family=Pridi|Niramit|K2D" rel="stylesheet">
        <style>
            thead {
                font-family: 'Pridi', 'Niramit', serif;
            }

            tbody {
                font-family: 'K2D', serif;
            }
        </style>

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
                    <form name="auc-form" id="auc-form" method="POST" class="text-center" enctype="multipart/form-data">
                        <div class="form-group">
                            <h1>อัพโหลด AUC FILE</h1>
                            <label for="aucfile">ไฟล์ AUC</label>
                            <input type="file" required name="aucfile" class="form-control-file btn btn-dark" id="aucfile" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="อัพโหลดข้อมูล">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <!-- <th scope="col">ปี</th> -->
                                    <th scope="col">กฟฟ.</th>
                                    <th scope="col">WBS</th>
                                    <th scope="col">ชื่องาน</th>
                                    <th scope="col">ผู้ควบคุมงาน</th>
                                    <th scope="col">ตำแหน่ง</th>
                                    <th scope="col">สังกัด</th>
                                    <th scope="col">สถานะระบบ</th>
                                    <th scope="col">สถานะผู้ใช้</th>
                                    <th scope="col">มูลค่างาน (บาท)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $sql = "SELECT * FROM tbl_tran_auc auc JOIN tbl_auc_office office ON auc.office_code = office.office_code";
                                $results = mysqli_query($conn, $sql);
                                $i = 0;
                                while($row = $results->fetch_assoc()){
                            ?>
                                <tr>
                                    <th scope="row"><?php echo ++$i; ?></th>
                                    <!-- <td><?php echo $row['year']; ?></td> -->
                                    <td><?php echo $row['office_name'];?><br/><?php echo '('.$row['office_code'].')'; ?></td>
                                    <td><?php echo $row['wbs']; ?></td>
                                    <td><?php echo $row['job_name']; ?></td>
                                    <td><?php echo $row['job_director']; ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td><?php echo $row['affiliation']; ?></td>
                                    <td><?php echo $row['job_status']; ?></td>
                                    <td><?php echo $row['director_status']; ?></td>
                                    <td class="text-right">
                                        <b>
                                        <?php 
                                            if($row['value'] == 0.00){
                                                echo "-";
                                            }else{
                                                echo number_format($row['value'], 2);
                                            }
                                        ?>
                                        </b>
                                    </td>
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

            $('[id="auc-form"]').submit(function(event){
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: './api/upload-auc-data.php',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    beforeSend: function(){
                        $.blockUI({ message:'<h3>Uploading xlsx file...</h3>' });
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response){
                        console.log('[error]', response);
                    },
                    complete: function() {
                        $.unblockUI();
                        // location.reload();
                    }
                });
                return false;
            });
        });
    </script>
</html>