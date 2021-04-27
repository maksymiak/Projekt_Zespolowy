<?php
    //argumenty z pliku bat
	$address = iconv("utf-8", "ibm852", $argv[1]); //konwersja na utf8
	$subject = iconv("utf-8", "ibm852", $argv[2]);
	$content = iconv("utf-8", "ibm852", $argv[3]);
	$imagesCount = $argv[4];

	$boundary=md5(uniqid(rand()));

    $header = "Content-Type: multipart/related; boundary=\"$boundary\"\r\n"; //nagłówek wiedomości

    $message = "--$boundary\r\n"; //wiadomość
    $message .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";

    $images = "";
    for($i=0; $i<$imagesCount; $i++){
        $images .= "<img style='display: flex;' src='cid:part$i' alt=''>";
	}

    $message .= "
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//PL'>
    <html>
      <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' charset='utf-8'>
      </head>
      <body>
        <p>$content</p>
        $images
      </body>
    </html>
    \r\n";

    for($i=0; $i<$imagesCount; $i++){
        $image = $argv[$i+5];
        $file_name = explode('/', $image)[count(explode('/', $image))-1];
        $file_type = explode('.', $image)[count(explode('.', $image))-1];;
        $data = file_get_contents('/xampp/htdocs/strona_spamerska/view/'.$image);
        $data = base64_encode($data); //obrazek w base64

        //dodanie obrazka do wiadomości
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: image/$file_type; name=\"$file_name\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-ID: <part$i>\r\n";
        $message .= "Content-Disposition: inline; filename=\"$file_name\"\r\n\r\n";
        $message .= "$data\r\n\r\n";
        $message .= "--$boundary\r\n";
    }

	mail($address, $subject, $message, $header);
?>