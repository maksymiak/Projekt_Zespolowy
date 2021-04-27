<!DOCTYPE html>

<?php
 session_start();

 //przekierowywanie na stronę główną, gdy uzytkownik niezalogowany
 if(!isset($_SESSION['login_user'])){
    header("Location:home.html");
 }

 if(isset($_POST) && (bool)$_POST){

   //pobranie danych z html
   $address = $_POST['address'];
   $subject = $_POST['title'];
   $content = $_POST['content'];
   $time = $_POST['time'];

   $imagesCount=0;
   $images = "";
   if(!empty($_FILES['file']['name'][0])){
        for($i=0; $i<count($_FILES['file']['name']); $i++){
            //pobranie danych z załączonych obrazków
            $file_name = $_FILES['file']['name'][$i];
            $file_type =  $_FILES['file']['type'][$i];
            $temp_name =  $_FILES['file']['tmp_name'][$i];

            //tworzenie obrazka
            $file = fopen($temp_name, 'rb');
            $base64 = fread($file, filesize($temp_name));
            $base64 = chunk_split(base64_encode($base64));
            $data = base64_decode($base64);
            $type = explode("/", $file_type)[1];
            $file = 'assets/'.uniqid() .'.'.$type;
            file_put_contents($file, $data);

            $images .="\"$file\" ";
            $imagesCount ++;
        }
   }

    $date = new DateTime();
    $timestamp = $date->getTimestamp();

    //tworzenie pliku bat
    $fileBat = '/xampp/htdocs/strona_spamerska/view/bat/' ."mail$timestamp.bat";
    $fileBatContent = "\"C:/xampp/php/php.exe\" -f \"C:/xampp/htdocs/strona_spamerska/view/send_mail.php\" \"$address\" \"$subject\" \"$content\" $imagesCount $images";
    file_put_contents($fileBat, $fileBatContent);

    //tworzenie zadania w harmonogramie zadań
	$cmdMessage = "schtasks /create /tn \"mail$timestamp\" /st $time:00 /sc daily ";
    $cmdMessage .= "/tr C:$fileBat";
    exec($cmdMessage);
 }
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="style/style.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <meta charset="utf-8" />
    <title></title>
    <script>
        $(function () {
            $("#navbar").load("shared/navbar.php");
        });

        //podgląd wybranych obrazków
        let openFiles = function(file) {
            let files = file.target.files;
            document.getElementById("images").innerHTML = '';
            for(let i=0; i<files.length; i++){
                file = files[i];
                let reader = new FileReader();
                reader.onload = function(e){
                    let dataURL = e.target.result;
                    let img = document.createElement("img");
                    img.src = dataURL;
                    img.height = "100";
                    document.getElementById("images").appendChild(img);
                };
                reader.readAsDataURL(file);
            }
		}

        //walidacja przy wysyłaniu
        let onSubmit = function(event) {           
            const time = document.getElementById('time');
            const address = document.getElementById('address');
            const regex = /\S+@\S+\.\S+/;

            if(!time.value){
                event.preventDefault();
                time.style.boxShadow = "0 0 3px 2px red";
			}

            if(!regex.test(address.value)){
                event.preventDefault();
                address.style.boxShadow = "0 0 3px 2px red";
			}
        }

        //walidacje czasu
        let onBlurTime = function(event) {           
            const time = event.target;

            if(!time.value){
                time.style.boxShadow = "0 0 3px 2px red";
			} else {
                time.style.boxShadow = "0 0 3px 2px hsla(238, 64%, 79%, 0.45)";
			}
        }

        //walidacje adresu
        let onBlurAddress = function(event) {           
            const address = event.target;
            const regex = /\S+@\S+\.\S+/;

            if(!regex.test(address.value)){
                address.style.boxShadow = "0 0 3px 2px red";
			} else {
                address.style.boxShadow = "0 0 3px 2px hsla(238, 64%, 79%, 0.45)";
			}
        }

    </script>
</head>
<body>
<div id="navbar"></div>
    <div class="wrapper">
         <div class="card">
            <div class="title"><p style="padding:20px; margin:0px;">Utwórz wiadomość</p></div>
            <div class="content">
                <form action="" method="post" enctype="multipart/form-data" onsubmit="onSubmit(event)">
                    <p>Adres:</p>
                    <input id="address" name="address" type="text"  onblur="onBlurAddress(event)"/>
                    <p>Tytuł:</p>
                    <input name="title" type="text" />
                    <p>Treść:</p>
                    <textarea name="content" ></textarea>
                    <input type="file" name='file[]' id="fileElem" multiple="multiple" accept="image/*" onchange="openFiles(event)"/>
                    <div id="images" class="images">
                    </div>
                    <p>Czas wysyłki:</p>
                    <input Type="time" id="time" name="time" value="07:00" onblur="onBlurTime(event)"/>
                    <div style="width:100%">
                        <button class="btn-primary" name='submit'>Wyślij</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>