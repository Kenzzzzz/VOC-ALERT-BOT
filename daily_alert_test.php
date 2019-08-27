<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');
    require('./libs/utils/messages.php');
    
    define('LINE_API',"https://notify-api.line.me/api/notify");
    $token1 = "yOgiRn8Z9opjibqhlTV70UQ4SWMQAedCxkvoFyiEaGq"; //ใส่Token ที่copy เอาไว้
    $token2 = "cLcKYVzqarW0rmwl9aETQasNmnRcA3ENKzOU11XiRV0";//test1
    $token3 = "yVkkGGDuveM5j5TVdzE4L2ISu2Q77VCIkgMfDpkG5Jd";//test2
    $token4 = "gR7wxyWPxXFTy6lguwOSGe3DVSXhSRV7Brgy4HVupwc";//test3
    $token5 = "7VIAki84V69XwG7Y3YBa0LvD3iZN9r1KYqsjXAIP3bq";//test4
    //$token6 = "DquN7TlUrX5XNPuz7FJfGAqoQDeGaxgQrIZ59f7szRg";//test5
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
        $str = "รายการข้อร้องเรียน\rประจำวันที่".DateThai(date("Y-m-d"))."\nhttps://vocbot-region2.herokuapp.com/south.php?NUMBER=@10";
        $res = notify_message($str,$token1);
        print_r($res);
        $res1 = notify_message($str,$token2);
        print_r($res1);
        $res2 = notify_message($str,$token3);
        print_r($res2);
        $res3 = notify_message($str,$token4);
        print_r($res3);
        $res4 = notify_message($str,$token5);
        print_r($res4);
       /* $res5 = notify_message($str,$token6);
        print_r($res5);*/
    } else {
        $messages = [
            "type"=> "text",
            "text"=> "Daily Alert :\n\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 10 วัน ในวันที่ ".DateThai(date("Y-m-d"))
        ];
        $str = "รายการข้อร้องเรียน\rประจำวันที่".DateThai(date("Y-m-d"))."\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 10 วัน\nhttps://vocbot-region2.herokuapp.com/south.php?NUMBER=@10";
        $res = notify_message($str,$token1);
        print_r($res);
        $res1 = notify_message($str,$token2);
        print_r($res1);
        $res2 = notify_message($str,$token3);
        print_r($res2);
        $res3 = notify_message($str,$token4);
        print_r($res3);
        $res4 = notify_message($str,$token5);
        print_r($res4);
       /* $res5 = notify_message($str,$token6);
        print_r($res5);*/
    }

    function notify_message($message,$token){
    $queryData = array('message' => $message);
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
    
