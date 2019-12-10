<?php
require("../core/bootstrap.php");
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Typing Game</title>
	</head>

	<body>
			<?php
				//finding username
				$sql = "SELECT Username FROM USERS WHERE Username = '" . $_POST["username"] . "'";
				$result = $db->query($sql);
				//check that username is in database
				if ($result->num_rows > 0) {
					//put username in session variable
					//$_SESSION["name"] = $_POST["username"];

					//get salt
					$sql = "SELECT Salt FROM USERS WHERE Username = '" . $_POST["username"] . "'";
					$result = $db->query($sql);

					$row = mysqli_fetch_array($result);
					$salt = $row['Salt'];


					?>
					<script src = "js/sha256.min.js"></script>

					<script type = "text/javascript">
						function validate() {
							if (document.myForm.pw.value == "" ) {
								alert("Your password?");
								document.myForm.pw.focus();
								return false;
							}
							var toHash = document.myForm.pw.value + '<?= $salt ?>';
							var hashed = sha256.create();
							hashed.update(toHash);
							document.myForm.pw.disabled = true;
							document.myForm.hash.value = hashed;
							document.myForm.un.value = '<?= $_POST["username"]?>';

							return ( true );
						}
					</script>

					<?php echo 'Found user ' . $_POST["username"];

					//Password form
					echo '<form action = "receivePassword.php" method="post" name="myForm" onsubmit="return (validate());">
					Password: <input type="password" name="pw" autofocus />
					<br>
					<input type="hidden" name="hash" value="notgood" />
					<input type="hidden" name="un" />
					<input type="submit" value="Submit" />';

				}
				else{
					//couldn't find username
					echo "Did not find user... ";
					echo $_POST["username"];
					echo '<p>Return to <a href="login.php">Login Page</a></p>';
				}


				echo "<br>";

			?>
	</body>
</html>
