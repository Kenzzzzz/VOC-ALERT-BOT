<?php
    require('../libs/database/connect-db.php');

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    $pea_office = $_POST['pea_office'];
    $regisCode = $_POST['regisCode'];

    $insert_manager = "INSERT INTO tbl_manager(office_id, name, surname, position, code) ".
                        "VALUES($pea_office, '$name', '$surname', '$position', '$regisCode')";
    $result = mysqli_query($conn, $insert_manager) or trigger_error($conn->error."[$sql]");

    echo 'เพิ่มข้อมูลผู้จัดการเรียบร้อยแล้ว';
    mysqli_close($conn);