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
    	require 'vendor/autoload.php';
			require('./libs/database/connect-db.php');
			use PhpOffice\PhpSpreadsheet\Shared\Date;

			function DateThai($strDate){
				$strYear = date("Y",strtotime($strDate))+543;
				$strMonth= date("n",strtotime($strDate));
				$strDay= date("j",strtotime($strDate));
				$strHour= date("H",strtotime($strDate));
				$strMinute= date("i",strtotime($strDate));
				$strSeconds= date("s",strtotime($strDate));
				// $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$strMonthThai=$strMonthCut[$strMonth];
				return "$strDay $strMonthThai $strYear, $strHour:$strMinute น.";
			}

			function DateDiff($strDate1){
				return (strtotime(date("Y/m/d")) - strtotime($strDate1) )/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
			}
			$NUMBER = $_GET['REQ'];
			$sql = "SELECT * FROM tbl_complaint WHERE complaint_id LIKE '%".$NUMBER."%' ";
			$query = mysqli_query($conn,$sql);
		?>
		<div data-role="page" id="page">
			<div data-role="header" data-theme="b">
				<h1>ข้อมูลข้อร้องเรียน</h1>
			</div>
			<div data-role="content">	
			<?php
				while($result=mysqli_fetch_array($query)){   
					if($result["received_date"]== Null){
						$strDate1 = "รอดำเนินการ";
					}else{
						$strDate1 = DateThai(Date::excelToDateTimeObject($result["received_date"])->format('Y-m-d H:i:s'));
					}

					// DateThai(Date::excelToDateTimeObject(43500.452693669)->format('Y-m-d H:i:s'))
					echo "<b>หมายเลขข้อร้องเรียน : </b>";
					echo $result["complaint_id"]."<br><br>";
					echo "<b>วันที่เรื่องเข้าระบบ : </b>";
					echo DateThai(Date::excelToDateTimeObject($result["sent_date"])->format('Y-m-d H:i:s'))."<br><br>";
					echo "<b>วันที่รับคำร้อง : </b>";
					echo $strDate1."<br><br>";
					echo "<b>จำนวนวัน : </b>";
					echo $result["number_of_day"]." วัน <br><br>";
					echo "<b>หน่วยงาน : </b>";
					echo $result["office_name"]."<br><br>";
					// echo "<b>ชื่อผู้ร้องเรียน : </b>";
					// echo $result["complainant_name"]."<br><br>";
					// echo "<b>เบอร์โทรศัพท์ : </b>";
					// echo $result["tel_contact"]."<br><br>";
					echo "<b>ประเภทข้อร้องเรียน : </b>";
					echo $result["complaint_type"]."<br><br>";
					echo "<b>หัวข้อย่อย : </b>";
					echo $result["sub_complaint_type"]."<br><br>";
					echo "<b>สถานะข้อร้องเรียน : </b>";
					echo $result["complaint_status"]."<br><br>";
				}
			?>	
				<h2><a href="#" class="ui-btn" data-rel="back">BACK</a></h2>
			</div>
			<div data-role="footer" data-theme="b">
				<h4>PEA</h4>
			</div>
		</div>
	</body>
	<?php include("ga_analytics.php"); ?>
</html>
