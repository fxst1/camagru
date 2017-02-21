<?php

	include_once '../connect.php';
	include_once '../gen.php';
	include_once '../utils.php';

	session_start();

	$array = json_decode($_GET['obj']);
	$order = json_decode($_GET['order']);

	if (!isset($_SESSION['user']))
		return (header('Location: ../redirect.php'));

	if (!isset($_SESSION['url']))
	{
		$_SESSION['url'] = apercu($bdd, 'png', $order, $array, isset($_GET['back']) ? $_GET['back'] : null, 500, 300);
	}
	$id = $_SESSION['user'];

	if (isset($_POST['ok']))
	{
		$out = $_SESSION['url'];
		$bdd->query("INSERT INTO image (user, filename, book) VALUES ('$id', '$out', '1');");
		$key = intval($bdd->lastInsertId());

		if (!empty($_POST['comment']))
		{
			$cnt = protect($_POST['comment']);
			$bdd->query("INSERT INTO comment (user, image, comment) VALUES ('$id', '$key', '$cnt');");	
		}
		echo '<script>alert("Votre image a ete sauvegarder");window.close();</script>';
		include_once '../clear.php';
	}
	else if (isset($_POST['ko']))
	{
		unlink($_SESSION['url']);
		include_once '../clear.php';
		echo '<script>window.close();</script>';
	}
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/camagru.css">
	<meta charset="utf-8">
	<title>Camagru - Apercu</title>
</head>
<body>
	<img class="apercu" src="<?php echo $_SESSION['url']; ?>">
	<br />
	<form method="POST">
		<input type="text" name="comment" placeholder="Comment me !">
		<br />
		<input type="submit" name="ok" value="sauvegarder">
		<input type="submit" name="ko" value="abandonner">
	</form>

</body>
</html>