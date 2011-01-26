<?php
require_once './booru-init.php';
require './actions.php';

?>

<html>
	<head>
		<title>My Own Imagehost</title>
		<link rel="stylesheet" type="text/css" href="./css/style.css" />
	</head>
	<body>
		<div id="masthead">
			<div class="wrapper">
				<h1><a href="<?php echo $booru->home; ?>">My Own Imagehost</a></h1>
			</div>
		</div>
		<div class="clear"></div>
		<div class="wrapper">
			<div id="content">