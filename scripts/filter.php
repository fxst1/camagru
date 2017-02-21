<?php

	session_start();
	include_once '../connect.php';
	$id_user = $_SESSION['user'];
	$img_filtres = $bdd->query("SELECT filename, id FROM image WHERE (user = $id_user OR user = 0) AND book = 0;");
	if ($img_filtres->rowCount() == 0)
	{
		echo '<h1>Pas de photo uploader';
	}
	else
	{
		?>
		<legend>Mes filtres</legend>
		<?php
		while ($img = $img_filtres->fetch(PDO::FETCH_ASSOC))
		{
			$type = mime_content_type("../" . $img['filename']);
			$data = base64_encode(file_get_contents("../" . $img['filename']));
			$data = "data:image/$type;base64,$data";
			?>
			<div class="divFilter" id="filter<?php echo $img['id']; ?>">
				<img src="<?php echo $data;?>" class="filtre" onclick="select(<?php echo $img['id'] ?>, '<?php echo $data; ?>')">
			</div>
			<?php
		}
	}?>
