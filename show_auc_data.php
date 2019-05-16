<?php 
    require('./libs/database/connect-db.php');

    if(!isset($_GET['year']) || !isset($_GET['district'])){
      die('วิธีการเข้าดูรายงาน AUC Report ไม่ถูกต้อง');
    }

    $district_map = array(
      "J" => "กฟต.1", "K" => "กฟต.2", "L" => "กฟต.3"
    );

    $year = $_GET['year'];
    $district = $_GET['district'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>AUC Report</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
        <link href="https://fonts.googleapis.com/css?family=Pridi|Niramit|K2D|Bai+Jamjuree|Sarabun" rel="stylesheet">
        <style>
            body {
              padding-top: 20px;
            }

            h1, h2, h3, h4, h5 {
                  font-family: 'Sarabun', 'K2D', serif;
            }

            thead {
                font-family: 'Pridi', 'Niramit', serif;
            }

            tbody {
                font-family: 'Bai Jamjuree', serif;
            }
        </style>

        <!-- js -->
        <script src="./assets/jquery/jquery-3.3.1.min.js"></script>
        <script src="./assets/bootstrap/js/bootstrap.js"></script>
        <script src="./assets/blockUI/jquery.blockUI.js"></script>
        <script src="./assets/jqueryScrollTableBody/jqueryScrollTableBody.js"></script>
    </head>
    <body>
    <?php 
      $sql = "SELECT * FROM tbl_tran_auc auc JOIN tbl_auc_office office ON auc.office_code = office.office_code WHERE auc.office_code like '$district%' AND year = '$year' ORDER BY auc.office_code";
      $results = mysqli_query($conn, $sql);
      $result_count = mysqli_num_rows($results);
    ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <?php 
              if(substr( $year, 0, 1)==="<"){
                $desc = "ก่อนปี ".substr( $year, 2);
              }else{
                $desc = "ปี ".$year;
              }
            ?>
            <h2 class="text-center"><b>ข้อมูลงานคงค้างของ <?= $district_map[$district] ?> <?=$desc ?></b></h2>
            <h3 class="text-center">จำนวนทั้งสิ้น <?= $result_count ?> งาน</h3>
            <h5 class="text-center">(สถานะ 18 เม.ย. 2562)</h5>
            <div class="table-responsive">
              <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">กฟฟ.</th>
                    <th scope="col">องค์ประกอบ WBS</th>
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
                    if($result_count == 0){
                ?>
                      <tr>
                        <td colspan="11"><b style="font-size: 14px">-- ไม่มีงานคงค้างนาน --</b></td>
                      </tr>
                <?php 
                    }
                    $i = 0;
                    while($row = $results->fetch_assoc()){
                ?>
                  <tr>
                    <th scope="row"><?php echo ++$i; ?></th>
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
            $('table').scrollTableBody({ rowsToDisplay:8 });
        });
    </script>
</html>