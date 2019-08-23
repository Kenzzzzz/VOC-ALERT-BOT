<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');
    require('./libs/utils/messages.php');
    
    define('LINE_API',"https://notify-api.line.me/api/notify");
    $token1 = "yOgiRn8Z9opjibqhlTV70UQ4SWMQAedCxkvoFyiEaGq"; //test
    $token2 = "aqbc2ZriucSfysg2opBslp5TglIFGf5W72Jmotktpoi";//ภาค2
    $token3 = "D3iR5pi60QEwGUs7esTNktBbxJ2EpoLAxExl3DHwEC1";//NE3
    $token4 = "jK5OWjRrJ1XK2tvSigKl5CFYghrUSGt3CS2ZyHIvpOr";//NE3_1
    $token5 = "FuGGlrjjhO8GhabRFysZhaaMuTp14IraXViPZyNztRG";//NE3_2
    $token6 = "ETslsRqJuuC52nGbpU1VPbDz4MPELy7kjCG7gMxkKNd";//NE3_3
    
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
        $res1 = notify_message($str,$token3);
        print_r($res2);
        $res1 = notify_message($str,$token4);
        print_r($res3);
        $res1 = notify_message($str,$token5);
        print_r($res4);
        $res1 = notify_message($str,$token6);
        print_r($res5);
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
        $res1 = notify_message($str,$token3);
        print_r($res2);
        $res1 = notify_message($str,$token4);
        print_r($res3);
        $res1 = notify_message($str,$token5);
        print_r($res4);
        $res1 = notify_message($str,$token6);
        print_r($res5);
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
    
<?php
define('LINE_API',"https://notify-api.line.me/api/notify");
 
$token = "aqbc2ZriucSfysg2opBslp5TglIFGf5W72Jmotktpoi"; //ใส่Token ที่copy เอาไว้
$str = "รายการข้อร้องเรียน
https://vocbot-region2.herokuapp.com/south.php?NUMBER=@10"; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
 
$res = notify_message($str,$token);
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
//https://havespirit.blogspot.com/2017/02/line-notify-php.html
//https://medium.com/@nattaponsirikamonnet/%E0%B8%A1%E0%B8%B2%E0%B8%A5%E0%B8%AD%E0%B8%87-line-notify-%E0%B8%81%E0%B8%B1%E0%B8%99%E0%B9%80%E0%B8%96%E0%B8%AD%E0%B8%B0-%E0%B8%9E%E0%B8%B7%E0%B9%89%E0%B8%99%E0%B8%90%E0%B8%B2%E0%B8%99-65a7fc83d97f
?>
