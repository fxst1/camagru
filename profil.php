<?php
	session_start();

	include_once 'clear.php';
	include_once 'gen.php';
	include_once 'connect.php';

	if (!isset($_SESSION['user']))
		return (header('Location: redirect.php'));
	$id = $_SESSION['user'];
	$req = $bdd->query("SELECT name, email FROM user WHERE id = '$id';");
	$usr = $req->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<title>Camagru - profil</title>
</head>
<body>
	<?php header_site(); ?>
	<div class="main">
		<fieldset class="container">
			<legend>Mes informations</legend>
				<span>Mon nom d'utilisateur: </span>
				<div class="name">
					<span class="item"><?php echo $usr['name']; ?></span>
				</div>

				<span>Mon email: </span>
				<div class="name">
					<span class="item"><?php echo $usr['email']; ?></span>
				</div>
		</fieldset>
	</div>
	<div class="main">
		<button onclick="redir()">Modifier mon compte</button>
		ou
		<button onclick="suppr()">Supprimer mon compte</button>
	</div>
	<script type="text/javascript" src="scripts/ajax.js"></script>
	<script type="text/javascript">

		function redir()
		{
			window.location.href = "modif.php";
		}

		function suppr()
		{
			if (confirm("Toutes vos donnees seront supprimer !"))
			{
				xhr = getXMLHttpRequest();
				if (!xhr)
					window.location.href = "scripts/suppr.php";
				else
				{
					xhr.onreadystatechange = function()
					{
						if (xhr.readyState == 4 &&
						(xhr.status == 200 || xhr.status == 0))
						{
							confirm(xhr.responseText);
							window.location.href = "index.php";
						}
					};
					xhr.open("GET", "scripts/suppr.php", true);
					xhr.send(null);
				}
			}
		}

	</script>
</body>
</html>