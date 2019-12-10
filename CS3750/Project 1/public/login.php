<?php require("../core/bootstrap.php");?>

<style>

	body { background-color: #000000; }
			 
	.container { margin: auto; width: 50%; text-align: center; border: 1px solid black;
		   			 	 padding: 15px; background-color: #ffffff; }

	input[type=text] { width: 50%; padding: 15px; margin: 5px 0 22px 0; display: inline-block;
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

<html>
	<body>
		<form action="receiveUN.php" method="post">
  		<div class="container">
    		<h1>Login</h1>
    		<p>Please enter user name to login.</p>
    		<hr>

    		<label for="email"><b>User Name</b></label>
				<br>
    		<input type="text" placeholder="Enter Name" name="username" required>

    		<button type="submit" class="registerbtn">Login</button>
  		</div>

      <div class="container signin">
    	   <p>Need an account? <a href="create.php">Sign up</a>.</p>
  		</div>
		</form>
	</body>
</html>
