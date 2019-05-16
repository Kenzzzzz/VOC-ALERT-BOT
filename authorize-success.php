<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php
    require('./libs/database/connect-db.php');
    
    $name = $_POST["name"];
    $lastname = $_POST["lastname"];
    $position = $_POST["position"];
    $code = $_POST["password"];
    echo "ลงทะเบียนเรียนร้อย รหัสยืนยันตัวของท่านคือ     ".$code;
    
    $sql_insert ="INSERT INTO tbl_authorize(name,lastname,position,code,status) VALUES('$name','$lastname','$position','$code','A')";
    mysqli_query($conn,$sql_insert);
?>
</head>

<body>
</body>
</html>
