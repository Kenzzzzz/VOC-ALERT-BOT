<!DOCTYPE html> 
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" data-ajax="false" charset="utf-8">
		<title>jQuery Mobile Web App</title>
		<link href="jquery.mobile.theme-1.0.min.css" rel="stylesheet" type="text/css"/>
		<link href="jquery.mobile.structure-1.0.min.css" rel="stylesheet" type="text/css"/>
		<script src="jquery-1.6.4.min.js" type="text/javascript"></script>
		<script src="jquery.mobile-1.0.min.js" type="text/javascript"></script>
	</head> 
	<body> 
	<?php
		require('./libs/database/connect-db.php');
		$NUMBER = $_GET['REQ'];
		$NUMBER2 = $_GET['REQ2'];
		$sql = "SELECT * , COUNT(office_name) AS NUM FROM tbl_complaint  WHERE (main_office LIKE '%".$NUMBER."%' OR office_name LIKE '%".$NUMBER."%') AND number_of_day>=".$NUMBER2." AND complaint_status <> 'ปิด' GROUP BY office_name HAVING(COUNT(office_name)>0)";
		$query = mysqli_query($conn,$sql);
		//$mode1 = mysqli_num_rows($query);
		while($ofname = mysqli_fetch_array($query)){ 
			$ofname1 = $ofname["main_office"];
		}

	?>
		<div data-role="page" id="page">
			<div data-role="header" data-theme="b">
				<h1>ข้อมูลข้อร้องเรียน </h1>
			</div>
			<div data-role="content">
			<?php 
				$fetch_number_complaint = "SELECT * FROM TBL_COMPLAINT";
				echo "<b>รายการข้อร้องเรียนที่ค้นพบผ่านคำสั่ง '".$NUMBER."'</b><br/>";
			?>
			</div>
			<div data-role="content">	
				<ul data-role="listview">
				<?php
					mysqli_data_seek($query,0);
					$a = 1;
					while($result=mysqli_fetch_array($query)){
						echo "<li><a href ='req_office1.php?REQ=".$result["office_name"]."&REQ2=$NUMBER2'>".$a.".".$result["office_name"]."  จำนวน  ".$result["NUM"]." เรื่อง</a></li>";;
						$a =$a +1;
					}
					$a = 0;
					mysqli_close($conn);
				?>
				</ul>
				<h2><a href="#" class="ui-btn" data-rel="back" > BACK</a></h2>
			</div>
			<div data-role="footer" data-theme="b">
				<h4>PEA</h4>
			</div>
		</div>
	</body>
	<?php include("ga_analytics.php"); ?>
</html>
