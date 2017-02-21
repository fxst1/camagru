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
	<script type="text/javascript" src="scripts/ajax.js"></script>
	<meta charset="utf-8">
	<title>Camagru - Galerie</title>
</head>
<body>
	<?php header_site(); ?>
	<a href="book.php">Votre album</a>
	<section class="flex">
		<?php
		$req = $bdd->query("SELECT filename, id, `like`, dlike FROM image WHERE book = 1 ORDER BY time_c DESC;");
		if ($req->rowCount() > 0)
		{
			while ($data = $req->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<div onmouseenter="show(<?php echo $data['id']; ?>)">
					<span class="like"><?php echo $data['like']; ?></span>
					<a href="image.php?image=<?php echo $data['id']; ?>">
						<img class="little-view" src="imgs/<?php echo $data['filename']; ?>">
					</a>
					<span class="dislike"><?php echo $data['dlike']; ?></span>
				</div>
				<?php
			}
		}
		?>
	</section>

	<aside id="show"></aside>

	<script type="text/javascript">

	function	show(id)
	{
		var balise = document.getElementById('show');
		var	xhr = getXMLHttpRequest();

		if (xhr)
		{
    		xhr.onreadystatechange = function()
			{
				if (xhr.readyState == 4 &&
				(xhr.status == 200 || xhr.status == 0))
				{
					balise.innerHTML = xhr.responseText;
				}
			};
			xhr.open("post", "scripts/getImage.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send("id=" + id);
		}
	}

	</script>
</body>
</html>