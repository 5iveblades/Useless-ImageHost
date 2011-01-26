<?php 
$albums->create_folders_array();
foreach ($albums->albums as $album => $album_abspath) {
	$$album = new Album; $$album->build_contents($album_abspath);
}		
?>