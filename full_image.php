<!DOCTYPE html>
<html>
	<head>
		<title> Full Image</title>
		<!-- function: display full-size image -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>

	<body>




	<?php


	require_once 'includes/header.php';

	require_once 'includes/config.php';

	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	if( empty( $id ) ) {
		//Try to get it from the POST data (form submission)
		$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
	}

	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$sql = "SELECT * FROM images WHERE image_id = $id";
	$sql_album = "SELECT * FROM albums INNER JOIN display ON albums.album_id = display.album_id AND image_id = $id";
	$result = $mysqli->query($sql);
	$albums = $mysqli->query($sql_album);
	$row = $result->fetch_assoc();
	$file_path = $row["file_path"];
	$title = $row["title"];
	$caption = $row["caption"];
	$credit = $row["credit"];

	print("<br><br>");
	print("<h2>$title</h2>");
	print("<img class = 'full_img' src='".$file_path."' src=''></img>");
	print(" <div class='desc'>$caption<br><br><p>source:$credit<p></div></div></div></div>");

	print ("<div>");
	print ("<h4>Album(s) it is in: </h4>");
	while ($row_album = $albums->fetch_assoc()){
		$album_id = $row_album['album_id'];
		$href = "gallary.php?album_id=$album_id";
		print("<a href='$href'><p>{$row_album['title']}</p></a>");
	}
	print ("</div>");

	$mysqli->close();

	?>
	</body>

</html>