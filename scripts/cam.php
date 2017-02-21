<?php

	session_start();
	include_once '../connect.php';
	include_once '../utils.php';
	include_once '../gen.php';

	//if (!isset($_POST['data']))
	//	return (header('Location: ../redirect.php'));
	//print_r($_POST['data']);

	$array = json_decode($_POST['obj']);
	$order = json_decode($_POST['order']);

	if (isset($_POST['data']))
	{
		$file = getRandomFilename('../imgs') . '.png';
		$img = str_replace('data:image/png;base64,', '', $_POST['data']);
		$img = str_replace(' ', '+', $img);
		file_put_contents($file, base64_decode($img));
		$_SESSION['url'] = apercu($bdd, 'png', $order, $array, $file, 500, 376);
	}
	else
	{
		$_SESSION['url'] = apercu($bdd, 'png', $order, $array, $_POST['back'], 500, 376);
	}

	echo basename($_SESSION['url']);
?>