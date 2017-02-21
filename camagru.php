<?php

	session_start();

	include_once 'utils.php';
	include_once 'gen.php';
	include_once 'connect.php';

	if (!isset($_SESSION['user']))
		return (header('Location: redirect.php'));
	$id_user = $_SESSION['user'];

	if (isset($_POST['ok']))
	{
		$out = $_SESSION['url'];
		$bdd->query("INSERT INTO image (user, filename, book) VALUES ('$id_user', '$out', '1');");
		$key = intval($bdd->lastInsertId());

		if (!empty($_POST['comment']))
		{
			$cnt = protect($_POST['comment']);
			$bdd->query("INSERT INTO comment (user, image, comment) VALUES ('$id_user', '$key', '$cnt');");	
		}
		return (header('Location: galerie.php'));
	}
	else if (isset($_FILES['upload']) && !empty($_FILES['upload']['name']) && !isset($_SESSION['backurl']))
	{
		if (getimagesize($_FILES['upload']['tmp_name']))
		{
			$target = getRandomFilename('imgs') . '.' . explode('/', $_FILES['upload']['type'])[1];

			$_SESSION['backurl'] = $target;
			if (move_uploaded_file($_FILES['upload']['tmp_name'], $target))
			{
				$backdata = base64_encode(file_get_contents($target));
				$backtype = $_FILES['upload']['type'];
			}
		}
	}
	else
		include_once 'clear.php';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/camagru.css">
	<title>Camagru</title>
</head>
<body>

	<?php header_site(); ?>
	<div class="inline" id="div">
		<fieldset id="filtre-box">
		</fieldset>
		<section id="main-section">
			<span id="video-cnt"></span>
			<section class="container2 main2 top" id="video">
			<form id="upload-form" method="post" enctype="multipart/form-data">
				<?php
				if (!isset($backdata))
				{
				?>
					<input type="file" name="upload">
					<input type="submit" name="submit" value="Upload background!">
					<div class="box"></div>
					<?php
				}
				else
				{
				?>
					<img src=<?php echo "data:$backtype;base64,$backdata";?> class="box">
				<?php
				}?>
			</form>
			</section>

			<canvas id="canvas"></canvas>

			<form method="post">
				<span id="apercu"></span>
			</form>
			
			<div id="form"></div>
		</section>
	</div>

	<footer>
	<div id="side">
		<section class="flex">
		<?php
			$req = $bdd->query("SELECT id, filename FROM image WHERE user = '$id_user' AND book = 1 LIMIT 10;");

			if ($req->rowCount() != 0)
			{
				while ($img = $req->fetch(PDO::FETCH_ASSOC))
				{
					$img['filename'] = 'imgs/' . $img['filename'];
					?>
					<div>
						<img src="data:image/<?php echo mime_content_type($img['filename']);?>;base64,<?php echo base64_encode(file_get_contents($img['filename']));?>" class="book">
					</div>
					<?php
				}
			}
			else
			{
				?>
				<h1>Vous n'avez pas de photos</h1>
				<?php
			}
		?>
		</section>
	</div>
		<hr />
		By: <b>fjacquem@student.42.fr</b>
	</footer>
	<script type="text/javascript">

	function	manual()
	{
		alert("Bienvenue sur Camagru !\nPensez a NE SUROUT PAS recharger la page sous peine de perdre vos modifications!");
	}

	</script>
	<script type="text/javascript" src="scripts/ajax.js"></script>
	<script type="text/javascript" src="scripts/actions.js"></script>
	<script type="text/javascript">
		
	openVideo();

	<?php
	if (isset($backdata))
		echo 'putFiltres();';
	?>

		function	openVideo()
		{
			navigator.getUserMedia = navigator.getUserMedia ||
  									 navigator.webkitGetUserMedia ||
  									 navigator.mozGetUserMedia;

			if (navigator.getUserMedia)
			{
				navigator.getUserMedia({ audio: false, video: { width: 500, height: 500 } },
						function(stream)
					{
						window.stream = stream;
						delUpload();
						var video = document.querySelector('video');
						video.videoWidth = "500px";
						video.videoHeight = "500px";
						video.srcObject = stream;
						video.play();
					},
						function(err)
					{
						console.log("The following error occurred: " + err.name);
					}
				);
			}
			else
			{
					console.log("getUserMedia not supported");
			}
		}

		function	putFiltres()
		{
			var		xhr = getXMLHttpRequest();
			var		div = document.getElementById('filtre-box');
		//	var		video = document.getElementById('video');

		//	video.style.position = "relative";
		//	video.style.top = "-160px";

			if (xhr)
			{
				xhr.onreadystatechange = function()
				{
					if (xhr.readyState == 4 &&
					(xhr.status == 200 || xhr.status == 0))
					{
						div.innerHTML = xhr.responseText;
					}
				};
				xhr.open("GET", "scripts/filter.php", true);
				xhr.send(null);			
			}
			
		}

		function	apercu(img)
		{
			var	tag = document.getElementById('apercu');
			tag.innerHTML = '<img id="img-apercu" src="imgs/' + img + '"><input type="submit" name="ok" value="Sauvegarder" id="save">';

		}

		function	takePicture()
		{
			console.log(order);
			var obj = correctObjs();

			var	url = 'obj=' + JSON.stringify(obj) + "&order=" + JSON.stringify(order);

			var	video = document.querySelector('video');
		   	if (video)
		   	{
				var canvas = document.getElementById('canvas');
		   		canvas.width = 500;
    			canvas.height = 376;
    			canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
     			var data = canvas.toDataURL('image/png');
     			canvas.width = 0;
    			canvas.height = 0;
     			url += "&data=" + data;
    			var	xhr = getXMLHttpRequest();

    			if (xhr)
    			{
    				xhr.onreadystatechange = function()
					{
						if (xhr.readyState == 4 &&
						(xhr.status == 200 || xhr.status == 0))
						{
							apercu(xhr.responseText);
						}
					};
					xhr.open("post", "scripts/cam.php", true);
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhr.send(url);
    			}
    		}
    		else
    		{
    			var	xhr = getXMLHttpRequest();

    			if (xhr)
    			{
    				xhr.onreadystatechange = function()
					{
						if (xhr.readyState == 4 &&
						(xhr.status == 200 || xhr.status == 0))
						{
							apercu(xhr.responseText);
						}
					};
					xhr.open("post", "scripts/cam.php", true);
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					<?php
						if (isset($_SESSION['backurl']))
							echo 'xhr.send(url + "&back=" + "../'. $_SESSION['backurl'].  '");';
						else
							echo 'xhr.send(url);';
					?>
    			}    		
			}
		}
	</script>
</body>
</html>