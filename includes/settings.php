<?php
	//Array of fields used
	$fields_albums = array(
		array( 
			'term' => 'title',
			'heading' => 'Title',
			'filter' => FILTER_SANITIZE_STRING,
		)
	);

	$fields_images = array(
		array( 
			'term' => 'title',
			'heading' => 'Title',
			'filter' => FILTER_SANITIZE_STRING,
		),
		array( 
			'term' => 'caption',
			'heading' => 'Caption',
			'filter' => FILTER_SANITIZE_STRING,
		),
		array(
			'term' => 'file_path',
			'heading' => 'File_path',
			'filter' => FILTER_SANITIZE_STRING,
		),
		array(
			'term' => 'credit',
			'heading' => 'Credit',
			'filter' => FILTER_SANITIZE_URL,
		)	
	);

?>
