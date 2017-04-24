<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title> Edit Image</title>
	<!-- 	function:add new albums -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>
	<body>
		<?php
		require_once 'includes/header.php';

		if (! isset($_SESSION['logged_user_by_sql'])) {
			print ("<div class = 'caution'><h4>Please login to edit image.</h4></div>");
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
		
		if (! empty($_POST['save_image'])){

			//Initialize an array to hold field values found in the $_POST data
			$field_values = array();
			
			//Loop through the expected fields
			foreach( $fields_images as $field ) {
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

			$update_fields = array();

			if (!empty($field_values['title']) && strlen($field_values['title']) > 100){
				$message .= '<p>Title should has less than 100 characters.</p>';
			}

			elseif (!empty($field_values['credit']) && strlen($field_values['credit']) > 500){
				$message .= '<p>Credit should has less than 500 characters.</p>';
			}

			elseif (!empty($field_values['caption']) && strlen($field_values['caption']) > 500){
				$message .= '<p>Caption should has less than 500 characters.</p>';
			} else {
				foreach( $field_values as $field_name => $field_value ) {
				$update_fields[] = "$field_name = '$field_value'";
			}
			
				$sets = implode( ', ', $update_fields );
				
				//Build the SQL for adding a movie - later we'll improve security and quoting
				$sql = "UPDATE images SET $sets WHERE image_id=$edit_id";
				
			}

			if (!empty($sql)){
				if ($mysqli->query($sql)){
					$message .= '<p>Updates saved.</p>';
				} else {
					$message .= '<p>Error updating image.</p><p>$mysqli->error</p>';
				}
			}
		}

			

			$sql_load = "SELECT * FROM images WHERE image_id = $edit_id";
			$result = $mysqli->query($sql_load);
			if ($result){
				$row = $result->fetch_assoc();
				$db_values['title'] = $row['title'];
				$db_values['caption'] = $row['caption'];
				$db_values['credit'] = $row['credit'];
			} else{
				$message .= "<p>Couldn't load albums from the database.</p><p>$mysqli->error</p>";
				$db_values['title'] = '';

			}


		print ("<h1>Edit Image</h1>");
		print ("<div id='show_image'>");
		print $message;
		print ('<form method = "post">');
		print "<p>title:</p>";
		print "<input type='text' name = 'title' value='{$db_values['title']}'>";
		print "<p>caption:</p>";
		print "<input type='text' name = 'caption' value='{$db_values['caption']}' >";
		print "<p>credit:</p>";
		print "<input type = 'text' name = 'credit' value='{$db_values['credit']}'>";
		print("<input type='submit' name='save_image' value='Save'>");
		print '</form>';
		print("</div>");
}

		?>
	</body>

</html>