<?php
	session_start();

	include_once 'clear.php';
	include_once 'gen.php';
	include_once 'connect.php';

	if (!isset($_SESSION['user']))
		return (header('Location: redirect.php'));
	$id = $_SESSION['user'];
	$error = "";
	if (count($_POST) > 0)
	{
		$req = $bdd->query("SELECT pass FROM user WHERE id = '$id';");
		$data = $req->fetch(PDO::FETCH_ASSOC);
		if (!empty($_POST['pw1']) && !empty($_POST['pw2']))
		{
			if ($_POST['pw1'] == $_POST['pw2'])
			{
				$pass = hash('whirlpool', $_POST['pw1']);
				if ($pass === $data['pass'])
				{
					if (!empty($_POST['name']))
					{
						$name = $_POST['name'];
						$req = $bdd->query("SELECT id FROM user WHERE name = '$name';");
						if ($req->rowCount() === 0)
							$bdd->query("UPDATE user SET name = '$name' WHERE id = '$id';");
						else
							$error = "Nom deja utiliser<br />";
					}
					if (!empty($_POST['e1']) && !empty($_POST['e2']))
					{
						if ($_POST['e1'] === $_POST['e2'])
						{
							$e = $_POST['e1'];
							$req = $bdd->query("SELECT id FROM user WHERE email = '$e';");
							if ($req->rowCount() === 0)
								$bdd->query("UPDATE user SET email = '$e' WHERE id = '$id';");
							else
								$error = "Email deja utiliser<br />";
						}
						else
							$error = "Mauvais email<br />";
					}
					if (!empty($_POST['npw1']) && !empty($_POST['npw2']))
					{
						if ($_POST['npw1'] === $_POST['npw2'])
						{
							$npw = hash('whirlpool', $_POST['npw1']);
							$bdd->query("UPDATE user SET pass = '$npw' WHERE id = '$id';");
						}
						else
							$error = "Mauvais nouveau mot de passe<br />";
					}
				}
				else
					$error = "Mauvais mot de passe<br />";
			}
			else
				$error = "Les mots de passe ne sont pas identiques<br />";
		}
		else
			$error = "Le mot de passe est obligatoire<br />";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<title>Camagru - Modifications</title>
</head>
<body>
	<?php header_site(); ?>
	<form method="post" action="#">
		<div class="main">
			<fieldset class="container">
				<legend>Modifier vos informations</legend>

				<span>Nom d'utilisateur</span>
				<div class="name">
					<input class="item" type="text" name="name" placeholder="Nom d'utilisateur">
				</div>

				<span>Nouvel Email</span>
				<div class="email">
					<input class="item" type="email" name="e1" placeholder="Email"  value="">
					<input class="item" type="email" name="e2" placeholder="Confirmation de votre email">
				</div>


				<span>Nouveau Mot de passe</span>
				<div class="pass">
					<input id="np1" class="item" type="password" name="npw1" placeholder="Mot de passe">
					<input id="np2" class="item" type="password" name="npw2" placeholder="Confirmer votre mot de passe">
				</div>

				<span>* Mot de passe</span>
				<div class="pass">
					<input id="p1" class="item" type="password" name="pw1" placeholder="Mot de passe">
					<input id="p2" class="item" type="password" name="pw2" placeholder="Confirmer votre mot de passe">
				</div>


				<?php if (!empty($error))
					echo "<span class='error'>$error</span>";
				?>
				<input id="btn" type="submit" name="submit" value="Valider !">
				
				<br/>
				<span class="log">* : Obligatoire</span>
			</fieldset>
		</div>
	</form>

</body>
</html>