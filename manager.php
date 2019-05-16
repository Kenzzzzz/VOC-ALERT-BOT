<?php 
    require('./libs/database/connect-db.php');
    if(!isset($_GET['officeCode'])){
      header('Location: manager.php?officeCode=J');
      exit();
    }
    $officeCode = $_GET['officeCode'];
    if($officeCode == ""){
      header('Location: manager.php?officeCode=J');
      exit();
    }
    switch (strtoupper($officeCode)) {
      case "J":
        $officeName = "กฟต.1";
        break;
      case "K":
        $officeName = "กฟต.2";
        break;
      case "L":
          $officeName = "กฟต.3";
          break;
      default:
        break;
    }
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
            function editManagerId(managerId){
                var newwindow = window.open("edit_manager.php?manager_id="+managerId, "", "width=500,height=650,left=10,top=10,titlebar=no,toolbar=no,menubar=no,location=no,directories=no,status=no");
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
                <div class="col-lg-12">
                    <h2>รายชื่อผู้รับการแจ้งเตือนของ <?= $officeName ?></h2>
                        <div class="table-responsive">
                            <table class="table table-hover" id='manager_table'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ตำแหน่ง</th>
                                        <th>การไฟฟ้า</th>
                                        <th>ประเภท กฟฟ.</th>
                                        <th>สถานะ</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $select_manager = "SELECT manager.id AS id, manager.name, manager.surname, manager.position, manager.code, office.office_code, office.office_name, office.office_type, uid FROM tbl_manager manager JOIN tbl_pea_office office ON manager.office_id = office.id WHERE office.office_code LIKE '$officeCode%' AND manager.status = 'A' ORDER BY office.office_code ASC ";
                                        $results_manager = mysqli_query($conn, $select_manager);
                                        $count = 0;
                                        while($manager = mysqli_fetch_array($results_manager)){
                                    ?>
                                    <tr>
                                        <td><?=$count+1 ?></td>
                                        <td><?=$manager['name']." ".$manager['surname'] ?></td>
                                        <td><?=$manager['position'] ?></td>
                                        <td><?=$manager['office_name'] ?></td>
                                        <td><?=$manager['office_type'] ?></td>
                                        <td>
                                            <?=($manager['uid'] == NULL)?"<b style='color:red;'>ยังไม่ได้ลงทะเบียน</b>":"<b style='color:green;'>ลงทะเบียนแล้ว</b>" ?>
                                        </td>
                                        <td>
                                            <button class='btn btn-sm btn-secondary' onclick='editManagerId(<?=$manager['id']?>);'>Edit</button>
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
        });
    </script>
</html>