<?php

/**
 * Retrieve app details for use in php code
 * @param $arg | Current Options: home, abspath, albums_dir, albums_abspath
 * @return string
 */

class Booru {
	private $db = 'booru', $db_user = 'root', $db_host = 'localhost', $db_pass;
	public $home = '/chillbooru',
		$abspath = __DIR__,
		$albums_dir = 'albums',
		$albums_abspath,
		$album_data_xml = 'data/album_data.xml',
		$thumb_size = 150;
	
	public function init_db() {
		mysql_connect($this->db_host,$this->db_user,$this->db_pass);
		@mysql_select_db($this->db) or die( "Unable to select database");
	}
}

class Albums {
	public $albums;
		
	private function get_albums() {
		global $booru;
		$albums =  glob( $booru->abspath.'/'.$booru->albums_dir.'/*', GLOB_ONLYDIR );
		
		return $albums;
	}
	
	public function create_folders_array() {
		foreach ( $this->get_albums() as $folder ) {
			$folders[basename($folder)] = $folder;
		}
		$this->albums = $folders;
	}
}

class Album {
	private $folder_abspath, $folders, $thumbs_abspath; 
	public $name, $contents, $images, $image_list, $folder_url, $thumbs_url; 
	
	/**
	 * Builds the contents of the new Album, and fills all variables except $this->images
	 * @param $folder | The abspath of the folder
	 * @return none
	 */
	function build_contents($folder) {
		global $booru;
		
		$this->folder_abspath = $folder;
		$this->name = basename($this->folder_abspath);
		$this->folder_url = $booru->home.'/'.$booru->albums_dir.'/'.$this->name;
		
		$this->build_images();
		
		/*$this->contents = array();
		foreach ( glob($folder.'/*') as $file ) {
			if ( $file != '.' && $file != '..' ) :
				$this->contents[basename($file)] = array( 'filename' => $file, 'mimetype' => mime_content_type($file) );
			endif;
		}*/
	}
	
	/**
	 * Prints $this->contents within pre tags
	 * @return array | $this->contents
	 */
	function print_contents() {
		echo "<h2>$this->folder_abspath</h2>\n<pre>";
		
		echo "<pre>\n";
		print_r($this->contents);
		echo "\n</pre>";
		
		return $this->contents;
	}
	
	/**
	 * Creates an array of the image abspaths in this object
	 * @param $folder | Takes an abspath to create a new Album; null for existing Album
	 * @return none
	 */
	function build_image_list($folder = NULL) {
		if ($folder = NULL) $folder = $this->folder_abspath;
		$this->thumbs_abspath = $this->folder_abspath.'/thumbs'; 
		$this->thumbs_url = $this->folder_url.'/thumbs';
		
		foreach ( glob($this->folder_abspath.'/{*.JPG,*.JPEG,*.jpg,*.jpeg}', GLOB_BRACE) as $image ) {
			$this->image_list[] = array( 'url' => $this->folder_url.'/'.basename($image), 'abspath' => $this->folder_abspath.'/'.basename($image) );
		}
	}
	
	function build_images() {
		$this->build_image_list();
		$this->images = array();
		
		foreach ($this->image_list as $id => $image) {
			$img_name = $this->name.'_img'.$id;
			$$img_name = new Image;
			$$img_name->build_image($image, $img_name);
			$this->images[$id] = $$img_name ;
		}
	}
	
	function build_thumbs() {
		global $booru;
		
		$i=0;
		foreach ($this->image_list as $id => $image) {
			$im = imagecreatefromjpeg($image['abspath']);  
			
			$ox = imagesx($im);  
		    $oy = imagesy($im);  
		  	
	        $nx = $booru->thumb_size;  
		    $ny = floor($oy * ($booru->thumb_size / $ox));
		    $nm = imagecreatetruecolor($nx, $ny);
			  	
		    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
		  	
		    if(!file_exists($this->thumbs_abspath)) {
		    	if( !mkdir($this->thumbs_abspath) ) {  
		        	die("There was a problem. Please try again!");  
		    	}  
		    }  
		  
		    if ( !file_exists($this->thumbs_abspath . '/' . basename($image['url'])) ) {
		    	imagejpeg($nm, $this->thumbs_abspath . '/' . basename($image['url']));  
		    }
		    $tn = '<p><img style="float:right;" src="' . $this->thumbs_url . '/' . basename($image['url']) . '" alt="image" />';  
		    $tn .= 'Congratulations. Your file has been successfully uploaded, and a thumbnail has been created.</p>';  
		    echo $tn;  
			
		    $i++; if ($i > 80) set_time_limit(60);
		}
	}
	
	/**
	 * Echoes an unordered list of links to the images in this Album, with a title 
	 * (for debugging)
	 * @return none;
	 */
	function link_to_images() {
		global $booru;
		$this->build_image_list();
		
		echo "<h2>Images from $this->name:</h2>\n";
		echo "<ul class=\"imgs\">\n";
		foreach ($this->images as $id => $image) {
			$img = basename($image['url']);
			echo "<li><a href=\"$booru->home/image.php?curr_album=$this->name&curr_image=$id\">$img</a></li>\n";
		}
		echo "</ul>";
	}
	
}

class Image {
	private $abspath, $info;
	public $name, $filename, $url, $size, $layout, $iptc, $xmp, $exif;
	
	function build_image($image, $name) {
		$this->abspath = $image['abspath'];
		$this->url = $image['url'];
		$this->name = ucwords( str_replace("_", " ", $name) );
		$this->filename = basename($this->abspath);
		
		$this->exif = exif_read_data($this->abspath);
		$this->size = getimagesize($this->abspath, $info);
		if ( $this->size[0] > $this->size[1] ) {
			$this->layout = 'landscape';
		} elseif ( $this->size[0] < $this->size[1] ) {
			$this->layout = 'portrait';
		} elseif ( $this->size[0] == $this->size[1] ) {
			$this->layout = 'square';
		} else { $this->layout = ''; }
		if (isset ($info['APP13'])) $this->iptc = $this->info['APP13'];
	}
	
	function list_exif() {

		$output = "";
		$output .= "<li><span class=\"exifTitle\">Date:</span><span>{$this->convertExifToTimestamp($this->exif['DateTime'], 'd.m.Y')}</span></li>\n";
		$output .= "<li><span class=\"exifTitle\">Aperture:</span><span>{$this->exif['COMPUTED']['ApertureFNumber']}</span></li>\n";
		$output .= "<li><span class=\"exifTitle\">Shutter Speed:</span><span>{$this->exif['ExposureTime']} sec</span></li>\n";
		$output .= "<li><span class=\"exifTitle\">Focal Length:</span><span>{$this->exif['FocalLength']}</span></li>\n";
		$output .= "<li><span class=\"exifTitle\">ISO:</span><span>{$this->exif['ISOSpeedRatings']}</span></li>\n";
		$output .= "<li><span class=\"exifTitle\">Camera:</span><span>{$this->exif['Model']}</span></li>\n";
		
		echo $output;
		return $output;
		
	}
	
	function convertExifToTimestamp($exifString, $dateFormat) {
		$exifPieces = explode(":", $exifString);
		return date($dateFormat, strtotime($exifPieces[0] . "-" . $exifPieces[1] . "-" . $exifPieces[2] . ":" . $exifPieces[3] . ":" . $exifPieces[4]));
	}
}
?>