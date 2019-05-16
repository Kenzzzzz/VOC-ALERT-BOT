<?php
    require('../libs/PHPExcel/Classes/PHPExcel.php');
    include('../libs/PHPExcel/Classes/PHPExcel/IOFactory.php');
    date_default_timezone_set('Asia/Bangkok');

    // function getMainOfficeByOfficeCode($officeCode){
    //     switch(substr($officeCode, 0, 1)){
    //         case "J":
    //             return "กฟต.1";
    //             break;
    //         case "K":
    //             return "กฟต.2";
    //             break;
    //         case "L":
    //             return "กฟต.3";
    //             break;
    //         default:
    //             return "";
    //             break;
    //     }
    // }

    // function convertToStandardDate($raw_date){
    //     // $raw_date = d/m/Y H:m:s
    //     if(!isset($raw_date) || empty($raw_date)){
    //         return NULL;
    //     }
    //     $thaiDateTimeArray = explode(" ", $raw_date);
    //     $thaiDateWithSlash = $thaiDateTimeArray[0];
    //     $thaiDateArray = explode("/", $thaiDateWithSlash);
    //     $year = (int)($thaiDateArray[2]) - 543;
    //     $thaiDate = date_create_from_format('Y-m-d', $year."-".$thaiDateArray[1]."-".$thaiDateArray[0]);
    //     return $thaiDate;
    // }

    // function getDiffDate($sent_date, $settlement_date, $complaint_status){
    //     if($complaint_status == "ปิด"){
    //         $diff = $sent_date->diff($settlement_date);
    //     } else {
    //         $diff = $sent_date->diff(new DateTime('now'));
    //     }
    //     return ($diff->days + 1);
    // }

    function getDataFromXLSXPath($xlsxPath){
        // load xlsx file with its path
        $inputFileType = PHPExcel_IOFactory::identify($xlsxPath);  
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
        $objReader->setReadDataOnly(true);  
        $objPHPExcel = $objReader->load($xlsxPath);  

        // set config -> activesheet, highestRow, highestColumn and get heading data
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1', null, true, true, true);
        // print_r($headingsArray);
        $headingsArray = $headingsArray[1];

        // collect data within $namedDataArray
        $r = -1;
        $namedDataArray = array();
        for ($row = 2; $row <= $highestRow; ++$row) {
            ++$r;
            $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true);
            foreach($headingsArray as $columnKey => $columnHeading) {
                $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
            }
        }
        return $namedDataArray;
    }

    function uploadXLSXFile($conn, $file){
        $filename = $file['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $target_path = "../upload-auc-files/".basename(date('d-m-').(date("Y")+543)).".".$ext;
        $uploaded_result = @move_uploaded_file($file['tmp_name'], $target_path);
        if(!$uploaded_result) {
            die(error_get_last());
        }
        return $target_path;
    }

    function clearAUCData($conn){
        $sql = "DELETE FROM tbl_tran_auc";
        mysqli_query($conn, $sql);
    }

    function insertAUCData($conn, $namedDataArray){
        $count = 0;
        foreach($namedDataArray as $row){
            $count++;
            $year = $row['ปี'];
            $ba = $row['BA'];
            $wbs = $row['องค์ประกอบ WBS'];
            $job_name = $row['ชื่องาน'];
            $job_director = !empty($row['ผู้ควบคุมงาน'])?$row['ผู้ควบคุมงาน']:"N/A";
            $position = !empty($row['ตำแหน่ง'])?$row['ตำแหน่ง']:"N/A";
            $affiliation = $row['สังกัด'];
            $job_status = $row['สถานะระบบ'];
            $director_status = $row['สถานะผู้ใช้'];
            $value = $row['มูลค่างาน (บาท)'];
            print_r($value);

            $sql = "INSERT INTO tbl_tran_auc(id, year, office_code, wbs, job_name, job_director, position, affiliation, job_status, director_status, value) ".
                    "VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssssd",$count,$year, $ba,
                    $wbs,$job_name,$job_director,
                    $position,$affiliation,$job_status,
                    $director_status,$value);
            $stmt->execute();
        }
    }

    // function countComplaintData($conn){
    //     $sql = "SELECT COUNT(*) AS count_complaint FROM tbl_complaint";
    //     $results = mysqli_query($conn, $sql) or trigger_error($conn->error."[$sql]");    
    //     $row = $results->fetch_assoc();
    //     return $row['count_complaint'];
    // }