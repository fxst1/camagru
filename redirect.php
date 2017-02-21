<?php
	session_start();
	if (isset($_SESSION['user']))
		return (header('Location: index.php'));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Vous n'avez rien a faire ici</title>
</head>
<body>
	Bonjour ! Vous etes perdu ? Vous avez essayer changer une url ? Ou bien vous etes victime d'une deconnection innopinee
	<br />
	Ne vous inquiete pas ! Camagru c'est <a href="index.php">ici</a>
</body>
</html>