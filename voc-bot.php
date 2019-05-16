<?php
require('./libs/database/connect-db.php');
$access_token = 'QPUPUnMzGhO//A8J2Qi1nmBXgEW89hciaaxNExeLVgxa8cjYtvnF9TZQF3TEjEOVA5HhS6dTRT2Tp4F0I3JhC0QWrQdmlBiL/6bhuazJI/juOxmvFx31NX7RWv9z19gbUZAdPIEuAURaHPy7TnDNkQdB04t89/1O/w1cDnyilFU=';
 
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data

if (!is_null($events['events'])) {
    // Loop through each event
    foreach ($events['events'] as $event) {
        // Reply only when message sent is in 'text' format
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            // Get text sent
            $text = $event['message']['text'];
			$lineid = $event['source']['userId'];		
			$addpos = strpos($text,"@");
			$lengh = strlen($text);
			$lengh1 = $lengh-1;
			// Get replyToken
            $replyToken = $event['replyToken'];
         
            $regis_code = substr($text,0,1); // เก็บตัวอักษรแรก
//---------------------------------เก็บ UID ลง DATABASE-----------------------------------------------------//		 
			if($regis_code == "#"){
				$sql = "SELECT * FROM tbl_authorize WHERE code LIKE '%".$text."%'";
				$query = mysqli_query($conn, $sql);
				$nums = mysqli_num_rows($query);
				$result = mysqli_fetch_array($query);
				$t = $result['name'];
				$t2 = $result['lastname'];
				$t1 = $result['line'];

				if($nums == 1 AND $t1 <> "") {
					$txtans = "รหัสยืนยันนี้ถูกใช้งานแล้วโดย ".$t." ".$t2;
				}

				if($nums == 1 AND $t1 == ""){
					$sql_regis = "UPDATE tbl_authorize SET line ='$lineid' WHERE code LIKE '%".$text."%'";
					mysqli_query($conn, $sql_regis);
					$txtans = "ลงทะเบียนสิทธิ์การพิมพ์คำสั่งเรียบร้อย";
				}

				if($nums == 0){
					$txtans = "รหัสยืนยันไม่ถูกต้อง";
				}
					
				$messages = [ 'type' => 'text', 'text' => $txtans];
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
						'replyToken' => $replyToken,
						'messages' => [$messages],
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
				return;
			}

			if($regis_code == "$"){

				$find_existing_regis = "SELECT uid FROM tbl_manager WHERE uid = '$lineid'";
				$query = mysqli_query($conn, $find_existing_regis);
				$count_existing = mysqli_num_rows($query);
				if( $count_existing > 0 ){
					$txtans = "ท่านลงทะเบียนเรียบร้อยแล้ว ไม่สามารถลงทะเบียนซ้ำได้อีก...";
				} else {
					$select_code = "SELECT * FROM tbl_manager WHERE code = '".$text."'";
					$query = mysqli_query($conn, $select_code);
					$nums = mysqli_num_rows($query);
					$result = mysqli_fetch_array($query);
					$t = $result['name'];
					$t2 = $result['surname'];
					$t1 = $result['uid'];

					// if($nums == 1 AND $t1 <> "") {
					// 	$txtans = "รหัสยืนยันนี้ถูกใช้งานแล้วโดย ".$t." ".$t2;
					// }

					if($nums == 1 AND $t1 == ""){
						$sql_regis = "UPDATE tbl_manager SET uid ='$lineid' WHERE code = '".$text."'";
						mysqli_query($conn, $sql_regis);
						$txtans = "ลงทะเบียนการแจ้งเตือนรายบุคคลในนามของ $t $t2 เรียบร้อย";
					}else if($nums == 0){
						$txtans = "รหัสยืนยันไม่ถูกต้อง";
					}
				}
					
				$messages = [ 'type' => 'text', 'text' => $txtans];
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
						'replyToken' => $replyToken,
						'messages' => [$messages],
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
				return;
			}
//******************************************************************************************************//
//----------------------------------------เอา UID ค้นหาในฐานข้อมูล-----------------------------------------//
			$sql_line = "SELECT * FROM tbl_authorize WHERE line LIKE '%".$lineid."%'";
			$query_line = mysqli_query($conn,$sql_line);
			$re = mysqli_num_rows($query_line); //<<<<<ใส่ผลลัพธ์ที่ได้ลงในตัวแปร $re
//*******************************************************************************************************//
			if($re <> 0){
				$hello_prefix = substr($text, 0, 10);
				$group_name = substr($text, 10);
				$source_type = $event['source']['type'];
				if($hello_prefix == "/hellobot:" AND $source_type == "group"){
					$group_id = $event['source']['groupId'];
					$fetch_existing_group = "SELECT id FROM tbl_line_group WHERE group_id = '$group_id'";
					$group_result = mysqli_query($conn, $fetch_existing_group);
					if(mysqli_num_rows($group_result) > 0) {
						break;
					}

					$insert_group = "INSERT INTO tbl_line_group(group_id, group_name) VALUES('$group_id', '$group_name')";
					mysqli_query($conn, $insert_group);
					$messages = [ 'type' => 'text', 'text' => 'เปิดใช้งาน Daily Alert เรียบร้อยแล้ว'];
					$url = 'https://api.line.me/v2/bot/message/reply';
					$data = [
							'replyToken' => $replyToken,
							'messages' => [$messages],
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
					break;
				}

				// ตำแหน่ง@อยู่ตัวแรก เช่น @15
				if($addpos == 0){
					$datenum = substr($text,$addpos+1,$lengh1);
					$sql = "SELECT * FROM tbl_complaint WHERE number_of_day>=".$datenum." AND complaint_status <> 'ปิด'";
					$mode4 = "https://voc-bot.herokuapp.com/south.php?NUMBER=".$text; //เรียกหน้าภาค
				}
							
				if($addpos > 1){ 
					if($addpos == $lengh1){ 
						$main_office = substr($text,0,$addpos); 
						$sql = "SELECT * FROM tbl_complaint WHERE (office_name LIKE '%".$main_office."%' OR main_office LIKE '%".$main_office."%') AND complaint_status <> 'ปิด'";
						$datenum1 = 0;
						$mode4 = "https://voc-bot.herokuapp.com/req_office.php?REQ=".$main_office."&REQ2=".$datenum1;
					}
					if($addpos < $lengh1){
						$main_office = substr($text,0,$addpos); 
						$datenum = substr($text,$addpos+1,$lengh1); 
						$sql = "SELECT * FROM tbl_complaint WHERE number_of_day>=".$datenum." AND (office_name LIKE '%".$main_office."%' OR main_office LIKE '%".$main_office."%') AND complaint_status <> 'ปิด'";
						$mode4 = "https://voc-bot.herokuapp.com/req_office.php?REQ=".$main_office."&REQ2=".$datenum;	 			
					} 
				}
							
				$query = mysqli_query($conn,$sql);
				$mode1 = mysqli_num_rows($query);
				while($result=mysqli_fetch_array($query)){
					$mode = iconv("tis-620","utf-8",$result["OFFICE"]);
				}
				mysqli_close($conn);
			
			
				// Build message to reply back
				//$messages = [
				//  'type' => 'text',
				
			//    'text' => "ค้นพบ  ".$mode1."  รายการ   ".$mode4    //."  [".$KVA." KVA]"
				
			// ];
				if($mode1 == 0 or $addpos == 0 and $first = substr($text,0,1) <> "@"){
				//$messages = ['type' => 'text',
				// 'text' => "กรุณาตรวจสอบการใช้คำค้นหา"    
				//    ];
					break; 
				} else {
					$messages = array(
						'type'=> 'template',
						'altText'=> "ค้นพบข้อร้องเรียน: ".$mode1." รายการ \nจากคำสั่ง: '$text'",
						'template'=>array(
							'type'=>'buttons',
							'text'=> "ค้นพบข้อร้องเรียน: ".$mode1." รายการ \n\nจากคำสั่ง: '$text'",
							'actions'=>array(
								array('type'=> 'uri','label'=> 'ดูรายการข้อร้องเรียน','uri'=> $mode4)
							)
					));
				}
	
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
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
	
				echo $result . "\r\n";
			}
        }
    }
}

echo '<a href="test.php">test</a>';

