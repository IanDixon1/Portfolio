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
				//check connection
				$sql = "INSERT INTO USERS (Username, Salt, Hash) VALUES ('" . $_POST["name"] . "', '" . $_POST["salt"] . "', '" . $_POST["hash"] . "')";

				//Check that the query executed and user record was created
				if ($db->query($sql) === TRUE) {
					echo "New record created successfully: ";
					echo '<p>Return to <a href="login.php">Login Page</a></p>';
				}
				else{
					echo "Did not create record...";
					echo '<p>Return to <a href="login.php">Login Page</a></p>';
				}
			?>
	</body>
</html>
