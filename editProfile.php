<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'profile';
if(isset($_SESSION["USERID"])) 
{ 
	$userId = $_SESSION["USERID"];
	if(isset($_POST['submit']))
	{
		if(isset($_POST['email']))
		{
			$email = $_POST['email'];
			$zipCode = $_POST['zipCode'];
			$address = $_POST['address'];
			$phoneNr = $_POST['phoneNr'];
			
			$s = ociparse($conn, "UPDATE USERS SET email = :email, zipCode = :zipCode, address = :address, phoneNr = :phoneNr WHERE userId = :userId");
			oci_bind_by_name($s, ":email", $email);
			oci_bind_by_name($s, ":userId", $userId);
			oci_bind_by_name($s, ":zipCode", $zipCode);
			oci_bind_by_name($s, ":address", $address);
			oci_bind_by_name($s, ":phoneNr", $phoneNr);
			oci_execute($s);
			header("Location: login.php");
		}
		else if(!isset($_POST['email']))
		{
			echo "email invalid !";
		}
	}
	else
	{
		$s = ociparse($conn, "SELECT * FROM USERS WHERE userId = :userId");
		oci_bind_by_name($s, ":userId", $userId);
		oci_execute($s);
		if(ocifetch($s))
		{
			$username = ociresult($s, "USERNAME");
			$password = ociresult($s, "PASSWORD");
			$email = ociresult($s, "EMAIL");
			$zipCode = ociresult($s, "ZIPCODE");
			$address = ociresult($s, "ADDRESS");
			$phoneNr = ociresult($s, "PHONENR");
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
				<label class="control-label" for="username">User: <span class="text-primary"><?php echo $username; ?></span></label>
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="email">Email</label>
				<input class="form-control" type="email" name="email" value="<?php echo $email; ?>">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="zipCode">Zip Code</label>
				<input class="form-control" type="text" name="zipCode" pattern=".{0,6}" value="<?php echo $zipCode; ?>">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="address">Address</label>
				<input class="form-control" type="text" name="address" value="<?php echo $address; ?>">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="phoneNr">Phone Number</label>
				<input class="form-control" type="text" name="phoneNr" pattern=".{0,12}" value="<?php echo $phoneNr; ?>">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6 text-center">
				<input class="btn btn-primary" type="submit" name="submit" value="Save">
			</div>
		</form>
	</div>
</body>
</html>