<div class="navbar">
    <div class="logo">Strona spamerska</div>
    <div class="login-section">
		
		
        <div class="btn-success" id="login" onclick="load('sign_in.html')">Zaloguj</div>
        <div class="btn-success" id="registration"  onclick="load('sign_up.html')">Zarejstruj</div>

		<?php
		
		session_start();
		 
		 if(isset($_SESSION['login_user']))
		 {
		 echo "<script>document.getElementById('login').style.display = 'none'; </script>";
		 		 echo "<script>document.getElementById('registration').style.display = 'none'; </script>";

		 echo   "<a href='log_out.php' ><div class='btn-success'>Wyloguj</div></a>";
		 }
		 else {
		 echo "<script>document.getElementById('login').style.display = 'block'; </script>";
		 		 echo "<script>document.getElementById('registration').style.display = 'block'; </script>";

			}
		?>
    </div>
</div>

