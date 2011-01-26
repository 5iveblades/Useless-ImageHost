
<?php
	include 'header.php';?>
<pre><?php
	$current_album = $_GET['curr_album'];
	print_r($current_album);
	$$current_album->build_image_list();
	print_r($$current_album);
	$$current_album->build_thumbs;
?></pre>
<?php
	include 'footer.php';
	?>