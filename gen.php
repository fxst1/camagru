<?php

	function	header_site()
	{
		?>
		<header>
			<div>
				<a href="camagru.php"><h1 class="titre">Camagru</h1></a>
			</div>
			<div class="container">
				<a href="profil.php"><img class="icon profil" src="imgs/private/photo.png"></a>
				<a href="galerie.php"><img class="icon" src="imgs/private/book.png"></a>
				<a href="logout.php"><img class="icon" src="imgs/private/logout.png"></a>
			</div>
		</header>
		<?php
	}

	function	apercu($bdd, $type, $order, $array, $back, $w, $h)
	{
		$out = null;
		if (!$back)
			$img = imagecreate($w, $h);
		else
		{
			$ret = resize($w, $h, '../imgs/tmpresized', $back);
			$img = imagecreatefromstring(file_get_contents($ret));
		}

		//imagealphablending($img, false);
		imagesavealpha($img, true);

		foreach ($order as $key => $v)
		{
			if (!empty($v) && !empty($array[$v]))
			{
				$value = $array[$v];
				$req = $bdd->query("SELECT filename FROM image WHERE id = '$v';");

				$ret = resize($value[0], $value[1], '../imgs/tmpresized', '../' . $req->fetch()['filename']);

				$src_im_scale = imagecreatefromstring(file_get_contents($ret));

				imagealphablending($src_im_scale, false);
				imagesavealpha($src_im_scale, true);

				imagecopyresized($img, $src_im_scale, $value[2] , $value[3], 0, 0, imagesx($src_im_scale), imagesy($src_im_scale), $value[0], $value[1]);
				unlink($ret);
				imagedestroy($src_im_scale);
			}
		}

		switch ($type) {
			case 'jpeg':
				$out = getRandomFilename('../imgs') . '.jpeg';
				imagejpeg($img, $out);
				break;
		
			case 'png':
				$out = getRandomFilename('../imgs') . '.png';
				imagepng($img, $out);
				break;

			default:
				$out = getRandomFilename("../imgs") . '.jpeg';
				imagejpeg($img, $out);
				break;
		}
		imagedestroy($img);
		return ($out);
	}



function resize($newWidth, $newHeight, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
    imagedestroy($tmp);
    return ("$targetFile.$new_image_ext");
}



?>