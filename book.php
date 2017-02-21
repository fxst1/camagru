<?php
	session_start();

	include_once 'gen.php';
	include_once 'connect.php';
	include_once 'clear.php';

	if (!isset($_SESSION['user']))
		return (header('Location: redirect.php'));
	$id = $_SESSION['user'];

	if (count($_POST) > 0)
	{
		foreach ($_POST as $key => $value)
		{
			if (!strncmp("del", $key, 3))
			{
				$s = intval(substr($key, 3));
				$bdd->query("DELETE FROM image WHERE id = '$s';");
			}
			else if (!strncmp("cdel", $key, 4))
			{
				$s = intval(substr($key, 4));
				$bdd->query("DELETE FROM comment WHERE id = '$s';");
			}
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/camagru.css">
	<title>Camagru - Album</title>
</head>
<body>
	<?php header_site(); ?>
	<section id="main-section">
		<div>
		<form method="post">
		<?php
			$req = $bdd->query("SELECT filename, id FROM image WHERE book = 1 AND user = '$id';");
			if ($req->rowCount() == 0)
			{
				?>
				<h1>Vous n'avez pas de photos</h1>
				<?php
			}
			else
			{
				while ($data = $req->fetch(PDO::FETCH_ASSOC))
				{
					$id_img = $data['id'];
					?>
					<article>
						<img class="book" src="<?php echo 'imgs/' . $data['filename']; ?>" >
						<?php
						$req2 = $bdd->query("SELECT id, comment , user FROM comment WHERE image = '$id_img';");
						echo "<div>";
						if ($req2->rowCount() > 0)
						{
							while ($data2 = $req2->fetch(PDO::FETCH_ASSOC))
							{
								$id_comment = $data2['id'];
								$usr = $data2['user'];
								$req3 = $bdd->query("SELECT name FROM user WHERE id = '$usr';");
								$name = $req3->fetch(PDO::FETCH_ASSOC)['name'];
								echo "<b>" . $name . "</b>: " . $data2['comment'] . "<input type=\"submit\" name=\"cdel$id_comment\" value='-'><br />";
							}
						}
						echo "</div>";
					?>
					<input type="submit" name="del<?php echo $data['id']; ?>" value="Supprimer cette magnifique photo">
					</article>
					<?php
				}
			}
			?>
		</form>
		</div>
	</section>
</body>
</html>