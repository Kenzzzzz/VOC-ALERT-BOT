<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>INDIVIDUAL_NOTIFY</title>
    </head>
<body>
<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');

    function push($office_name, $uid, $log_id){
        $access_token = 'QPUPUnMzGhO//A8J2Qi1nmBXgEW89hciaaxNExeLVgxa8cjYtvnF9TZQF3TEjEOVA5HhS6dTRT2Tp4F0I3JhC0QWrQdmlBiL/6bhuazJI/juOxmvFx31NX7RWv9z19gbUZAdPIEuAURaHPy7TnDNkQdB04t89/1O/w1cDnyilFU=';
        $messages = [
            "type"=> "text",
            "text"=> "Individual alert :\n\nรายงานข้อร้องเรียนสถานะรอและกำลังดำเนินการมากกว่าเท่ากับ 10 วัน\n\nประจำวันที่ ".DateThai(date("Y-m-d"))." ของ $office_name \n\nhttps://voc-bot.herokuapp.com/individual.php?office_name=".$office_name."&log_id=".$log_id
        ];
        
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = [
            'to' => $uid,
            'messages' => [$messages]
        ];
        
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
    
    $sql_select_officename = "SELECT office_name FROM tbl_complaint WHERE (complaint_status LIKE '%กำลังดำเนินการ%' OR complaint_status LIKE '%รอดำเนินการ%') AND (number_of_day >= 10) GROUP BY office_name";
    $officename_list = mysqli_query($conn, $sql_select_officename);
    while($obj_office_name = mysqli_fetch_array($officename_list)){	
        $sql_office_id = "SELECT * FROM tbl_pea_office WHERE office_name LIKE '%".$obj_office_name["office_name"]."%'";
        $query_office_id = mysqli_query($conn, $sql_office_id);
        while($obj_office_id = mysqli_fetch_array($query_office_id)){
            $sql_manager = "SELECT office_name, uid, manager.id AS manager_id, manager.name FROM tbl_manager manager JOIN tbl_pea_office office ON manager.office_id = office.id WHERE office_id = ".$obj_office_id["id"]." AND manager.status = 'A' ";
            $query_manager = mysqli_query($conn,$sql_manager);
            while($obj_manager = mysqli_fetch_array($query_manager)){
                $sql_log_notify = "INSERT INTO tbl_individual_log(manager_id, notify_timestamp) VALUES(".$obj_manager["manager_id"].", NOW())";
                mysqli_query($conn, $sql_log_notify);
                $lasted_id = mysqli_insert_id($conn);
                echo push($obj_manager["office_name"], $obj_manager["uid"], $lasted_id);
            }
        }
    }



?>
</body>
</html>