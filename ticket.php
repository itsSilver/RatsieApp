<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'history';
if(isset($_SESSION["USERID"])&& $_SESSION["ROLE"] == $_Role->Client) 
{ 	if(isset($_GET["ticketId"]))
	{
		$ticketId = $_GET["ticketId"];
		$userId = $_SESSION["USERID"];
		$s = ociparse($conn, "SELECT betId, choice, betIsWon(betId) isWon, betIsFinished(betId) isFinished FROM Bets WHERE ticketId = :ticketId");
		oci_bind_by_name($s, ":ticketId", $ticketId);
		if(oci_execute($s))
		{}else{
			header("Location: personalHistory.php");
		}
	}
	else
	{
		header("Location: personalHistory.php");
	}
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
					<th>betId</th>
					<th>Finish Time</th>
					<th>Finished</th>
					<th>Won</th>
					<th>Choice</th>
				</tr>
			</thead>
			<tbody>
				<?php if(oci_execute($s)) { while(oci_fetch($s)) { 
				
					$betId = ociresult($s, "BETID");
					$finBet = ociparse($conn, "select betId, TO_CHAR(finishTime,'DD-Mon HH24:MI') finishTime from bets b, races r where b.raceId = r.raceId AND betId = :betId");
					oci_bind_by_name($finBet, ":betId", $betId);
					oci_execute($finBet);
					oci_fetch($finBet);
					$finishTime = ociresult($finBet, "FINISHTIME");
				
				
				?>
					<tr>
						<?php $finishedIcon = ociresult($s, "ISFINISHED") == 1? 'img/checked21.png' : 'img/cross102.png'?>
						<?php $wonIcon = ociresult($s, "ISWON") == 1? 'img/checked21.png' : 'img/cross102.png'?>
						<td><?php echo ociresult($s, "BETID"); ?></td>
						
						<td><?php echo $finishTime; ?></td>
						<td><img src="<?php echo $finishedIcon; ?>"/></td>
						<td><img src="<?php echo $wonIcon; ?>"/></td>
						<td><?php echo ociresult($s, "CHOICE"); ?></td>
					</tr>
				<?php } } ?>
			</tbody>
		</table>
	</div>
</body>
</html>