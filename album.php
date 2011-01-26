<?php include './header.php'; ?>

<?php
	$current_album = $_GET['curr_album'];
//	$$current_album->build_images();
	if ($_GET['action'] == 'build_thumbs') { $$current_album->build_thumbs(); }
?>

<div id="album">
<pre><?php // print_r($$current_album->images); ?></pre>
	<h2>Images from <?php echo ucwords( str_replace(array('-', '_'), ' ', $current_album) ); ?> </h2>
	<ul id="image-list">
		<?php $count = 0;
			foreach ($$current_album->images as $id => $image) {
				$img = basename($image->url);
				echo "<li>
						<a href=\"$booru->home/image.php?curr_album={$$current_album->name}&curr_image=$id\">
							<img src=\"{$$current_album->thumbs_url}/$img\" />
						</a>
					</li>\n";
				$count++; if ($count % 4 == 0) echo '<div class="clear"></div>';
			}
		?>
	</ul>
</div>

<?php include './footer.php'; ?>