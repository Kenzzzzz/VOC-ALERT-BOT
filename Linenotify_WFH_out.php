<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');
    require('./libs/utils/messages.php');
    
    define('LINE_API',"https://notify-api.line.me/api/notify");
    $token1 = "aYELrm8lYPSrk0vjbJMLLU2HUWdhBG78tIXa298qCYI"; //กบว.(ภ2)
    
    $str; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
    
    $todaytime = strtotime('today');
    $todaydate = date('Y-m-d', $todaytime);
    $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
    $holiday_list = mysqli_query($conn, $fetch_holiday);

    if(isWeekend($todaydate) || mysqli_num_rows($holiday_list) > 0){
        return;
    }
        $str = "อย่าลืมลงเวลาเลิกงานกันนะครับ"."\nhttps://docs.google.com/forms/d/e/1FAIpQLScx8qZbuKJ7AIdfQsHaP1_Yn_xjL03yhA2Bokif0Bk6cslB5Q/viewform";
        $res = notify_message($str,$token1);
        print_r($res);
        
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
