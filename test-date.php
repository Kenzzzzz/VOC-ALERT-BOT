<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_utils.php');
    // $sql = "SELECT * FROM tbl_holiday WHERE status = 'A'";
    // $results = mysqli_query($conn, $sql);
    // $date = strtotime("today");
    // echo $date;
    // $nextWeek = strtotime("+1 week", $date);
    // $todayDate = date("Y-m-d", $date);
    // echo isWeekend($todayDate)?"true":"false";
    // echo date("Y-m-d", $nextWeek)."<br/>";
    // // echo "diff today and yesterday".(strtotime('today')-strtotime('yesterday'))."<br/>";
    // echo "<table>";
    // while($row = mysqli_fetch_assoc($results)){
    //     echo "<tr>";
    //     echo "<td>".$row['holiday_name']."</td>";
    //     echo "<td>".$row['holiday_date']."</td>";
    //     echo "<td>".(isToday($row['holiday_date'])?"'today'":"'not today'")."</td>";
    //     echo "</tr>";
    // }
    // echo "</table>";

    // $todaytime = strtotime('today');
    // echo $todaytime."<br/>";
    // $todaydate = date('Y-m-d', $todaytime);
    // echo $todaydate."<br/>";
    // $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
    // echo $fetch_holiday."<br/>";

    $todaytime = strtotime('today');
    $todaydate = date('Y-m-d', $todaytime);
    $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
    $holiday_list = mysqli_query($conn, $fetch_holiday);

    echo "Today:".$todaydate."<br/>";
    echo date('Y-m-d H:i:s')."<br/>";

    echo isWeekend($todaydate)?"weekend":"weekday";
    echo "<br/>";
    echo (mysqli_num_rows($holiday_list) > 0)?"holiday":"not holiday";

    if(isWeekend($todaydate) || mysqli_num_rows($holiday_list) > 0){
        return;
    }

    echo "<br/> send push message";