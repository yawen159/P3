<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title> Edit Album</title>
	<!-- 	function:add new albums -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>
	<body>
		<?php
		require_once 'includes/header.php';

		if (! isset($_SESSION['logged_user_by_sql'])) {
			print ("<div class = 'caution'><h4>Please login to edit album.</h4></div>");
		} else {
		require_once 'includes/settings.php';

		$edit_id = filter_input( INPUT_GET, 'edit_id', FILTER_SANITIZE_NUMBER_INT );
		if( empty( $edit_id ) ) {
			//Try to get it from the POST data (form submission)
			$edit_id = filter_input( INPUT_POST, 'edit_id', FILTER_SANITIZE_NUMBER_INT );
		}


		$message = '';

		//Get the connection info for the DB. 
		require_once 'includes/config.php';

		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($mysqli->errno) {
			print "<p>$mysqli->error</p>";
			exit();
		}

		$db_values['title'] = '';
		
		if (! empty($_POST['save'])){

			//Initialize an array to hold field values found in the $_POST data
			$field_values = array();
			
			//Loop through the expected fields
			foreach( $fields_albums as $field ) {
				$field_name = $field[ 'term' ];
				$filter = $field[ 'filter' ];
				
				//Does this term exist in the POST data submitted by the add/edit movie form?
				if( !empty( $_POST[ $field_name ] ) ) {
					//Get the value for this term from the POST data
					$field_value = filter_input( INPUT_POST, $field_name, $filter );
					
					//Store the field values
					$field_values[ $field_name ] = $field_value;
				}
			}

			if (!empty($field_values['title']) && strlen($field_values['title']) >100){
				$message .="<p>Title should has less than 100 characters.</p>";
			} else {

			$update_fields = array();
			if (!empty( $field_values['title'] )){
				foreach( $field_values as $field_name => $field_value ) {
					$update_fields = "$field_name = '$field_value'";
				}
				
				//Build the SQL for adding a movie - later we'll improve security and quoting
				$sql_title = "UPDATE albums SET $update_fields WHERE album_id=$edit_id";
				
			}

			if (!empty($sql_title)){
				if ($mysqli->query($sql_title)){
					$message .= '<p>Title Updated.</p>';
				} else {
					$message .= '<p>Error updating album title.</p><p>$mysqli->error</p>';
				}
			}

			if (!empty($_POST['images'])){
				$images = $_POST['images'];
				foreach ($images as $image){
					$mysqli->query("INSERT INTO display VALUES($edit_id,$image)");
				}
				$message .= "<p>Image(s) added.</p>";
			}
		}
		}

			$sql_load = "SELECT * FROM albums WHERE album_id = $edit_id";
			$result = $mysqli->query($sql_load);
			if ($result){
				$row = $result->fetch_assoc();
				$db_values['title'] = $row['title'];
			} else{
				$message .= "<p>Couldn't load albums from the database.</p><p>$mysqli->error</p>";
				$db_values['title'] = '';

			}

			$sql_image = "SELECT * FROM images WHERE image_id NOT IN (SELECT DISTINCT image_id FROM display WHERE album_id = $edit_id)";
			$result_img = $mysqli->query($sql_image);
			if (!$result_img){
				$message .= "<p>Couldn't load images from database.</p>";
			}

			print ("<h1>Edit Album</h1>");
			print ("<div id='show_image'>");
			print $message;
			print ('<form method = "post">');
			print("<h4><label>Title</label><input type='text' name='title' value='{$db_values['title']}'></h4>");
			print ("<h4><label>Add existing images to album</label></h4>");
			print ("<table id = 'images'><thead><tr><th>Add</th><th>Thumbnail</th><th>Title</th><th>Caption</th></thead><tbody>");
			while ($row_img = $result_img->fetch_assoc()){
				print ("<tr>");
				print ("<td><input type = 'checkbox' name = 'images[]' value='{$row_img['image_id']}'></td>");
				print ("<td width=35%><img class='thumbnail' src='{$row_img['file_path']}' alt='')></td>");
				print ("<td>{$row_img['title']}</td>");
				print ("<td width=35%>{$row_img['caption']}</td>");
				print("</tr>");
			}
			print ("</table>");
			print("<input type='submit' name='save' value='save'>");
			print '</form>';
			print("</div>");
}



		?>
	</body>

</html>