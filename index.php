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
		if (!empty($_POST['name']) &&
			!empty($_POST['pw1']) && !empty($_POST['pw2']) &&
			!empty($_POST['e1']) && !empty($_POST['e2']))
		{
			if (strlen(protect($_POST['name'])) >= 15)
				$error_incs .= "Votre identifiant doit contenir 15 caracteres MAX<br />";
			if (strlen(protect($_POST['e1'])) >= 64)
				$error_incs .= "Votre email trop long<br />";
			if ($_POST['pw1'] !== $_POST['pw2'])
				$error_incs .= "Les mots de passe doivent etre identiques<br />";
			if ($_POST['e1'] !== $_POST['e2'])
				$error_incs .= "Les emails doivent etre identiques<br />";
			if (empty($error_incs))
			{
				$name = protect($_POST['name']);
				$email = protect($_POST['e1']);
				$pass = hash('whirlpool', protect($_POST['pw1']));
				$req = $bdd->query("SELECT id FROM user WHERE email = '$email' OR name = '$name';");
 				if ($req->rowCount() == 0)
				{
					mail($email, "Camagru - inscription", "Vous etes $name avec l'email suivant: $email !\n\rBienvenue !");
					$error_incs = "Un email vous a etes envoyer<br />";
					$bdd->query("INSERT INTO user (name, email, pass) VALUES ('$name', '$email', '$pass');");
				}
				else
					$error_incs .= "Email ou nom deja utilise<br />";
			}
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
	<title>Camagru - Acceuil</title>
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
				<a href="recup.php">Identifiants perdus ?</a>
			</div>
		</form>
		<span class="error"><?php if (!empty($error_log)) echo $error_log; ?></span>
	</header>
	<form method="post" action="#">
		<div class="main">
			<fieldset class="container">
				<legend class="correct">Creer un compte</legend>

				<span>Nom d'utilisateur</span>
				<div class="name">
					<input class="item" type="text" name="name" placeholder="Nom d'utilisateur">
				</div>

				<span>Email</span>
				<div class="email">
					<input class="item" type="email" name="e1" placeholder="Email">
					<input class="item" type="email" name="e2" placeholder="Confirmation de votre email">
				</div>

				<span>Mot de passe</span>
				<div class="pass">
					<input id="p1" class="item" type="password" name="pw1" placeholder="Mot de passe">
					<input id="p2" class="item" type="password" name="pw2" placeholder="Confirmer votre mot de passe">
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