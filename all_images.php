<?php  session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title> All Images</title>
		<!-- show all images in database -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<script type="text/javascript">
			function delete_id(id){
			if (confirm('Sure to delete this image?')){
				window.location.href='all_images.php?delete_id='+id;
				}
			}
		</script>
	</head>

	<body>



	<?php
		require_once 'includes/header.php';
		require_once 'includes/config.php';

		$message = '';

		$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

				//Was there an error connecting to the database?
		if ($mysqli->errno) {
			//The page isn't worth much without a db connection so display the error and quit
			print($mysqli->error);
			exit();
		}

		if (isset($_GET['delete_id'])){
			$delete_id = filter_input( INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT );
			$path_query = "SELECT file_path FROM images WHERE image_id =".$delete_id;
			$delete_path = $mysqli->query($path_query)->fetch_assoc()['file_path'];
			$delete_query = "DELETE FROM images WHERE image_id = ".$delete_id;
			$delete_res = $mysqli->query($delete_query);
			if ($delete_res){
				unlink($delete_path);
				print("<script>");
				print ("window.location.href.replace(window.location.href.split('?')[0]);");
				print("</script>");
			} else{
				print("<script>");
				print("alert('Delete failed!')");
				print("</script>");
			}
		}

		// $sql = "SELECT * FROM images";
		$sql = "SELECT DISTINCT images.image_id, images.title, images.file_path FROM images INNER JOIN display ON images.image_id = display.image_id";
		$sql_noalb = "SELECT DISTINCT images.image_id, images.title, images.file_path FROM images LEFT JOIN display ON images.image_id = display.image_id WHERE display.album_id IS NULL";
		if ($mysqli->query($sql)){
			$result = $mysqli->query($sql);
		}else {
			echo '<p>Error loading images.</p>';
			$message .='<p>Error loading images.</p>';
		}
		if ($mysqli->query($sql_noalb)){
			$result_no= $mysqli->query($sql_noalb);
		}else{
			$message .='<p>Error loading images.</p>';
		}
		print ("<div class='all_img'>");
		 while($row = $result->fetch_assoc()){
		 	print("<div class = 'box'>");
		 	print("<div class='container'>");
		 	$id = $row['image_id'];
		 	$href = "full_image.php?id=$id";
		 	print("<a href='$href'>"); 
		 	print("<img class = 'pic' src= '".$row[ 'file_path' ]."' alt=''>");
		 	print("</a>");
		 	print(" <div class='desc_img'><h4>{$row[ 'title' ]}</h4></div>");
		 	if (isset($_SESSION['logged_user_by_sql'])) {
				print("<div class ='delete'><a href='javascript:delete_id($id)' ><p>delete</a> ");
				print("<a href='edit_image.php?edit_id=$id' >edit</p></a></div>");

			}
			print ("</div></div>");
		 }
		 print ("</div>");


		 print ("<div class='all_img'>");
		 print ("<h4>Imgae(s) that are not in any albums</h4>");
		 while($row_no = $result_no->fetch_assoc()){
		 	print("<div class = 'box'>");
		 	print("<div class='container'>");
		 	$id = $row_no['image_id'];
		 	$href = "full_image.php?id=$id";
		 	print("<a href='$href'>"); 
		 	print("<img class = 'pic' src= '".$row_no[ 'file_path' ]."' alt=''>");
		 	print("</a>");
		 	print(" <div class='desc_img'><h4>{$row_no[ 'title' ]}</h4></div>");
		 	if (isset($_SESSION['logged_user_by_sql'])) {
				print("<div class ='delete'><a href='javascript:delete_id($id)' ><p>delete</a> ");
				print("<a href='edit_image.php?edit_id=$id' >edit</p></a></div>");

			}
			print ("</div></div>");
		 }
		 print ("</div>");

		 $mysqli->close();
	?>

</body>
</html>