<!DOCTYPE html>
<html>
	<head>
		<title>Search</title>
		<!-- function:search images -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>

	<body>
	<?php
	require_once 'includes/header.php';
	require_once 'includes/config.php';

	$message = '';

	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	if ($mysqli->errno) {
			$message .= "<p>$mysqli->error</p>";
			exit();
		}

	if (!empty($_POST['search'])){
		$search_value = filter_input(INPUT_POST,'term',FILTER_SANITIZE_STRING);
		if (strlen($search_value) > 0){
			$sql_album = "SELECT * FROM albums WHERE title LIKE '%$search_value%'";
			$sql_image = "SELECT * FROM images WHERE title LIKE '%$search_value%' OR caption LIKE '%$search_value%'";
			$result_album = $mysqli->query($sql_album);
		    $result_img = $mysqli->query($sql_image);
	

			if ($result_album->num_rows == 0 && $result_img->num_rows == 0){
				$message .= '<h4>No results found</h34';
			} else {
				$message .= '<h4>Result:</h4>';
			}
		} else {
			$message .= '<h4>Input incorrect</h4>';
		}
}


	?>
	<div id="search">
	<h3>Search</h3>
	<form method = "post" >
		<p>Search albums/images:</p> 
		<input type="text" name = "term">
		<input type = "submit" name = "search" value = "search" >
	</form>

	<?php

	echo $message;
	if (!empty($result_album) && $result_album->num_rows != 0) {
		print ("<h4>Album(s)</h4>");
		print('<table>');
		print("<tr><th>Title</th><th>Date created</th><th>Date Modified</th></tr>");
		 	while($row_album = $result_album->fetch_assoc()){
		 		$album_id = $row_album['album_id'];
		 		$href = "gallary.php?album_id=$album_id";
				print('<tr>');
		 			print( "<td class='title'><a href='$href'>{$row_album[ 'title' ]}</a></td>" );
		 			print( "<td class='date'>{$row_album[ 'date_created' ]}</td>" );
		 			print( "<td class ='date'>{$row_album[ 'date_modified' ]}</td>" );

				print('</tr>');
		  }
		 print('</table>');
	}

	if (!empty($result_img) && $result_img->num_rows != 0){
		print ("<h4>Image(s)</h4>");
	 	print('<table>');
		print("<tr><th>Image</th><th>Title</th><th>Caption</th><th>credit</th></tr>");
	 	while($row_img = $result_img->fetch_assoc()){
	 		$image_id = $row_img['image_id'];
	 		$href = "full_image.php?id= $image_id";
			print('<tr>');
				print( "<td class='file_path' ><a href='$href'><img class = 'pic' src= '".$row_img[ 'file_path' ]."' ></img></a></td>" );
	 			print( "<td class='title' width='23%'><a href='$href'>{$row_img[ 'title' ]}</a></td>" );
	 			print( "<td class='caption' width='25%'>{$row_img[ 'caption' ]}</td>" );
	 			print( "<td class='credit' >{$row_img[ 'credit' ]}</td>" );

			print('</tr>');
	  }
	 print('</table>');
	}
	?>
	</div>
	</body>
</html>