<?php

	include_once '../connect.php';
	session_start();

	if (!isset($_SESSION['user']))
	{
		echo "Vous n'avez rien a faire ici ...";
	}
	else
	{
		$id = $_SESSION['user'];
		$bdd->query("DELETE FROM user WHERE id = '$id';");
		$bdd->query("DELETE FROM image WHERE user = '$id';");
		$bdd->query("DELETE FROM comment WHERE user = '$id';");
		echo "Toutes vos donnees ont ete supprimer\nVous allez etre rediriger...";
		session_destroy();
	}
?>