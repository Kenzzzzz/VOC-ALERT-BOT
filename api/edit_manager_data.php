<?php
    require('../libs/database/connect-db.php');

    $manager_id = $_POST['manager_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    $pea_office = $_POST['pea_office'];

    $update_manager = "UPDATE tbl_manager SET office_id = '$pea_office', name='$name', surname='$surname', position='$position' ".
                        "WHERE id=$manager_id";
    $result = mysqli_query($conn, $update_manager) or trigger_error($conn->error."[$sql]");

    echo 'แก้ไขข้อมูลผู้จัดการเรียบร้อยแล้ว';