<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'races';
if(isset($_SESSION["USERID"]) && $_SESSION["ROLE"] == $_Role->Admin ) 
{ 
	$userId = $_SESSION["USERID"];
	$s = ociparse($conn, "SELECT r.*, TO_CHAR(r.finishTime,'DD-Mon HH24:MI') finish FROM Races r ORDER BY r.finishTime DESC");
	oci_execute($s);
}
else
{
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('libBundle.php'); ?>
</head>
<body>
	<?php include('navbarPartial.php'); ?>
	<div class="container">
		<table class="table">
			<thead>
				<tr>
					<th>raceId</th>
					<th>Finish Time</th>
					<th>Odd 1</th>
					<th>Odd 2</th>
					<th>Odd 3</th>
					<th>Odd 4</th>
					<th>Odd 5</th>
					<th>Odd 6</th>
					<th>1st</th>
					<th>2nd</th>
					<th>3rd</th>
					<th>4th</th>
					<th>5th</th>
					<th>6th</th>
				</tr>
			</thead>
			<tbody>
				<?php while(oci_fetch($s)) { ?>
					<tr>
						<td><?php echo ociresult($s, "RACEID"); ?></td>
						<td><?php echo ociresult($s, "FINISH"); ?></td>
						<td><?php echo ociresult($s, "RAT1ODD"); ?></td>
						<td><?php echo ociresult($s, "RAT2ODD"); ?></td>
						<td><?php echo ociresult($s, "RAT3ODD"); ?></td>
						<td><?php echo ociresult($s, "RAT4ODD"); ?></td>
						<td><?php echo ociresult($s, "RAT5ODD"); ?></td>
						<td><?php echo ociresult($s, "RAT6ODD"); ?></td>
						
						<?php if (ociresult($s, "POS1")>0){  ?>
							<td><?php echo ociresult($s, "POS1"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
						
						<?php if (ociresult($s, "POS2")>0){  ?>
							<td><?php echo ociresult($s, "POS2"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
						
						<?php if (ociresult($s, "POS3")>0){  ?>
							<td><?php echo ociresult($s, "POS3"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
						
						<?php if (ociresult($s, "POS4")>0){  ?>
							<td><?php echo ociresult($s, "POS4"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
						
						<?php if (ociresult($s, "POS5")>0){  ?>
							<td><?php echo ociresult($s, "POS5"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
						
						<?php if (ociresult($s, "POS6")>0){  ?>
							<td><?php echo ociresult($s, "POS6"); ?></td>
						<?php }else{  ?>
							<td><img src="img/question30.png"/></td>
						<?php }  ?>
					</tr>
				<?php }  ?>
			</tbody>
		</table>
	</div>
</body>
</html>