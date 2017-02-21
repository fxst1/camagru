<?php

	include_once '../connect.php';

	session_start();
	if (!isset($_POST['id']) || !isset($_SESSION['user']))
		return (header('location: redirect.php'));

	$id = $_POST['id'];

	$req = $bdd->query("SELECT id, filename FROM image WHERE id = '$id';");
	$img = $req->fetch(PDO::FETCH_ASSOC);
?>
	<img src="data:image/<?php echo mime_content_type($img['filename']);?>;base64,<?php echo base64_encode(file_get_contents($img['filename']));?>" class="book2">
	<br />
	<?php
	$req3 = $bdd->query("SELECT user, comment FROM comment WHERE image = '$id' ORDER BY time_c;");
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