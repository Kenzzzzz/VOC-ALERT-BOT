<!DOCTYPE html> 
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0," data-ajax="false" charset="utf-8">
		<title>jQuery Mobile Web App</title>
		<link href="jquery.mobile.theme-1.0.min.css" rel="stylesheet" type="text/css"/>
		<link href="jquery.mobile.structure-1.0.min.css" rel="stylesheet" type="text/css"/>
		<script src="jquery-1.6.4.min.js" type="text/javascript"></script>
		<script src="jquery.mobile-1.0.min.js" type="text/javascript"></script>
	</head> 
	<body> 
		<?php 
			require('./libs/database/connect-db.php');
			$sql_log_id = "SELECT * FROM tbl_individual_log";
			$query_log_id = mysqli_query($conn,$sql_log_id);
		?>
		<div data-role="page" id="page">
			<div data-role="header">
				<h1>สถานะรับทราบการแจ้งเตือน</h1>
			</div>
			<div data-role="content">	
			<?php
				while($obj_log_id = mysqli_fetch_array($query_log_id)){
					if($obj_log_id["accept_status"] == "Y"){
						$status = "รับทราบ";
					}else if($obj_log_id["accept_status"] == "N"){
						$status = "รอ";
					}
					$sql_manager = "SELECT * FROM tbl_manager WHERE id = ".$obj_log_id["manager_id"];  
					$query_manager = mysqli_query($conn,$sql_manager);
					while($obj_manager = mysqli_fetch_array($query_manager)){
						$sql_office = "SELECT * FROM tbl_pea_office WHERE id = ".$obj_manager["office_id"];
						$query_office = mysqli_query($conn,$sql_office);
						while($obj_office = mysqli_fetch_array($query_office)){
							echo "การแจ้งเตือนเลขที่ ".$obj_log_id["id"]."<br>";
							echo "แจ้งเตือนเมื่อ ".$obj_log_id["notify_timestamp"]."<br>";
							echo "หน่วยงาน ".$obj_office["office_name"]."<br>";
							echo "ผู้รับ ".$obj_manager["name"]." ".$obj_manager["surname"]." ".$obj_manager["position"]."<br>";
							echo "สถานะ ".$status."(".$obj_log_id["accept_status"].")<br>";
							echo "เวลารับทราบ ".$obj_log_id["accept_timestamp"]."<br><br>";
						}
					}
				}
			?>
			</div>
			<div data-role="footer">
				<h4>Page Footer</h4>
			</div>
		</div>
	</body>
</html>