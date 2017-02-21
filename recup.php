<?php

	include_once 'connect.php';
	include_once 'utils.php';

	session_start();
	if (isset($_SESSION['user']))
		return (header('Location:camagru.php'));
	$error_log = "";
	$error_incs = "";

	if (isset($_POST['submit']))
	{
		if (!empty($_POST['name']))
		{
			$name = protect($_POST['name']);
			$req = $bdd->query("SELECT id, email FROM user WHERE (email = '$name' OR name = '$name');");

 			if ($req->rowCount() === 1)
			{
				$usr = $req->fetch(PDO::FETCH_ASSOC);
				$id = $usr['id'];
				$pass = protect(getPassword());
				print_r($usr);
				mail($usr['email'], "Camagru - recuperation", "Votre mot de passe est desormais le suivant:\n\r$pass");
				$error_incs = "Un email vous a etes envoyer<br />";
				$pass = hash('whirlpool', $pass);
				$bdd->query("UPDATE user SET pass = '$pass' WHERE id = '$id';");
			}
			else
				$error_incs .= "Email ou nom deja introuvable<br />";
		}
		else
			$error_incs .= "Tous les champs sont obligatoires<br />";
	}

	if (isset($_GET['submit']))
	{
		if (!empty($_GET['name']) && !empty($_GET['pass']))
		{
			$name = protect($_GET['name']);
			$pass = hash('whirlpool', protect($_GET['pass']));
			$req = $bdd->prepare("SELECT id FROM user WHERE (email = '$name' OR name = '$name') AND pass = '$pass';");
			$req->execute();

			if ($req->rowCount() == 1)
			{
				$_SESSION['user'] = $req->fetch()['id'];
				return (header('Location: camagru.php'));
			}
			else
				$error_log .= "Email ou nom introuvale";
		}
		else
			$error_log .= "Tous les champs sont obligatoires";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/header.css">	
	<title>Camagru - Recuperation</title>
</head>
<body>
	<header>
		<span class="titre">
			Camagru
		</span>
		<form method="get" action="#">
			<div>
				<input class="login" type="text" name="name" placeholder="Nom ou email">
				<input class="login" type="password" name="pass" placeholder="Mot de passe">
				<input class="login" type="submit" name="submit" value="Se connecter">
				<a href="forgetPass.php">Identifiants perdus ?</a>
			</div>
		</form>
		<span class="error"><?php if (!empty($error_log)) echo $error_log; ?></span>
	</header>
	<a href="index.php">Revenir a l'index</a>
	<form method="post" action="#">
		<div class="main">
			<fieldset class="container">
				<legend class="correct">Recuperation du compte</legend>

				<span>Nom d'utilisateur ou email</span>
				<div class="name">
					<input class="item" type="text" name="name" placeholder="Nom d'utilisateur ou email">
				</div>
				<?php if (!empty($error_incs))
					echo "<span class='error'>$error_incs</span>";
				?>
				<input id="btn" type="submit" name="submit" value="Valider !">
			</fieldset>
		</div>
	</form>
	<footer>
		<hr>
		By:<b>fjacquem@student.42.fr</b>
	</footer>
</body>
</html>