<?php
  require('./libs/database/connect-db.php');
  require('./libs/utils/date_thai.php');
  require('./libs/utils/date_utils.php');

  // line access token
  $access_token = 'QPUPUnMzGhO//A8J2Qi1nmBXgEW89hciaaxNExeLVgxa8cjYtvnF9TZQF3TEjEOVA5HhS6dTRT2Tp4F0I3JhC0QWrQdmlBiL/6bhuazJI/juOxmvFx31NX7RWv9z19gbUZAdPIEuAURaHPy7TnDNkQdB04t89/1O/w1cDnyilFU=';

  // check holiday
  $todaytime = strtotime('today');
  $todaydate = date('Y-m-d', $todaytime);
  $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
  $holiday_list = mysqli_query($conn, $fetch_holiday);

  if(isWeekend($todaydate) || mysqli_num_rows($holiday_list) > 0){
      return;
  }

  // count complaint 
  $fetch_notify_office = "SELECT * FROM tbl_tmp_notify_office WHERE number_of_complaint > 0";
  $notify_office = mysqli_query($conn, $fetch_notify_office) or die($fetch_notify_office);
  if(mysqli_num_rows($notify_office) == 0){
    return;
  }

  // find maximum id
  $find_maximum_id = "SELECT * FROM tbl_individual_log";
  $log_object = mysqli_query($conn, $find_maximum_id) or die($find_maximum_id);
  $log_id = mysqli_num_rows($log_object);
  while($office = $notify_office->fetch_assoc()){
    $fetch_manager = "SELECT manager.office_id, office.office_name, manager.id AS manager_id, manager.name, manager.surname, manager.position, uid ".
                     " FROM tbl_manager manager ".
                     " JOIN tbl_pea_office office ON manager.office_id = office.id ".
                     "WHERE manager.office_id=".$office['office_id']." AND manager.status = 'A' ".
                      "AND office.status = 'A' AND uid IS NOT NULL";
                    //  "AND office.status = 'A'";
    $manager_object = mysqli_query($conn, $fetch_manager) or die($fetch_manager);
    if(mysqli_num_rows($manager_object) == 0){
      continue;
    }

    while($manager = $manager_object->fetch_assoc()){
      // auto increment with manual
      $log_id = $log_id + 1;
      // log push data
			$timestamp = date('Y-m-d H:i:s');
      $log_individual_notify = "INSERT INTO tbl_individual_log(id, manager_id, notify_timestamp) ".
                              "VALUES($log_id, ".$manager['manager_id'].", '$timestamp')";
      mysqli_query($conn, $log_individual_notify) or die($log_individual_notify);

      $log_id = mysqli_insert_id($conn);
      $messages = [
        "type"=> "text",
        "text"=> "Individual Alert :\n\nรายงานข้อร้องเรียนสถานะรอและกำลังดำเนินการมากกว่าเท่ากับ 7 วัน ของ ".$manager['office_name']." \n\nประจำวันที่ ".DateThai(date("Y-m-d"))." \n\nhttps://voc-bot.herokuapp.com/individual.php?office_id=".$manager['office_id']."&log_id=".$log_id
      ];

      $data = [
        'to' => $manager['uid'],
        'messages' => [$messages]
      ];

      $url = 'https://api.line.me/v2/bot/message/push';
      $post = json_encode($data);
      $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      $result = curl_exec($ch);
      curl_close($ch);
    }
  }