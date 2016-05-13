<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'bet';
if(isset($_SESSION["USERID"]) && $_SESSION["ROLE"] == $_Role->Client) 
{ 
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
	<script>
	_placedBets = [];
	_placedChoices = [];
	_availableBets = [];

	function canBeAdd(raceId){
		if(_placedBets.indexOf(raceId) > -1)
			return false;
		else
			return true;
	}
	
	function removeBet(raceId){
		clearSummary();
		var el = document.getElementById('placedRace'+raceId);
		el.parentNode.removeChild( el );
		
		var index = _placedBets.indexOf(raceId);
		if (index >= 0) {
		  _placedBets.splice( index, 1 );
		  _placedChoices.splice( index, 1 );
		}
		
		if(_placedBets.length == 0)
			document.getElementById("clearAll").style.display = "none";
	}
	
	function clearAll(){
		_placedBets = [];
		_placedChoices = [];
		document.getElementById("ticket").innerHTML = "";
		document.getElementById("clearAll").style.display = "none";
	}
	
	function placeChoice(raceId, pos, finishTime, odd){
		clearSummary();
		if(canBeAdd(raceId))
		{
			_placedBets.push(raceId);
			_placedChoices.push(pos);
			
			
			 document.getElementById("ticket").innerHTML += '<div class="bet" id="placedRace'+raceId+'">'+
							'<button type="button" class="removeBet" onclick="removeBet('+raceId+')">X</button>'+
							'<span class="betTitle">'+pos+'</span>'+
							'<span class="betDate">'+finishTime+'</span>'+
							'<span class="betOdd">Odd: '+odd+'</span>'+
					'</div>';
		}
		else
		{
			displayError("You already betted on this race !");
		}
		document.getElementById("clearAll").style.display = "block";
	}
	
	function populate(races){
		document.getElementById("incoming").innerHTML = "";
		for(var i=0; i<races.length ; i++){
			var newRow = "";
			newRow += '<div class="row raceRow" id="race'+races[i].raceId+'">'+
				'<div class="col col-md-12">'+
				'	<h4>'+races[i].finishTime+'</h4>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="1" onclick="placeChoice('+races[i].raceId+',1,\''+races[i].finishTime+'\','+races[i].rat1Odd+')"><span>1</span>'+races[i].rat1Odd+'</button>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="2" onclick="placeChoice('+races[i].raceId+',2,\''+races[i].finishTime+'\','+races[i].rat2Odd+')"><span>2</span>'+races[i].rat2Odd+'</button>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="3" onclick="placeChoice('+races[i].raceId+',3,\''+races[i].finishTime+'\','+races[i].rat3Odd+')"><span>3</span>'+races[i].rat3Odd+'</button>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="4" onclick="placeChoice('+races[i].raceId+',4,\''+races[i].finishTime+'\','+races[i].rat4Odd+')"><span>4</span>'+races[i].rat4Odd+'</button>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="5" onclick="placeChoice('+races[i].raceId+',5,\''+races[i].finishTime+'\','+races[i].rat5Odd+')"><span>5</span>'+races[i].rat5Odd+'</button>'+
				'</div>'+
				'<div class="col col-md-2 text-center">'+
				'	<button class="betBtn" data="6" onclick="placeChoice('+races[i].raceId+',6,\''+races[i].finishTime+'\','+races[i].rat6Odd+')"><span>6</span>'+races[i].rat6Odd+'</button>'+
				'</div>'
			'</div>';
			
			
			document.getElementById("incoming").innerHTML += newRow;
		}
		document.getElementById("nextTime").innerHTML = races[0].finishTime;
	}
	
	function clearSummary(){
		document.getElementById("validationError").innerHTML = "";
		document.getElementById("validationSuccess").innerHTML = "";
	}
	
	function displayError(summary){
		clearSummary();
		document.getElementById("validationError").innerHTML = summary;
	}
	function displaySuccess(summary){
		clearSummary();
		document.getElementById("validationSuccess").innerHTML = summary;
	}
	
	function uploadTicket(){
		var _placedBetsJson = JSON.stringify(_placedBets);
		var _placedChoicesJson = JSON.stringify(_placedChoices);
		var stake = document.getElementById("submitBet").value;
		var data = {raceIds :_placedBetsJson,
			betChoices :_placedChoicesJson,
			stake :stake};
		var dataJson = JSON.stringify(data);
		//alert(dataJson);
		
		var params = "placeTicket="+dataJson;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var response = JSON.parse(xmlhttp.responseText);
				if(response.status=="fail")
					displayError(response.message);
				if(response.status=="success"){
					displaySuccess(response.message);
					clearAll();
					document.getElementById("submitBet").value = "";
				}
			}
		}
		xmlhttp.open("POST", "live.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");
		xmlhttp.send(params);
	}
	
	function setLastRace(lastRace){
		if(lastRace){
			document.getElementById("podiumRats").style.display = 'block';
			
			document.getElementById("podiumPos1").setAttribute("data", lastRace.pos1);
			document.getElementById("podiumPos1").innerHTML = '<span>' + lastRace.pos1 + '</span>';
			
			document.getElementById("podiumPos2").setAttribute("data", lastRace.pos2);
			document.getElementById("podiumPos2").innerHTML = '<span>' + lastRace.pos2 + '</span>';
			
			document.getElementById("podiumPos3").setAttribute("data", lastRace.pos3);
			document.getElementById("podiumPos3").innerHTML = '<span>' + lastRace.pos3 + '</span>';
			
			document.getElementById("podiumPos4").setAttribute("data", lastRace.pos4);
			document.getElementById("podiumPos4").innerHTML = '<span>' + lastRace.pos4 + '</span>';
			
			document.getElementById("podiumPos5").setAttribute("data", lastRace.pos5);
			document.getElementById("podiumPos5").innerHTML = '<span>' + lastRace.pos5 + '</span>';
			
			
			document.getElementById("podiumPos6").setAttribute("data", lastRace.pos6);
			document.getElementById("podiumPos6").innerHTML = '<span>' + lastRace.pos6 + '</span>';
			
		}
		else{
			document.getElementById("podiumRats").style.display = 'none';
		}
		
		
	}
	
	function getAvailableBets() {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var obj = JSON.parse(xmlhttp.responseText);
				populate(obj.races);
				setLastRace(obj.lastRace);
				document.getElementById("balance").innerHTML = obj.balance;
			}
		}
		xmlhttp.open("GET", "live.php?getNextBets=true", true);
		xmlhttp.send();
	}
	
	function getTime() {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var obj = JSON.parse(xmlhttp.responseText);
				document.getElementById("currTime").innerHTML = obj.currTime;
			}
		}
		xmlhttp.open("GET", "live.php?getTime=true", true);
		xmlhttp.send();
	}
	
	document.addEventListener("DOMContentLoaded", function(event) { 
		getAvailableBets();
		setInterval(function () {
			getAvailableBets();
		}, 3000);
		getTime();
		setInterval(function () {
			getTime();
		}, 1000);
	});
	</script>
</head>
<body>
	<?php include('navbarPartial.php'); ?>
	<div class="balanceContainer">
		$ <span id="balance">2341</span>
	</div>
	<div class="previousRace">
		<div class="podium">
			<span class="podiumTitle">PREV. RACE</span>
			<div id="podiumRats">
				<button id="podiumPos1" class="betBtn podiumWinner"><span>1</span></button>
				<button id="podiumPos2" class="betBtn"><span>2</span></button>
				<button id="podiumPos3" class="betBtn"><span>3</span></button>
				<button id="podiumPos4" class="betBtn"><span>4</span></button>
				<button id="podiumPos5" class="betBtn"><span>5</span></button>
				<button id="podiumPos6" class="betBtn"><span>6</span></button>
			
			</div>
		</div>
		<div class="nextRace">
			<span id="currTime">10:23</span>
			Next race
			<span id="nextTime">10:23</span>
		</div>
	</div>
	<div class="container-fluid">
			<div class="col col-md-9" id="incoming">
				
			</div>
			<div class="col col-md-3" style="padding-right: 0;">
				<div id="ticketContainer">
					<div id="ticket">
					
					</div>
					<div id="clearAll">
						<button onclick="clearAll(); clearSummary();">CLEAR ALL</button>
					</div>
					<div class="ticketSubmit">
						<input id="submitBet" type="text" class="" onkeyup="clearSummary()" placeholder="$" />
						<button onclick="uploadTicket()"> BET </button>
					</div>
				</div>
				<div class="text-danger text-center" id="validationError">
				</div>
				<div class="text-success text-center" id="validationSuccess">
				</div>
			</div>

	</div>
</body>
</html>