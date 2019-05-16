<?php
  require('../libs/database/connect-db.php');
  $userId = $_POST['userId'];
  $del_userId = "UPDATE tbl_manager SET status = 'I' WHERE id = ".$userId;
  mysqli_query($conn, $del_userId) or trigger_error($conn->error."[$sql]");