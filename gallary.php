<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gallary</title>
		<!-- function: display images in album -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<script type="text/javascript">
			function delete_id(id,album_id){
			if (confirm('Sure to delete this image in current album?')){
					window.location.href='gallary.php?album_id='+album_id+ '&delete_id='+id;
				}
			}
		</script>
	</head>

	<body>



	<?php

		require_once 'includes/header.php';

		$album_id = filter_input( INPUT_GET, 'album_id', FILTER_SANITIZE_NUMBER_INT );
		if( empty( $album_id ) ) {
			//Try to get it from the POST data (form submission)
			$album_id = filter_input( INPUT_POST, 'album_id', FILTER_SANITIZE_NUMBER_INT );
		}


		require_once 'includes/config.php';

		$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

		//Was there an error connecting to the database?
		if ($mysqli->errno) {
			//The page isn't worth much without a db connection so display the error and quit
			print($mysqli->error);
			exit();
		}

		$row_name = $mysqli->query("SELECT title FROM albums WHERE album_id = $album_id")->fetch_assoc();
		$album_name = $row_name['title'];


		if (isset($_GET['delete_id'])){
			$delete_id = filter_input( INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT );
			$delete_query = "DELETE FROM display WHERE album_id = $album_id AND image_id = $delete_id";
			$delete_res = $mysqli->query($delete_query);

			if ($delete_res){
				print("<script>");
				// print ("window.alert(window.location.href();");
				print ("window.location.href.replace(window.location.href.split('&')[0]);");
				// print ("window.location.reload();");
				print("</script>");
			} else{
				print("<script>");
				print("alert('Delete failed!')");
				print("</script>");
			}

		}

		$sql = "SELECT images.image_id, images.title, images.caption, images.file_path, images.credit ";
		$sql .= "FROM albums INNER JOIN display ON albums.album_id = display.album_id AND albums.album_id = $album_id ";
		$sql .=  "INNER JOIN images ON display.image_id = images.image_id";

		$result = $mysqli->query($sql);


		// print('<table>');
		// print("<tr><th>ID</th><th>Title</th><th>Caption</th><th>Image</th></tr>");
		//  	while($row = $result->fetch_assoc()){
		// 		print('<tr>');
		//  			print( "<td class='image_id'>{$row[ 'image_id' ]}</td>" );
		//  			print( "<td class='title'>{$row[ 'title' ]}</td>" );
		//  			print( "<td class='caption'>{$row[ 'caption' ]}</td>" );
		//  		    print( "<td class='file_path'><img class = 'pic' src= '".$row[ 'file_path' ]."' ></img></td>" );
		//  			// print( "<td class='credit'>{$row[ 'credit' ]}</td>" );

		// 		print('</tr>');
		//   }
		//  print('</table>');
		print ("<h2>$album_name</h2>");

		 while($row = $result->fetch_assoc()){
		 	print("<div class = 'box'>");
		 	print("<div class='container'>");
		 	$id = $row['image_id'];
		 	$href = "full_image.php?id= $id";
		 	print("<a href='$href'>"); 
		 	print("<img class = 'pic' src= '".$row[ 'file_path' ]."' ></img>");
		 	print("</a>");
		 	print(" <div class='desc'><h4>{$row[ 'title' ]}</h4></div>");
		 	if (isset($_SESSION['logged_user_by_sql'])) {
				print("<div class ='delete'><a href='javascript:delete_id($id,$album_id)' ><p>delete</p></a></div>");
			}
			print ("</div></div>");
		 }

		 $mysqli->close();


	?>

		<!-- <img src= 'images/Beauty and Beast.jpg' alt =""></img> -->
	</body>
 
</html>