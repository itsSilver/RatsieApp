<?php
session_start();
require('connAndFunctions.php');
$_CurrentPage = 'login';
if(isset($_SESSION["USERID"]))
{
	if($_SESSION["ROLE"] == $_Role->Client)
		header("Location: index.php");
	if($_SESSION["ROLE"] == $_Role->Admin)
		header("Location: races.php");
}
if(isset($_POST['submit'])) 
{ 
    $username = $_POST['username'];
	$password = $_POST['password'];
	
	$s = ociparse($conn, "SELECT * FROM USERS WHERE username = :username AND password = :password");
	oci_bind_by_name($s, ":username", $username);
	oci_bind_by_name($s, ":password", $password);
	oci_execute($s);
	if(ocifetch($s))
	{
		echo ociresult($s, "USERID") . "<br />";
		$_SESSION["USERID"] = ociresult($s, "USERID");
		$_SESSION["ROLE"] = ociresult($s, "ROLE");
		unset($_POST['submit']);
		unset($_POST['username']);
		unset($_POST['password']);
		if($_SESSION["ROLE"] == $_Role->Client)
			header("Location: index.php");
		if($_SESSION["ROLE"] == $_Role->Admin)
			header("Location: races.php");
	}
	else
	{
		$validationSummary = "Invalid username or password!"; 
	}
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
				<label class="control-label" for="username">User Name</label>
				<input class="form-control" type="text" name="username">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="password">Password</label>
				<input class="form-control" type="password" name="password">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6 text-center">
				<input class="btn btn-primary" type="submit" name="submit" value="Login">
			</div>
		</form>
	</div>
</body>
</html>