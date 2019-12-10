<?php
require("../core/bootstrap.php");
?>

<html>

<style>
  body { background-color: #000000; }

	.container { margin: auto; width: 50%; text-align: center; border: 1px solid black;
		   			 	 padding: 15px; background-color: #ffffff; }

	input[type=text] { width: 50%; padding: 15px; margin: 5px 0 22px 0; display: inline-block;
					 	 			 	 border: none; background: #f1f1f1; }

 input[type=password] { width: 50%; padding: 15px; margin: 5px 0 22px 0; display: inline-block;
                   					 	 			 	 border: none; background: #f1f1f1; }


  input[type=text]:focus { background-color: #ddd; outline: none; }

	hr { border: 1px solid #f1f1f1; margin-bottom: 25px;}

	.registerbtn { background-color: #4CAF50; color: white; padding: 16px 20px;
			   			 	 margin: 8px 0;  border: none; cursor: pointer; width: 50%;
				 				 opacity: 0.9; }

	.registerbtn:hover { opacity:1; }

	a { color: dodgerblue; }

	.signin { background-color: #f1f1f1; margin: auto; width: 50%; text-align: center; }
</style>

	<head>
		<!--From https://www.npmjs.com/package/js-sha256 -->
		<script src = "js/sha256.min.js"></script>
		<script type = "text/javascript">
			function makeid(length) {
			   var result           = '';
			   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			   var charactersLength = characters.length;
			   for ( var i = 0; i < length; i++ ) {
				  result += characters.charAt(Math.floor(Math.random() * charactersLength));
			   }
			   return result;
			}
			function validate() {
				if (document.myForm.name.value == "" ) {
					alert("Your name?");
					document.myForm.name.focus();
					return false;
				}
				if (document.myForm.password.value == "" ) {
					alert("Your password?");
					document.myForm.password.focus();
					return false;
				}

				var jsalt = makeid(64);
				var toHash = document.myForm.password.value + jsalt;
				var hashed = sha256.create();
				hashed.update(toHash);
				document.myForm.password.disabled = true;
				document.myForm.hash.value = hashed;
				document.myForm.salt.value = jsalt;

				return ( true );
			}

		</script>
	</head>
	<body>

	<form action="receiveAccountInfo.php" name="myForm" onsubmit="return (validate());" method="post">
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="email"><b>User Name</b></label>
		<br>
    <input type="text" placeholder="Enter Name" name="name" required>
		<br>

    <label for="password"><b>Password</b></label>
    <br>
		<input type="password" placeholder="Enter Password" name="password" required>
    <hr>

		<input type="hidden" name="salt" value="notgood">
		<input type="hidden" name="hash" value="notgood">
    <button type="submit" class="registerbtn">Register</button>
  </div>

  <div class="container signin">
    <p><a href="login.php"> Return Home.</a></p>
  </div>
</form>


	</body>
</html>
