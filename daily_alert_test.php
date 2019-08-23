<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');
    require('./libs/utils/messages.php');
    
    define('LINE_API',"https://notify-api.line.me/api/notify");
    $token = "yOgiRn8Z9opjibqhlTV70UQ4SWMQAedCxkvoFyiEaGq"; //ใส่Token ที่copy เอาไว้
    $str; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
    
    $todaytime = strtotime('today');
    $todaydate = date('Y-m-d', $todaytime);
    $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
    $holiday_list = mysqli_query($conn, $fetch_holiday);

    if(isWeekend($todaydate) || mysqli_num_rows($holiday_list) > 0){
        return;
    }

    $fetch_group_list = "SELECT group_id FROM tbl_line_group WHERE status = 'A'";
    $group_list = mysqli_query($conn, $fetch_group_list);

    $fetch_existing_complaint = "SELECT main_office, COUNT(main_office) AS count_complaint ".
                                "FROM tbl_complaint ".
                                "WHERE number_of_day>='10' AND complaint_status <> 'ปิด' ".
                                "GROUP BY main_office ".
                                "HAVING COUNT(main_office) > 0 ".
                                "ORDER BY main_office ASC";
    $complaint_list = mysqli_query($conn, $fetch_existing_complaint);
    if(mysqli_num_rows($complaint_list) > 0){
        $messages = getBubbleMessages($conn, DateThai(date("Y-m-d")), $complaint_list);
        $str = "รายการข้อร้องเรียน\rประจำวันที่".DateThai(date("Y-m-d"))."\n".getMainOfficeByOfficeCode($officeCode)."\nhttps://vocbot-region2.herokuapp.com/south.php?NUMBER=@10";
        $res = notify_message($str,$token);
        print_r($res);
    } else {
        $messages = [
            "type"=> "text",
            "text"=> "Daily Alert :\n\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 10 วัน ในวันที่ ".DateThai(date("Y-m-d"))
        ];
        $str = "รายการข้อร้องเรียน\rประจำวันที่".DateThai(date("Y-m-d"))."\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 10 วัน\nhttps://vocbot-region2.herokuapp.com/south.php?NUMBER=@10";
        $res = notify_message($str,$token);
        print_r($res);
    }

    function notify_message($message,$token){
    $queryData = array('to' => $group['group_id'],'message' => $message);
    $queryData = http_build_query($queryData,'','&');
    $headerOptions = array( 
         'http'=>array(
            'method'=>'POST',
            'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization: Bearer ".$token."\r\n"
                      ."Content-Length: ".strlen($queryData)."\r\n",
            'content' => $queryData
         ),
    );
    $context = stream_context_create($headerOptions);
    $result = file_get_contents(LINE_API,FALSE,$context);
    $res = json_decode($result);
    return $res;
    }
    
