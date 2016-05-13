<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'create';
if(isset($_SESSION["USERID"]) && $_SESSION["ROLE"] == $_Role->Admin) 
{ 
	$userId = $_SESSION["USERID"];
	if(isset($_POST['submit']))
	{
		$finishTime = $_POST['date'] . ' ' . $_POST['time'];
		$rat1Odd = $_POST['rat1Odd'];
		$rat2Odd = $_POST['rat2Odd'];
		$rat3Odd = $_POST['rat3Odd'];
		$rat4Odd = $_POST['rat4Odd'];
		$rat5Odd = $_POST['rat5Odd'];
		$rat6Odd = $_POST['rat6Odd'];
		
		$s = ociparse($conn, "INSERT INTO Races(finishTime, rat1Odd, rat2Odd, rat3Odd, rat4Odd, rat5Odd, rat6Odd) VALUES(TO_DATE(:finishTime,'DD/MM/YYYY HH24:MI'), :rat1Odd, :rat2Odd, :rat3Odd, :rat4Odd, :rat5Odd, :rat6Odd)");
		oci_bind_by_name($s, ":finishTime", $finishTime);
		oci_bind_by_name($s, ":rat1Odd", $rat1Odd);
		oci_bind_by_name($s, ":rat2Odd", $rat2Odd);
		oci_bind_by_name($s, ":rat3Odd", $rat3Odd);
		oci_bind_by_name($s, ":rat4Odd", $rat4Odd);
		oci_bind_by_name($s, ":rat5Odd", $rat5Odd);
		oci_bind_by_name($s, ":rat6Odd", $rat6Odd);
		
		if(oci_execute($s))
		{
			header("Location: races.php");
		}
		else
		{
			$validationSummary = "Fields invalid";
		}
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
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row">
			<?php
			if(isset($validationSummary))
			{ ?>
			<div class="form-group col col-md-offset-3 col-md-6">
				<span class="text-danger"><strong><?php echo $validationSummary; ?></strong></span>
			</div>
			<?php
			}
			?>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="date">Date</label>
				<input class="form-control" type="text" pattern="\d{2}/\d{2}/\d{4}" name="date" title="format dd/mm/yyyy" required />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="time">Time</label>
				<input class="form-control" type="text" name="time" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" required title="format 24 (hh:mm)" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat1Odd">Odd Rat 1</label>
				<input class="form-control" type="number" name="rat1Odd" step="0.01"/>
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat2Odd">Odd Rat 2</label>
				<input class="form-control" type="number" name="rat2Odd" step="0.01" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat3Odd">Odd Rat 3</label>
				<input class="form-control" type="number" name="rat3Odd" step="0.01" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat4Odd">Odd Rat 4</label>
				<input class="form-control" type="number" name="rat4Odd" step="0.01" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat5Odd">Odd Rat 5</label>
				<input class="form-control" type="number" name="rat5Odd" step="0.01" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="rat6Odd">Odd Rat 6</label>
				<input class="form-control" type="number" name="rat6Odd" step="0.01" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6 text-center">
				<input class="btn btn-primary" type="submit" name="submit" value="Create Race">
			</div>
		</form>
	</div>
</body>
</html>