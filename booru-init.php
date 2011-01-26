<?php
require_once './objects.php';
require_once './functions.php';

/**
 * Initiate the app
 */
$booru = new Booru;
$booru->init_db();
$albums = new Albums;

?>
