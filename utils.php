<?php

	function	isSameFilename($filename, $path)
	{
		$a = scandir($path);
		$len = strlen($filename);
		$i = 0;
		foreach ($a as $value)
		{
			if (!strncmp($value, $filename, $len))
				return (true);
		}
		return (false);
	}

	function	getRandomNum($qte = 10)
	{
		$tab = "012345679";
		$i = 0;
		$name = "";

		while ($i < $qte)
		{
			$name .= $tab[rand() % 10];
			$i++;
		}
		return ($name);
	}

	function	getRandomString($qte = 10)
	{
		$tab = "abcdefghijklmnopqrstuvwxyz";
		$i = 0;
		$name = "";

		while ($i < $qte)
		{
			$name .= $tab[rand() % 26];
			$i++;
		}
		return ($name);
	}

	function	getRandomFilename($path)
	{
		$name = getRandomString();
		if (isSameFilename($name, $path))
			return (getRandomFilename($path));
		return ("$path/$name");
	}

	function	protect($str)
	{
		$i = 0;
		$n = strlen($str);
		$ret = "";

		while ($i < $n)
		{
			if ($str[$i] == '\'' || $str[$i] == "\"" || $str[$i] == '\\')
				$ret .= "\\" . $str[$i];
			else
				$ret .= $str[$i];
			$i++;
		}
		return (htmlspecialchars($ret));
	}

	function	clearFiles($bdd, $path)
	{
		$dir = scandir($path);
		foreach ($dir as $value)
		{
			$filename = protect($value);
			$request = $bdd->query("SELECT id FROM image WHERE filename = '../imgs/$value';");
			if ((!$request || $request->rowCount() == 0) && !is_dir("$path/$value"))
				unlink("$path/$value");
		}
	}

	function	getPassword()
	{
		return (getRandomString(3) . getRandomNum(3));
	}

	function	isPassword($s)
	{
		$ok = 0;
		$i = 0;
		$n = strlen($s);
		if ($n < 6)
			return (false);
		while ($i < $n)
		{
			if (ctype_alpha($s[$i]))
				$ok |= 1;
			else if (ctype_digit($s[$i]))
				$ok |= 2;
			else
				return (false);
			$i++;
		}
		return ($ok === 3);
	}

	function	sendNotification($to, $file, $comment)
	{
		//define the subject of the email 
		$subject = 'Camagru - Notification'; 

  		$separator = md5(time());

  		// carriage return type (we use a PHP end of line constant)
  		$eol = "\r\n";

		// attachment name
		$filename = basename($file);//store that zip file in ur root directory
		$attachment = chunk_split(base64_encode(file_get_contents($file)));
		$type = mime_content_type($file);


		// main header
		$headers = "MIME-Version: 1.0".$eol; 
		$headers .= "Content-Type: multipart/alternative; boundary=\"".$separator."\"";

		// no more headers after this, we start the body! //

		$body = "--".$separator.$eol;
		$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
		$body .= "This is a MIME encoded message.".$eol;

		// message
		$body .= "--".$separator.$eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
		$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
		$body .= "Nouveau commentaire: $comment".$eol;

		// attachment
		$body .= "--".$separator.$eol;
		$body .= "Content-Type: $type; name=\"".$filename."\"".$eol; 
		$body .= "Content-Transfer-Encoding: base64".$eol;
		$body .= "Content-Disposition: attachment".$eol.$eol;
		$body .= $attachment.$eol;
		$body .= "--".$separator."--";

		//send the email 
		return mail( $to, $subject, $body, $headers );
	}

?>