<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'history';
if(isset($_SESSION["USERID"]) && $_SESSION["ROLE"] == $_Role->Client) 
{ 
	$userId = $_SESSION["USERID"];
	$s = ociparse($conn, "SELECT ticketId, userId, stake, ticketIsWon(ticketId) isWon, ticketIsFinished(ticketId) isFinished FROM Tickets WHERE userId = :userId ORDER by ticketId DESC");
	oci_bind_by_name($s, ":userId", $userId);
	$fp = fopen('file.csv', 'w');
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
					<th>ticketId</th>
					<th>Finish Time</th>
					<th>Finished</th>
					<th>Won</th>
					<th>Stake</th>
				</tr>
			</thead>
			<tbody>
				<?php if(oci_execute($s)) { while(oci_fetch($s)) { 
				
					$ticketId = ociresult($s, "TICKETID");
					$finTick = ociparse($conn, "select TO_CHAR(MAX(t.finishTime),'DD-Mon HH24:MI') finish from (select * from (select * from bets where ticketId = :ticketId) q, races r where q.raceId = r.raceId) t");
					oci_bind_by_name($finTick, ":ticketId", $ticketId);
					oci_execute($finTick);
					oci_fetch($finTick);
					$finishTime = ociresult($finTick, "FINISH");
				?>
					<tr>
						<?php $finishedIcon = ociresult($s, "ISFINISHED") == 1? 'img/checked21.png' : 'img/cross102.png'?>
						<?php $wonIcon = ociresult($s, "ISWON") == 1? 'img/checked21.png' : 'img/cross102.png'?>
						<td> <a href="ticket.php?ticketId=<?php echo $ticketId; ?>"><?php echo $ticketId; ?></a></td>
						<td><?php echo $finishTime; ?></td>
						<td><img src="<?php echo $finishedIcon; ?>"/></td>
						<td><img src="<?php echo $wonIcon; ?>"/></td>
						<td>$ <?php echo ociresult($s, "STAKE"); ?></td>
					</tr>
				<?php 
				
					fputcsv($fp, array(ociresult($s, "TICKETID"), $finishTime, ociresult($s, "ISFINISHED"), ociresult($s, "ISWON"), ociresult($s, "STAKE")));
				} } 
				?>
			</tbody>
		</table>
	</div>
</body>
</html>