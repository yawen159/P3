<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title> Add Album</title>
	<!-- 	function:add new albums -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
	</head>

	<body>
	<?php
		require_once 'includes/header.php';

		if (! isset($_SESSION['logged_user_by_sql'])) {
			print ("<div class = 'caution'><h4>Please login to add album.</h4></div>");
		} else {
		require_once 'includes/settings.php';

		$message = '';

		require_once 'includes/config.php';

		$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

		if ($mysqli->errno) {
			print "<p>$mysqli->error</p>";
			exit();
		}

		if( ! empty( $_POST[ 'save_album' ] ) ) {
			//Try to retrieve values from the POST data

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

			if( empty( $field_values['title']) ) {
					$message .= '<p>Album not added. Title is required.</p>';
				}else{
					if (strlen($field_values['title']) > 100){
						$message .= '<p>Album not added. Title should has less than 100 characters.</p>';
					} else{
						$field_name_array = array_keys($field_values);
						$field_list = implode(',',$field_name_array);
						$value_list = implode("','",$field_values);
						$sql = "INSERT INTO albums ($field_list) VALUES ('$value_list');";
					}
				}

			if (! empty($sql)){
				if ($mysqli -> query($sql)){
					$message.='<p>Album Saved.</p>';

					$album_id = $mysqli->insert_id;
				} else {
					$message .= "<p>Error saving movie.</p><p>$mysqli->error</p>";
				}
			}
		}

			$db_values = array();

			if (! empty($movie_id)){
				$sql_load = "SELECT * FROM albums WHERE album_id = $album_id";
				$result = $mysqli->query($sql_load);
				if ($result){
					$row = $result->fetch_assoc();
					$db_values['title'] = $row['title'];
				} else {
					$message .= "<p>Couldn't load album from the database.</p><p>$mysqli->error</p>";
				}
			} else {
				$db_values['title'] = '';
			}

			$mysqli->close();

		print ("<div class ='add'>");
			print "<h3>Add Album</h3>";
			print $message;

			print '<form method = "post">';

			foreach($fields_albums as $field){
				$term = $field['term'];
				$field_heading = $field['heading'];
				$field_value = $db_values[$term];
				print("<p class='$term'><label>$field_heading</label><input type='text' name='$term' value='$field_value'></p>");
			}

			print "<input type = 'submit' name = 'save_album' value = 'Save'>";
			print "</form>";
			print "</div>";
		}

		?>
	</body>
</html>