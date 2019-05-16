<?php
  require('./libs/database/connect-db.php');
  for($i=11;$i<=20;$i++){
    $insert_comment = "INSERT INTO tbl_test_log(comment, insert_time) VALUES('corn_job_$i', NOW())";
    mysqli_query($conn, $insert_comment);
  }