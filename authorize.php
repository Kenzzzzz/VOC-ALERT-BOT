<?php 
	require('./libs/database/connect-db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Authorize</title>
<script>
	function randomStringp() {
		var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789";
		var string_length = 8;
		var password = "";
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			password += chars.substring(rnum,rnum+1);
		}
		document.randgen.password.value = "#" + password;
	}
</script>
</head>
	<body>
		<div align="center">
			<h1>ลงทะเบียน VOC-BOT</h1>
		</div>
		<div align="center">
			<table width="600" border="1">
				<form action="authorize-success.php" name="randgen" method="post">
					<tr>
						<td width="300"><div align="right">ชื่อ</div></td>
						<td width="300"><input type="text" name="name" id="name" /></td>
					</tr>
					<tr>
						<td><div align="right">นามสกุล</div></td>
						<td><input type="text" name="lastname" id="lastname" /></td>
					</tr>
					<tr>
						<td><div align="right">ตำแหน่ง</div></td>
						<td><input type="text" name="position" id="position" /></td>
					</tr>
					<tr>
						<td><div align="right">รหัสยืนยัน</div></td>
						<td>
							<input name="password" id="password" type="text" onfocus="randomStringp()" value="" />
							<input name="" type="button" onClick="randomStringp()" value="GEN">
						</td>
					<tr>
						<td ></td>
						<td><div align="center"><input type="submit" value="ตกลง" /></div></td>
					</tr>
				</form>
			</table>
		</div>
		<div class="center">
			<table border="1" style="margin:0 auto;">
				<thead>
					<tr>
						<td>ลำดับ</td>
						<td>ชื่อ - นามสกุล</td>
						<td>Authen code</td>
						<td>User id</td>
					</tr>
				</thead>
				<?php 
					$fetch_authorize_user = "SELECT * FROM TBL_AUTHORIZE";
					$results = mysqli_query($conn, $fetch_authorize_user);
					$user_index = 0;
				?>
				<tbody>
					<?php 
						while($user = $results->fetch_assoc()){
					?>
					<tr>
						<td><?=($user_index+1) ?></td>
						<td><?=$user['name']." ".$user['lastname'] ?></td>
						<td><?=$user['code'] ?></td>
						<td><?=$user['line'] ?></td>
					</tr>
					<?php 
						$user_index++;
						}
					?>
				</tbody>
			</table>			
		</div>
	</body> 
</html>
