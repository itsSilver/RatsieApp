<?php
session_start();
require('connAndFunctions.php');

$result = array();
if(isset($_SESSION["USERID"])) 
{ 
	if(isset($_GET["getTime"]))
	{
		$s = ociparse($conn, "SELECT TO_CHAR(SYSDATE, 'HH24:MI:SS') currTime FROM DUAL RETURNING");
		if(oci_execute($s))
		{
			if(oci_fetch($s))
			{
				$result['currTime'] = ociresult($s, "CURRTIME");
			}
		}
	}

	if(isset($_GET["getNextBets"]))
	{
		$race = array();
		$races = array();
		$lastRace = array();
		
		$bl = ociparse($conn, "SELECT balance FROM Users WHERE userId = :userId");
		$userId = $_SESSION["USERID"];
		oci_bind_by_name($bl, ":userId", $userId );
		if(oci_execute($bl))
		{
			while(ocifetch($bl))
			{
				$result['balance'] = ociresult($bl, "BALANCE");
			}
		}
		
		$s = ociparse($conn, "SELECT raceId, TO_CHAR(finishTime,'DD-Mon HH24:MI') finishTimeChar, rat1Odd, rat2Odd, rat3Odd, rat4Odd, rat5Odd, rat6Odd FROM RACES, (SELECT SYSDATE currentDate FROM DUAL) WHERE finishTime > currentDate AND finished = 0 ORDER BY finishTime");
		if(oci_execute($s))
		{
			while(ocifetch($s))
			{
				$race['raceId'] = ociresult($s, "RACEID");
				$race['finishTime'] = ociresult($s, "FINISHTIMECHAR");
				$race['rat1Odd'] = ociresult($s, "RAT1ODD");
				$race['rat2Odd'] = ociresult($s, "RAT2ODD");
				$race['rat3Odd'] = ociresult($s, "RAT3ODD");
				$race['rat4Odd'] = ociresult($s, "RAT4ODD");
				$race['rat5Odd'] = ociresult($s, "RAT5ODD");
				$race['rat6Odd'] = ociresult($s, "RAT6ODD");
				array_push($races, $race);
				//array_push($races, 'raceId' => ociresult($s, "RACEID"));
			}
		}
		
		$lr = ociparse($conn, "SELECT * FROM (SELECT raceId, TO_CHAR(finishTime,'DD-Mon HH:MI') finishTimeChar, pos1, pos2, pos3, pos4, pos5, pos6, rat1Odd, rat2Odd, rat3Odd, rat4Odd, rat5Odd, rat6Odd FROM RACES, (SELECT SYSDATE currentDate FROM DUAL) WHERE finishTime < currentDate AND finished = 1 ORDER BY finishTime DESC) WHERE ROWNUM=1");
		if(oci_execute($lr))
		{
			while(ocifetch($lr))
			{
				$race['raceId'] = ociresult($lr, "RACEID");
				$race['finishTime'] = ociresult($lr, "FINISHTIMECHAR");
				$race['pos1'] = ociresult($lr, "POS1");
				$race['pos2'] = ociresult($lr, "POS2");
				$race['pos3'] = ociresult($lr, "POS3");
				$race['pos4'] = ociresult($lr, "POS4");
				$race['pos5'] = ociresult($lr, "POS5");
				$race['pos6'] = ociresult($lr, "POS6");
				$race['rat1Odd'] = ociresult($lr, "RAT1ODD");
				$race['rat2Odd'] = ociresult($lr, "RAT2ODD");
				$race['rat3Odd'] = ociresult($lr, "RAT3ODD");
				$race['rat4Odd'] = ociresult($lr, "RAT4ODD");
				$race['rat5Odd'] = ociresult($lr, "RAT5ODD");
				$race['rat6Odd'] = ociresult($lr, "RAT6ODD");
				$lastRace = $race;
				//array_push($races, 'raceId' => ociresult($s, "RACEID"));
			}
		}
		
		$result['status'] = 'success';
		$result['races'] = $races;
		$result['lastRace'] = $lastRace;
	}
	
	if(isset($_POST["placeTicket"])){
		$betsJson = $_POST["placeTicket"];
		$bets = json_decode($betsJson);
		$raceIds = json_decode($bets->raceIds);
		$betChoices = json_decode($bets->betChoices);
		$stake = $bets->stake;
		
		//----------------------CHECK STAKE IS VALID------------------
		
		if(!is_numeric($stake))
		{
			$result['status'] = 'fail';
			$result['message'] = 'Please enter a valid amount !';
			echo json_encode($result);
			return;
		}
		
		if($stake<2)
		{
			$result['status'] = 'fail';
			$result['message'] = 'You must bet at least 2$ !';
			echo json_encode($result);
			return;
		}
		
		//---------------------CHECK USER HAS ENOUGH MONEY---------------
		$bal = ociparse($conn, "SELECT balance FROM Users WHERE userId = :userId");
		$userId = $_SESSION["USERID"];
		oci_bind_by_name($bal, ":userId", $userId );
		if(oci_execute($bal))
		{
			if(oci_fetch($bal))
			{
				$balance = ociresult($bal, "BALANCE");
			}
		}
		
		if($stake > $balance)
		{
			$result['status'] = 'fail';
			$result['message'] = 'You don\'t have enough money.';
			echo json_encode($result);
			return;
		}
		
		
		
		if(count($raceIds)>0 && count($betChoices)==count($raceIds))
		{
			$userId = $_SESSION["USERID"];
			
			$payBet = ociparse($conn, "CALL payBet(:userId,:stake)");
			oci_bind_by_name($payBet, ":userId", $userId );
			oci_bind_by_name($payBet, ":stake", $stake);
			if(oci_execute($payBet))
			{
				
			}
			else{
				$result['status'] = 'fail';
				$result['message'] = 'Couldn\'t pay the bet !';
				echo json_encode($result);
				return;
			}
			
			
			$s = ociparse($conn, "INSERT INTO Tickets(userId,stake) VALUES(:userId,:stake) RETURNING ticketId INTO :ticketId");
			oci_bind_by_name($s, ":userId", $userId );
			oci_bind_by_name($s, ":stake", $stake);
			oci_bind_by_name($s, ":ticketId", $ticketId,32);
			if(oci_execute($s))
			{
				for($i = 0; $i<count($raceIds); $i++)
				{
					$currRaceId = $raceIds[$i];
					$currChoice = $betChoices[$i];
					
					
					// CHECK IF IS FINISHED
					$r = ociparse($conn, "SELECT finished FROM RACES WHERE raceId = :raceId");
					oci_bind_by_name($r, ":raceId", $currRaceId);
					if(oci_execute($r))
					{
						if(oci_fetch($r))
						{
							if( ociresult($r, "FINISHED") == 0)
							{
								//echo $ticketId .' - '.$currRaceId .' - '.$currChoice .' - ';
								$b = ociparse($conn, "INSERT INTO Bets(ticketId,raceId,choice) VALUES (:ticketId , :raceId , :choice)");
								oci_bind_by_name($b, ":ticketId", $ticketId);
								oci_bind_by_name($b, ":raceId", $currRaceId);
								oci_bind_by_name($b, ":choice", $currChoice);
								if(oci_execute($b))
								{
									
								}
							}
							else
							{
								$result['status'] = 'fail';
								$result['message'] = 'A race is already finished';
								echo json_encode($result);
								return;
							}
						}
					}
					
					
					
				}
				$result['status'] = 'success';
				$result['message'] = 'Ticket successfully created !';
			}
			// for($i = 0; $i<count($raceIds); $i++)
			// {
				// $currRaceId = $raceIds[$i];
				// $s = ociparse($conn, "SELECT rat1Odd, rat1Odd, rat1Odd, rat1Odd, rat1Odd, rat1Odd FROM RACES WHERE raceId = :raceId AND finished = 0");
				// oci_bind_by_name($s, ":raceId", $currRaceId);
				// if(oci_execute($s))
				// {
					// if(ocifetch($s))
					// {
						// $totalOdd *= ociresult($s, "RAT"+$betChoices[$i]+"ODD");
					// }
					// else
					// {
						// $result['status'] = 'fail';
						// $result['message'] = 'You can not bet on a finished race;';
						// echo json_encode($result);
						// return;
					// }
				// }
			// }

		}
		else
		{
			$result['status'] = 'fail';
			$result['message'] = 'Enter at least one bet';
			echo json_encode($result);
			return;
		}
	}
	
}
else
{
	$result['status'] = 'fail';
	$result['message'] = 'You must be logged';
}

echo json_encode($result);
?>
