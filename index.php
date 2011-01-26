<?php
require './header.php';
?>
	<!-- <pre>
		<?php print_r($booru); ?>
		<?php print_r($albums); ?>
		---------------------------------------
	</pre> -->
	
	<h2>Albums:</h2>
	<ul>
		<?php foreach ($albums->albums as $album => $album_abspath) { 
			echo "<li><a href=\"album.php?curr_album=$album\">$album</a> (<a href=\"album.php?curr_album=$album&action=build_thumbs\">Build Thumbnails</a>)</li>";
		} ?>
	</ul>
<?php 
require './footer.php';
?>