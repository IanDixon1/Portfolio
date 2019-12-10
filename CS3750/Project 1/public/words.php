<?php
		require("../core/bootstrap.php");

		//SELECT words from the database
		$sql = "SELECT word FROM Words ORDER BY RAND() LIMIT 10;";
		$result = $db->query($sql);

		//Initalize array
		$words = array();

		//Fetch into associative array
			 while($row = $result->fetch_assoc()) {
					$words[] = $row["word"];
			 }

		//Test Print out the array
		echo json_encode(compact("words"));

?>
