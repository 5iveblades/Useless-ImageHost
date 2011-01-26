<?php include './header.php'; ?>

<?php
	$current_album = $_GET['curr_album'];
	$current_img_id = $_GET['curr_image'];
	$current_img = $$current_album->images[$current_img_id];
?>

<div id="image-<?php echo $current_img_id; ?>" class="full-image">
	<!-- <pre><?php print_r($current_img); ?></pre> -->
	<h2><?php echo $current_img->name; ?></h2><br>
	
	<div id="image">
		<img src="<?php echo $current_img->url; ?>">
	</div>
	
	<div id="details">
		<div>
			<h4>EXIF</h4>
			<ul id="<?php echo $current_img->filename; ?>-exif">
				<?php $current_img->list_exif(); ?>
			</ul>
			<div class="clear"></div>
			<?php if ($current_img->iptc) {?>
				<h4>IPTC</h4>
				ul id="<?php echo $current_img->filename; ?>-iptc">
			<?php } ?>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
	<hr>
</div>



<?php include './footer.php'; ?>