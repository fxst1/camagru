<?php
	include_once 'database.php';
	include_once '../utils.php';

	session_start();
	session_destroy();
	try
	{
		$bdd = new PDO("mysql:host=localhost;charset=utf8;port=8080", $DB_USER, $DB_PASSWORD);
		$bdd->query("DROP DATABASE camagrudb");
		$bdd->query("CREATE DATABASE IF NOT EXISTS camagrudb;");
		$bdd->exec("USE camagrudb;");
		$bdd->exec("CREATE TABLE `user`(
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`pass` varchar(256) NOT NULL,
			`name` varchar(16) NOT NULL,
			`email` varchar(64) NOT NULL			
		);");

		clearFiles($bdd, '../imgs');

		$bdd->exec("CREATE TABLE `image`(
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`user` int NOT NULL,
			`like` int DEFAULT 0,
			`dlike` int DEFAULT 0,
			`filename` varchar(256) NOT NULL,
			`book` int NOT NULL,
			`time_c` timestamp DEFAULT CURRENT_TIMESTAMP
		);");

		$bdd->exec("CREATE TABLE `like`(
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`user` int NOT NULL,
			`image` int NOT NULL,
			`bool` int NOT NULL
		);");

		$bdd->exec("CREATE TABLE `comment`(
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`user` int NOT NULL,
			`image` int NOT NULL,
			`comment` varchar(200) NOT NULL,
			`time_c` timestamp DEFAULT CURRENT_TIMESTAMP
		);");

		$files = array('imgs/filters/mamouth.png', 'imgs/filters/adium.png', 'imgs/filters/smiley.png', 'imgs/filters/tablet.png', 'imgs/filters/banane.png', 'imgs/filters/machin.png', 'imgs/filters/barbe.png');
		foreach ($files as $f)
			$bdd->query("INSERT INTO image (user, filename, book) VALUES (0, '$f', 0)");
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}

?>
