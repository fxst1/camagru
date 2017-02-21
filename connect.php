<?php
	include_once 'config/database.php';

	$bdd = null;
	try
	{
		$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
?>