<?php
function template_header($title)
{
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>SQL_TP5_Utilisation de MySQL avec PHP</h1>
            <a href="../index.php"><i class="fa fa-home"></i>Accueil</a>
    		<a href="../crud/read.php"><i class="fa fa-film"></i>Films</a>
    	</div>
    </nav>
<div class="container">
EOT;
}
