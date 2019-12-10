<?PHP

		 require("../core/bootstrap.php");

		 if (isset($_SESSION["name"])){
				$name = $_SESSION[name];

				if (isset($_POST["score"])){
					 $score = $_POST[score];
					 //Add the score to the database
					 $sql = "INSERT INTO HighScores (user, score) VALUES ( '$name' , '$score')";
					 if ($db->query($sql) === true){
					 		//echo "Input Success!";
							} else {
					 			echo "Error: " .$sql . "<br>" . $db->error;
								}
				}
		 }

		//Pull database and set to an array
		$sql = "SELECT * FROM HighScores ORDER BY score DESC LIMIT 10";
		$result = $db->query($sql);

		//Loop to pull input from the DB
		$users = array();
		$scores = array();

		if ($result->num_rows > 0) {
		$counter = 0;
			while($row = $result->fetch_assoc()) {
				  $users[$counter] = $row["user"];
				  $scores[$counter] = $row["score"];
					$counter = $counter + 1;
			}
		} else {
			echo "Something bad happened";
		}

		//Close the DB Connection
		$db->close();
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Typing Game</title>

	  <style>
	    .content {max-width: 960px; margin: auto;	padding: 5px;}
	    .title_box { border: 2px solid yellow; margin-left: auto; margin-right: auto; width: 500px;}
	    .title_box #title {position: relative; top : -0.5em; margin-left: 1em; display: inline; background-color: black;	color: yellow; font-size: 30px; padding-left: 5px; padding-right: 5px;}
	    .title_box #content {}
	    body {background: black;}
	    table{margin-left: auto; margin-right: auto; width: 500px; padding: 5px;}
	    tr {padding: 5px; color: white;	margin-top: 10px;	margin-bottom: 10px;}
	    th, td {text-align: center;	font-size: 25px;}
	    h1 {text-align: center; font-size: 150px; padding: 5px; color: red;}
	    h2{text-align: center; font-size: 50px; color: red;}
	    h3{text-align: center; font-size: 30px; color: red;}
	  </style>
	</head>

  <body>
    <h1>Game Over</h1>
    <h3>Your Score: </h3>
	  <h2><?= $score ?></h2>
		<div class="title_box" id="tb">
   	  <div id="title">High Scores</div>
   	  <div id="content">
	      <table>
					<?PHP for($i = 0; $i < 10; $i++): ?>
					<tr> <td> <?= $i + 1 ?> </td>  <td><?= $users[$i] ?></td> <td><?= $scores[$i] ?></td> </tr>
					<?PHP endfor; ?>
        </table>
      </div>
    </div>
  </body>
</html>
