<?php
  function clearOfficeNotify($conn){
    $sql = "DELETE FROM tbl_tmp_notify_office";
    mysqli_query($conn, $sql);
  }

  function logOfficeToNotify($conn){
    $count_main_complaint = [];
    $fetch_main_branch = "SELECT * FROM tbl_pea_office WHERE office_code LIKE '%101' AND status = 'A'";
    $main_branch_results = mysqli_query($conn, $fetch_main_branch);
    while($main_branch = $main_branch_results->fetch_assoc()){

      // count complaint by main branch
      $count_main_complaint[$main_branch['office_code']] = [];
      $count_main_complaint[$main_branch['office_code']]['main'] = [];
      $count_main_complaint[$main_branch['office_code']]['main'][$main_branch['id']] = 0;

      // fetch complaint by main_branch
      $fetch_complaint = "SELECT * FROM tbl_complaint WHERE office_name = '".$main_branch['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 7";
      $complaint_results = mysqli_query($conn, $fetch_complaint);
      $count_main_complaint[$main_branch['office_code']]['main'][$main_branch['id']] += mysqli_num_rows($complaint_results);

      $fetch_branch = "SELECT * FROM tbl_pea_office WHERE parent_level_1 = ".$main_branch['id'];
      $branch_results = mysqli_query($conn, $fetch_branch);

      $count_main_complaint[$main_branch['office_code']]['branch'] = [];
      while($branch = $branch_results->fetch_assoc()){
        // initial complaint's count
        $count_main_complaint[$main_branch['office_code']]['branch'][$branch['id']] = 0;

        $fetch_complaint_branch = "SELECT * FROM tbl_complaint WHERE office_name ='".$branch['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 7";
        $complaint_results = mysqli_query($conn, $fetch_complaint_branch);
        $count_main_complaint[$main_branch['office_code']]['main'][$main_branch['id']] += mysqli_num_rows($complaint_results);
        $count_main_complaint[$main_branch['office_code']]['branch'][$branch['id']] += mysqli_num_rows($complaint_results);

        $fetch_sub_pea = "SELECT * FROM tbl_pea_office WHERE parent_level_1 ='".$branch['id']."'";
        $sub_pea_results = mysqli_query($conn, $fetch_sub_pea);
        while($sub_pea = $sub_pea_results->fetch_assoc()){
          $fetch_complaint_sub_branch = "SELECT * FROM tbl_complaint WHERE office_name ='".$sub_pea['office_name']."' AND complaint_status <> 'ปิด' AND number_of_day >= 7";
          $complaint_results = mysqli_query($conn, $fetch_complaint_sub_branch);
          $count_main_complaint[$main_branch['office_code']]['main'][$main_branch['id']] += mysqli_num_rows($complaint_results);
          $count_main_complaint[$main_branch['office_code']]['branch'][$branch['id']] += mysqli_num_rows($complaint_results);
        }
      }
    }

    $count_office = 1;
    foreach($count_main_complaint as $key => $value){
      foreach($value["main"] as $office_id=>$complaint_count){
        $log_main_office = "INSERT INTO tbl_tmp_notify_office VALUES ($count_office, $office_id, $complaint_count);";
        mysqli_query($conn, $log_main_office);
        $count_office++;
      }
      foreach($value["branch"] as $office_id=>$complaint_count){
        $filter_branch = "SELECT * FROM tbl_pea_office WHERE id=".$office_id;
        $office_object = mysqli_query($conn, $filter_branch);
        $office = $office_object->fetch_assoc();
        if($office['office_type'] == "กฟย."){
          continue;
        }
        $log_branch_office = "INSERT INTO tbl_tmp_notify_office VALUES ($count_office, $office_id, $complaint_count);";
        mysqli_query($conn, $log_branch_office);
        $count_office++;
      }
    }
  }