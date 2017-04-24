<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<title>Login</title>
	</head>
	<body>
		<h1>Welcome to Yawen's Movie Albums</h1>
		<div class = "login">
		<?php
		$post_username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$post_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

		if (isset($_SESSION['logged_user_by_sql'])){
			print ("<h3>You already logged in.</h3>");
			print ("<h3>To change another account, please <a href='logout.php'>log out</a> first.</h3>");
		} else {

		if (empty($post_username) || empty($post_password)){
		?>
			<h2>Log in</h2>
			<form action="login.php" method="post">
				Username: <input type="text" name="username"><br>
				Password: <input type="password" name="password"><br>
				<input type="submit" value="Login">
			</form>
			<p><a href ="index.php">Return to home page</a></p>

		<?php

		} else {
			require_once 'includes/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if ($mysqli->connect_errno){
				die("Couldn't connect to database");
			}

			$query = "SELECT * FROM users WHERE username = '$post_username'";
			$result = $mysqli->query($query);

			//Make sure there is exactly one user with this username
			if ($result && $result->num_rows == 1){
				$row = $result->fetch_assoc();
				$db_hash_password = $row['hashpassword'];

				if (password_verify($post_password,$db_hash_password)){
					$db_username = $row['username'];
					$_SESSION['logged_user_by_sql'] = $db_username;
				}
			}

			$mysqli->close();

			if (isset($_SESSION['logged_user_by_sql'])){
				print("<h3 class='center'>Congratulations, $db_username You have accessed the website as admin.</p>");
				print("<p><a href ='index.php'>Return to home page</a></p>");
			} else{
				print "<h3 class='center'>You did not login successfully.</p>";
				print "<p>Please <a href = 'login.php'>try again.</a></p>";
			}

		}
	}
		?>
		</div>
	</body>
	</html>
