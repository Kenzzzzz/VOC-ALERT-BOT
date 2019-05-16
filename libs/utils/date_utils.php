<?php
    date_default_timezone_set('Asia/Bangkok');
    function isToday($otherDate){
        return (strtotime('today') == strtotime($otherDate));
    }

    function isWeekend($date){
        return (date('N', strtotime($date)) >= 6);
    }