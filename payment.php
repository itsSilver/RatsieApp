<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'payment';
if(isset($_SESSION["USERID"])&& $_SESSION["ROLE"] == $_Role->Client) 
{ 
	$userId = $_SESSION["USERID"];
	if(isset($_POST['submit']))
	{
		if(isset($_POST['amount']))
		{
			
			$amount = $_POST['amount'];
			
			$s = ociparse($conn, "CALL addAmount(:userId, :amount)");
			oci_bind_by_name($s, ":userId", $userId);
			oci_bind_by_name($s, ":amount", $amount);
			if(oci_execute($s))
			{
				header("Location:index.php");
			}
			else
			{
				echo("Please enter a valid amount !");
			}
			
		}
		else if(!isset($_POST['email']))
		{
			echo "email invalid !";
		}
	}
	else
	{
		$s = ociparse($conn, "SELECT BALANCE FROM USERS WHERE userId = :userId");
		oci_bind_by_name($s, ":userId", $userId);
		oci_execute($s);
		if(ocifetch($s))
		{
			$balance = ociresult($s, "BALANCE");
		}
		else
		{
			echo "Invalid account!"; 
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
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="email">Currency: $<span class="text-primary"><?php echo $balance; ?></span></label>
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="amount">Amount to Add</label>
				<input class="form-control" type="number" name="amount" required />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6 text-center">
				<input class="btn btn-primary" type="submit" name="submit" value="Save">
			</div>
		</form>
	</div>
</body>
</html>