<!DOCTYPE html> 
<html lang="th">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" data-ajax="false">
		<meta charset="utf-8" >
		<title>รายการข้อร้องเรียน</title>
		<link rel="manifest" href="/manifest.json">
		<meta name="theme-color" content="#710E82">

		<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
		<link href="jquery.mobile.theme-1.0.min.css" rel="stylesheet" />
		<link href="jquery.mobile.structure-1.0.min.css" rel="stylesheet" />
		<script src="jquery-1.6.4.min.js" ></script>
		<script src="jquery.mobile-1.0.min.js" ></script>
		<script>
			$(function(){
				liff.init();
			});
		</script>
	</head> 
	<body> 
		<?php
			require('./libs/database/connect-db.php');
			$NUMBER = $_GET['NUMBER'];
			$addpos = strpos($NUMBER,"@");
			$lengh = strlen($NUMBER);
			$lengh1 =$lengh-1;
			echo $NUMBER;
			// if($addpos == 0){
			$datenum = substr($NUMBER,$addpos+1,$lengh1);
			$sql = "SELECT main_office, COUNT(main_office) AS NUM FROM tbl_complaint  WHERE number_of_day>=".$datenum." AND complaint_status <> 'ปิด' GROUP BY main_office HAVING(COUNT(office_name)>0)";
			// }
			$query = mysqli_query($conn,$sql);
		?>
		<div data-role="page" id="page">
			<div data-role="header" data-theme="b">
				<h1>ข้อร้องเรียน</h1>
			</div>
			<div data-role="content">
			<?php 
				echo "รายการข้อร้องเรียนรอและกำลังดำเนินการมากกว่าหรือเท่ากับ ".$datenum." วัน";	
			?>
			</div>
			<div data-role="content">
				<ul data-role="listview">
					<?php
						$a = 1;
						while($result=mysqli_fetch_array($query)){
							echo "<li><a href ='req_office.php?REQ=".$result["main_office"]."&REQ2=$datenum'>".$a.".".$result["main_office"]."  จำนวน  ".$result["NUM"]." เรื่อง</a></li>";
							$a =$a +1;
						}
						$a = 0;
						mysqli_close($conn);
					?>
				</ul>		
			</div>
			<div data-role="footer" data-theme="b">
				<h4>PEA</h4>
			</div>
		</div>
	</body>
	<?php include("ga_analytics.php"); ?>
</html>
