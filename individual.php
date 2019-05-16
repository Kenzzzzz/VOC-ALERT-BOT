<!DOCTYPE html> 
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" data-ajax="false" charset="utf-8">
		<title>รายงานข้อร้องเรียน</title>
		<link href="jquery.mobile.theme-1.0.min.css" rel="stylesheet" type="text/css"/>
		<link href="jquery.mobile.structure-1.0.min.css" rel="stylesheet" type="text/css"/>
		<script src="jquery-1.6.4.min.js" type="text/javascript"></script>
		<script src="jquery.mobile-1.0.min.js" type="text/javascript"></script>
	</head>
	<body>
	<?php
		require('./libs/database/connect-db.php');
		require('./libs/utils/date_thai.php');
		require('./libs/utils/date_utils.php');
		
		$office_id = $_GET['office_id'];
		$log_id = $_GET['log_id'];

		// check if open this file not today will automatic closed window
		$fetch_date = "SELECT date(notify_timestamp) AS notidy_date ".
								"FROM tbl_individual_log log JOIN tbl_manager manager ".
								"				ON log.manager_id = manager.id ".
								"			JOIN tbl_pea_office office ".
								"				ON manager.office_id = office.id ".
								"WHERE log.id=".$log_id." AND office.id=".$office_id;
		$date_object = mysqli_query($conn, $fetch_date);
		if(mysqli_num_rows($date_object) == 0){
			echo "<br/><br/><h1 style='text-align:center;'>...ไม่มีข้อมูล...</h1>";
			exit;
		}

		$date_result = mysqli_fetch_array($date_object);
		$today = date('Y-m-d');
		if($date_result['notidy_date'] != $today){
			echo "<br/><br/><h2 style='text-align:center;'>ไม่สามารถเปิดดูได้เนื่องจากเป็นข้อมูลข้อร้องเรียนสถานะวันที่ ".DateThai($date_result['notidy_date'])."</h2>";
			exit;
		}

		// select log for update
		$sql_log_id = "SELECT log.id, name, surname, position, log.accept_status FROM tbl_individual_log log JOIN tbl_manager manager ON log.manager_id = manager.id WHERE log.id = ".$log_id;
		$query_sql_log_id = mysqli_query($conn,$sql_log_id);
		$obj_query = mysqli_fetch_array($query_sql_log_id);

		if(trim($obj_query['accept_status']) == 'N'){
			$timestamp = date('Y-m-d H:i:s');
			$set_flag = "UPDATE tbl_individual_log ".
										"SET accept_status = 'Y', accept_timestamp='$timestamp' ".
										"WHERE id=".$obj_query['id'];
			mysqli_query($conn, $set_flag);
		}

		// select office name from 
		$fetch_office_name = "SELECT * FROM tbl_pea_office WHERE status = 'A' AND id = ".$office_id;
		$office_object = mysqli_query($conn,$fetch_office_name);
		$office = mysqli_fetch_array($office_object);
	?> 
		<div data-role="page" id="page">
			<div data-role="header" data-theme="b">
				<h1>ข้อร้องเรียน</h1>
			</div>
			<div data-role="content">
				<h3 align='center'>
					<?php 
						echo "รายการข้อร้องเรียนรอและกำลังดำเนินการมากกว่าหรือเท่ากับ 7 วัน ของ ".$office['office_name']." และ กฟฟ.ในสังกัด";	
					?>
				</h3>
				<h4 align='center'>
					แจ้งเตือนถึง <u><?=$obj_query['name']." ".$obj_query['surname']?></u> ตำแหน่ง <u><?=$obj_query['position'] ?></u> ประจำวันที่ <u><?=DateThai(date("Y-m-d")) ?></u>
				</h4>
			</div>
			<div data-role="content">
				<ul data-role="listview">
					<?php
						$office_id = $office['id'];
						$office_name = $office['office_name'];
						$fetch_main_complaint = "SELECT office.office_name, COUNT(*) AS count_complaint ".
																		"FROM tbl_pea_office office JOIN tbl_complaint complaint ".
																		"		ON office.office_name = complaint.office_name ".
																		"WHERE complaint.number_of_day >= 7 AND complaint.complaint_status <> 'ปิด' ".
																		"				AND office.office_type = 'กฟฟ.ชั้น 1-3' AND office.id=".$office_id." ".
																		"GROUP BY office.office_name";
						$complaint_main_object = mysqli_query($conn, $fetch_main_complaint);
						if(mysqli_num_rows($complaint_main_object) > 0){
							echo "<li style='font-size:22px;color:crimson;text-align:center;'><b>การไฟฟ้าจุดรวมงาน (ชั้น 1-3)</b></li>";
							while($complaint_main = mysqli_fetch_array($complaint_main_object)){
								echo "<li style='text-indent:20px'><a href ='req_office1.php?REQ=".$complaint_main["office_name"]."&REQ2=7'>".$complaint_main["office_name"]." <small>(จำนวน ".$complaint_main["count_complaint"]." เรื่อง)</small></a></li>"; 
							}
						}

						$fetch_branch_complaint = "SELECT office.office_name, COUNT(*) AS count_complaint ".
																			"FROM tbl_pea_office office JOIN tbl_complaint complaint ".
																			"		ON office.office_name = complaint.office_name ".
																			"WHERE complaint.number_of_day >= 7 AND complaint.complaint_status <> 'ปิด' ".
																			"				AND office.office_type = 'กฟส.' AND (office.id=".$office_id." OR office.parent_level_1=".$office_id.") ".
																			"GROUP BY office.office_name";
						$complaint_branch_object = mysqli_query($conn, $fetch_branch_complaint);
						if(mysqli_num_rows($complaint_branch_object) > 0){
							echo "<li style='font-size:22px;color:brown;text-align:center;'><b>การไฟฟ้าสาขา (กฟส.)</b></li>";
							while($complaint_branch = mysqli_fetch_array($complaint_branch_object)){
								echo "<li style='text-indent:20px'><a href ='req_office1.php?REQ=".$complaint_branch["office_name"]."&REQ2=7'>".$complaint_branch["office_name"]." <small>(จำนวน ".$complaint_branch["count_complaint"]." เรื่อง)</small></a></li>"; 
							}
						}

						$fetch_sub_branch_complaint = "SELECT office.office_name, COUNT(*) AS count_complaint ".
																			"FROM tbl_pea_office office JOIN tbl_complaint complaint ".
																			"		ON office.office_name = complaint.office_name ".
																			"WHERE complaint.number_of_day >= 7 AND complaint.complaint_status <> 'ปิด' ".
																			"				AND office.office_type = 'กฟย.' AND (office.parent_level_1=".$office_id." OR office.parent_level_2=".$office_id.") ".
																			"GROUP BY office.office_name";
						$complaint_sub_branch_object = mysqli_query($conn, $fetch_sub_branch_complaint);
						if(mysqli_num_rows($complaint_sub_branch_object) > 0){
							echo "<li style='font-size:22px;color:#462b2b;text-align:center;'><b>การไฟฟ้าสาขาย่อย (กฟย.)</b></li>";
							while($complaint_sub_branch = mysqli_fetch_array($complaint_sub_branch_object)){
								echo "<li style='text-indent:20px'><a href ='req_office1.php?REQ=".$complaint_sub_branch["office_name"]."&REQ2=7'>".$complaint_sub_branch["office_name"]." <small>(จำนวน ".$complaint_sub_branch["count_complaint"]." เรื่อง)</small></a></li>"; 
							}
						}
					?>
				</ul>		
			</div>
			<br/>
			<div data-role="footer" data-theme="b">
				<h4>PEA</h4>
			</div>
		</div>
	</body>
	<?php include("ga_analytics.php"); ?>
</html>