<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title><?php bloginfo('title'); ?></title>
</head>
<body>
	<div id="wrapper">
    <header class="col col--no-gutters">
        <div class="grid grid--container">
            <h1>header</h1>
        </div>
    </header>
    <main>
<?php wp_head(); ?>
