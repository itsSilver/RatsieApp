<nav class="navbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php">Ratsie</a>
		</div>
		<ul class="navbar-right">
			<?php if(isset($_SESSION["USERID"])) { ?>
			<?php if($_SESSION["ROLE"] == $_Role->Client) { ?>
				<li class="payment"><a href="payment.php">Deposit</a></li>
				<li class="<?php if($_CurrentPage == 'bet') echo 'active';?>"><a href="index.php">Bet</a></li>
				<li class="<?php if($_CurrentPage == 'history') echo 'active';?>"><a href="personalHistory.php">History</a></li>
			<?php } ?>
			
			
			<?php if($_SESSION["ROLE"] == $_Role->Admin){ ?>
				<li class="<?php if($_CurrentPage == 'races') echo 'active';?>"><a href="races.php">Races</a></li>
				<li class="<?php if($_CurrentPage == 'create') echo 'active';?>"><a href="create.php">Create</a></li>
			<?php } ?>
			
			
			<li class="<?php if($_CurrentPage == 'profile') echo 'active';?>"><a href="editProfile.php">Profile</a></li>
			<li><a href="logout.php">Logout </a></li>
			
			<?php }else{ ?>
			
			<li class="<?php if($_CurrentPage == 'register') echo 'active';?>"><a href="register.php">Register </a></li>
			<li class="<?php if($_CurrentPage == 'login') echo 'active';?>"><a href="login.php">Login </a></li>
			
			<?php } ?>
		</ul>
	</div>
</nav>
