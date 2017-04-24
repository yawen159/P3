<?php
	session_start();

	if (isset($_SESSION['logged_user_by_sql'])){
		$olduser = $_SESSION['logged_user_by_sql'];
		unset($_SESSION['logged_user_by_sql']);
	} else {
		$olduser = false;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<title>Logout</title>
	</head>
	<body>

		<div class="login">
		<h2>Logout</h2>
		<?php
			if ($olduser){
				print("<h3 class='center'>Logout succeed!</h3>");
			} else {
				print ("<h3 class='center'>You haven't logged in</h3>");
			}
			print("<p> <a href = 'index.php'>Return to home page</a></p>");
		?>
		</div>
	</body>
</html>