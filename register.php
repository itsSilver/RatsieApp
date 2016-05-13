<?php
session_start();
session_destroy();
session_start();
error_reporting(E_ALL & ~E_WARNING);
require('connAndFunctions.php');
$_CurrentPage = 'register';
if(isset($_POST['submit'])) 
{ 
	$validated = true;
    $username = $_POST['username'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	$email = $_POST['email'];
	$role = 'client';
	
	//------------------------CHECK USERNAME EXITS-------------------------
	$validUser = ociparse($conn, "SELECT COUNT(userId) noUsers FROM USERS WHERE UPPER(username) = UPPER(:username)");
	oci_bind_by_name($validUser, ":username", $username);
	if(oci_execute($validUser))
	{
		if(ocifetch($validUser))
		{
			if(ociresult($validUser, "NOUSERS")>0)
			{
				$validationSummary = 'This username already exist !';
				$validated = false;
			}
		}
	}
	//------------------------CHECK PASSWORD -----------------------
	if(strlen($password) < 6)
	{
		$validationSummary = 'Password must contain at least 6 characters !';
		$validated = false;
	}
	if($password != $passwordConfirm)
	{
		$validationSummary = 'Password confirmation is not the same !';
		$validated = false;
	}
	//-----------------------CHECK EMAIL ---------------------------
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$validationSummary = 'Invalid email format !';
		$validated = false;
	}
	
	if($validated)
	{
		$s = ociparse($conn, "INSERT INTO USERS(username, password, email, role) VALUES(:username , :password, :email, :role)");
		oci_bind_by_name($s, ":username", $username);
		oci_bind_by_name($s, ":password", $password);
		oci_bind_by_name($s, ":email", $email);
		oci_bind_by_name($s, ":role", $role);
		if(oci_execute($s))
		{
			header("Location: login.php");
		}
		else
		{
			$e = oci_error($s); 
			$validationSummary = getOciMessage(htmlentities($e['message']));
		}
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
				<input class="form-control" type="text" name="username" required>
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="password"> Password</label>
				<input class="form-control" type="password" name="password" required pattern="([a-zA-Z0-9]+){6,}" title="Password must contain at least 6 characters" />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="passwordConfirm">Password Confirm</label>
				<input class="form-control" type="password" name="passwordConfirm" required />
			</div>
			<div class="form-group col col-md-offset-3 col-md-6">
				<label class="control-label" for="email">Email</label>
				<input class="form-control" type="email" name="email">
			</div>
			<div class="form-group col col-md-offset-3 col-md-6 text-center">
				<input class="btn btn-primary" type="submit" name="submit" value="Register" required />
			</div>
		</form>
	</div>
</body>
</html>