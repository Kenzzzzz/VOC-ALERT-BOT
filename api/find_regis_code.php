<?php
    require('../libs/database/connect-db.php');
    error_reporting(E_ERROR | E_PARSE);

    function randomRegisCode() {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghijklmnopqrstuvwxtz0123456789";
        $string_length = 8;
        $regisCode = array("$");
        for ($i=0; $i<$string_length; $i++) {
            $pos_num = rand(0, strlen($chars));
            array_push($regisCode, $chars[$pos_num]);
        }
        
        return implode('', $regisCode);
    }

    while(true){
        $regisCode = randomRegisCode();
        $fetch_existing_code = "SELECT * FROM (SELECT code FROM tbl_authorize UNION SELECT code FROM tbl_manager) AS CODE_LIST ".
                                "WHERE CODE_LIST.code LIKE '%$regisCode%'";
        $code_results = mysqli_query($conn, $fetch_existing_code);
        if(mysqli_num_rows($code_results) == 0){
            echo $regisCode;
            break;
        }
    }

    