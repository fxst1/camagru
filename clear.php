<?php
	include_once 'utils.php';
	include_once 'connect.php';

	if (isset($_SESSION))
	{
		unset($_SESSION['url']);
		if (isset($_SESSION['backurl']))
			unlink($_SESSION['backurl']);
		unset($_SESSION['backurl']);
		clearFiles($bdd, 'imgs');
	}
?>