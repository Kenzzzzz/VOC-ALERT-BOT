<?php
  require('../libs/database/connect-db.php');
  $manager_id = $_POST['managerId'];
  $clear_user_id = "UPDATE tbl_manager SET uid = NULL WHERE id = ".$manager_id;
  mysqli_query($conn, $clear_user_id) or trigger_error($conn->error."[$sql]");