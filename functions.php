<?php

/**
 * Echo data from get_booru_info
 * @param $arg | Same options as get_booru_info
 * @return string
 */
function booru_info($arg) {
	global $booru;
	
	echo $booru->$arg;
	return $booru->$arg;
}

function mk_albums() {
	global $albums;
	
	foreach ($albums->albums as $folder) {
		$$folder = new Album;
		$$folder->build_contents($folder);
	}
}
	
?>