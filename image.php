<?php

	$id_image = $_GET['image'];

	session_start();

	if (!isset($_SESSION['user']))
		return (header('location: redirect.php'));

	$id_user = $_SESSION['user'];

	include_once 'clear.php';
	include_once 'connect.php';
	include_once 'gen.php';
	include_once 'utils.php';

	$req = $bdd->query("SELECT `like`, filename FROM image WHERE id = '$id_image';");
	if ($req->rowCount() == 0)
		return (header('location: redirect.php'));
	$data = $req->fetch(PDO::FETCH_ASSOC);

	$req2 = $bdd->query("SELECT bool FROM `like` WHERE user = '$id_user' AND image = '$id_image';");

	if (isset($_POST['like']))
	{
		$bdd->query("UPDATE image SET `like` = `like` + 1 WHERE id = '$id_image';");
		$bdd->query("INSERT INTO `like` (user, image, bool) VALUES ('$id_user', '$id_image', '1');");
		return (header("location: image.php?image=$id_image"));
	}
	else if (isset($_POST['dislike']))
	{
		$bdd->query("UPDATE image SET `dlike` = `dlike` + 1 WHERE id = '$id_image';");
		$bdd->query("INSERT INTO `like` (user, image, bool) VALUES ('$id_user', '$id_image', '0');");
		return (header("location: image.php?image=$id_image"));
	}
	if (!empty($_POST['comment']))
	{
		$comment = protect($_POST['comment']);
		$bdd->query("INSERT INTO comment (user, image, comment) VALUES ('$id_user', '$id_image', '$comment');");
		$req = $bdd->query("SELECT user.email FROM image INNER JOIN user ON image.user = user.id WHERE image.id = '$id_image';");
		$comment = $_POST['comment'];

		sendNotification($req->fetch(PDO::FETCH_ASSOC)['email'], 'imgs/' . $data['filename'], $comment);

		return (header("location: image.php?image=$id_image"));
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/camagru.css">
	<meta charset="utf-8">
	<title>Camagru - Editer</title>
</head>
<body>
	<?php header_site(); ?>
	<form method="post">
		<article>

			<section>
				<img src="imgs/<?php echo $data['filename']; ?>">
			</section>

			<aside>
			<?php

				$req3 = $bdd->query("SELECT user, comment FROM comment WHERE image = '$id_image' ORDER BY time_c;");
				while ($data3 = $req3->fetch(PDO::FETCH_ASSOC))
				{
					$id_user_comment = $data3['user'];
					$usr = $bdd->query("SELECT name FROM user WHERE id = '$id_user_comment';");
					?>
					<b><?php echo $usr->fetch(PDO::FETCH_ASSOC)['name']; ?></b> :
					<?php echo $data3['comment']; ?> <br />
					<?php
				}
			?>
				<textarea name="comment"></textarea>
				<input type="submit" name="add" value="Ajouter un commentaire">
			</aside>

			<div>
			<?php
				if ($req2->rowCount() == 0)
				{
					?>
					<input class="like" type="submit" name="like" value="J'aime">
					<input class="dislike" type="submit" name="dislike" value="Je n'aime pas">
					<?php
				}
			?>
			</div>

		</article>
	</form>
</body>
</html>