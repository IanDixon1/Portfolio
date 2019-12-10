<?php
require("../core/bootstrap.php");
?>
<html>
	<body>
			<?php
				//Spit out testing variables
				/*echo 'User: ' . $_POST["un"];
				echo '<br>';
				*/

				//Get the correct hash from the server
				$sql = "SELECT Hash FROM USERS WHERE Username = '" . $_POST["un"] . "'";
				$result = $db->query($sql);
				
				$row = mysqli_fetch_array($result);
				$serverHash = $row['Hash'];
				
				//Compare hash values
				$userHash = $_POST["hash"];
				/*echo 'User hash: ' . $userHash;
				echo '<br>';
				echo 'Server hash: ' . $serverHash;
				echo '<br>';*/
				if ($serverHash === $userHash) {
					echo "Logged in as " . $_POST["un"];
					$_SESSION["name"] = $_POST["un"];

					echo '<br>'; ?>
					<script type = "text/javascript">window.location.replace("index.php");</script>
					<?php
					echo '<p>If redirect does not work, <a href="index.php">click here</a> to start the game!</p>';
				}
				else{
					echo "Password incorrect";
					//remove all session variables
					session_unset();

					// destroy session
					session_destroy();

				}
				echo '<br>';
				/*
				if (isset($_SESSION["name"])) {
						echo "My session variables are " . $_SESSION["name"] . "<br>";
					} else {
						echo "Session variables are not set <br>";
					}
					*/
				echo '<p>Return to <a href="login.php">Home Page</a></p>';
				
			?>
	</body>
</html>