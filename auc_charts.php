<?php 
    require('./libs/database/connect-db.php');

    if(!isset($_GET['year']) || !isset($_GET['district'])){
      die('วิธีการเข้าดูรายงาน AUC Report ไม่ถูกต้อง');
    }

    $district_map = array(
      "J" => "การไฟฟ้าเขต 1 ภาคใต้ จ.เพชรบุรี", 
      "K" => "การไฟฟ้าเขต 2 ภาคใต้ จ.นครศรีธรรมราช", 
      "L" => "การไฟฟ้าเขต 3 ภาคใต้ จ.ยะลา"
    );

    $year = $_GET['year'];
    $district = $_GET['district'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <title>AUC Charts</title>
        <!-- css -->
        <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
        <link href="https://fonts.googleapis.com/css?family=Pridi|Niramit|K2D|Bai+Jamjuree|Sarabun" rel="stylesheet">
        <style>
            body {
              padding-top: 20px;
            }

            h1, h2, h3, h4, h5, a, label {
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
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </head>
    <body>
      <?php
        $sql = "SELECT * FROM tbl_auc_goal WHERE district = '$district' AND year = '$year' ".
               "ORDER BY CASE ".
               "          WHEN type = 'closed' THEN 1 ".
               "          WHEN type = 'goal' THEN 2 ".
               "          ELSE 3 ".
               "        END ASC ";
        $results = mysqli_query($conn, $sql);

        $row = $results->fetch_assoc();
        $closed_job = $row['amount_job'];

        $row = $results->fetch_assoc();
        $goal_job = $row['amount_job'];
        $percentage_closed_job = number_format(($closed_job/$goal_job)*100, 2, ".", "");
      ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <h4 class="text-center">
              <b>กราฟแสดงความสัมพันธ์จำนวนงานที่ปิดกับเป้าหมาย ปี <?=$year ?> <?=$district_map[$district]?>
              </b><br/>
              <label>ข้อมูลสถานะ 18 เม.ย. 2562</label>
            </h4><br/>
            <h5 class="text-center">
              ปิดงานก่อสร้างได้ <?=$percentage_closed_job ?> %<br/>
              จำนวนงานคงเหลือ <?=$goal_job - $closed_job ?> งาน
            </h5>
            <div class="text-center">
              <a class="btn btn-primary" href="show_auc_data.php?year=<?=$year?>&district=<?=$district?>">ดูรายละเอียดงานคงค้าง</a>
            </div><br/><br/>
            <div id="container" style="width:100%; height:400px;"></div>
          </div>
        </div>
      </div>
    </body>
    <script>
        $(function(){
          var myChart = Highcharts.chart('container', {
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              tooltip: {
                  shared: true
              },
              xAxis: {
                  categories: ['<?=$district_map[$district]?>']
              },
              yAxis: {
                  title: {
                      text: 'จำนวนงาน',
                      style: {
                        fontSize: 16,
                        fontFamily: 'K2D'
                      }
                  }
              },
              series: [{
                  name: 'จำนวนงานที่ปิดแล้ว',
                  data: [<?=$closed_job ?>]
              }, {
                  name: 'จำนวนงานตามเป้าหมาย',
                  data: [<?=$goal_job ?>]
              }],
              legend: {
                itemStyle: {
                    font: '12pt K2D',
                    color: 'black',
                },
                itemHoverStyle:{
                    color: 'gray'
                }   
              },
              plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        formatter: function(){
                            return (this.y).toLocaleString('en') + " งาน"
                        }
                    }
                  }
              }
          });
        });
    </script>
</html>