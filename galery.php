<?php
	session_start();

	include_once 'clear.php';
	include_once 'gen.php';
	include_once 'connect.php';

	if (!isset($_SESSION['user']))
		return (header('Location: redirect.php'));
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/camagru.css">
	<meta charset="utf-8">
	<title>Camagru - Galerie</title>
</head>
<body>
	<?php header_site(); ?>
	<a href="book.php">Votre album</a>
	<hr />
	<input type="text" oninput="change(this)">
	<hr />
	<section class="flex">
		<?php
		$req = $bdd->query("SELECT filename, id, `like`, dlike FROM image WHERE book = 1 ORDER BY time_c;");
		if ($req->rowCount() > 0)
		{
			while ($data = $req->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<div>
					<a href="image.php?image=<?php echo $data['id']; ?>">
						<img  class="little-view" src="imgs/<?php echo $data['filename']; ?>">
					</a>
					<span class="like"><?php echo $data['like']; ?></span>
					<span class="dislike"><?php echo $data['dlike']; ?></span>
				</div>
				<?php
			}
		}
		?>
	</section>
	<script type="text/javascript" src="scripts/ajax.js"></script>
	<script type="text/javascript">

		function	change(it)
		{
			if (it.value != '')
			{
				var	xhr = getXMLHttpRequest();


				xhr.open("GET", "scripts/search.php?value=\"\"", true);
				xhr.send(null);
			}
		}

	</script>
</body>
</html>