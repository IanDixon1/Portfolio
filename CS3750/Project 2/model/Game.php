<?php
require_once("model/Card.php");
require_once("model/Player.php");

class Game {
  // the player id (0 or 1) of the player whose turn it is
  public $nextTurnPlayer;
  // the current turn's type
  // normal = 1, special = 2
  // special turns are when a face card is played
  public $turnType;
  // special turns are after an ace or face card is played
  public $specialTurns;
  // array of players
  public $players;
  // the cards in play
  public $cardsInPlay;
  // the name of the winner
  public $winner;
  // the name of the round winner
  public $roundWinner;
  // the name of the slap $winner
  public $slapWinner;

  public $otherClientHasUpdated;

  public function __construct() {
    $this->nextTurnPlayer = 0;
    $this->turnType = 1;
    $this->specialTurns = 0;
    $this->winner = -1;
    $this->roundWinner = -1;
    $this->slapWinner = -1;
    $this->players = [new Player(), new Player()];
    $this->cardsInPlay = [];
    $this->otherClientHasUpdated = false;
  }

  // initialize a game object with the values from the given associative array
  private function init($data) {
    // initialize the turn stuff
    $this->nextTurnPlayer = $data['nextTurnPlayer'];
    $this->turnType = $data['turnType'];
    $this->specialTurns = $data['specialTurns'];
    $this->winner = $data['winner'];
    $this->roundWinner = $data['roundWinner'];
    $this->slapWinner = $data['slapWinner'];

    // initialize the players
    $this->players = [new Player(), new Player()];
    for ($i = 0; $i < sizeof($this->players); ++$i) {
      $this->players[$i]->init($data['players'][$i]);
    }

    // initialize the deck
    $this->cardsInPlay = Card::makeCards($data['cardsInPlay']);
    
    // when this variable is set to true, the client that caused the update
    // will pause, so the next request should always be from the other client
    $otherClientHasUpdated = $data['otherClientHasUpdated'];
    if ($otherClientHasUpdated) {
      $this->roundWinner = -1;
      $this->slapWinner = -1;
    } else {
      $this->otherClientHasUpdated = true;
      $this->save();
    }
  }

  // called when a player takes a turn
  public function turn() {
    // add your turn logic here
    // this method should not echo or return anything. It should just process the
    // turn logic, and save the game
    // turn.php will send the response to the client
    // $_SESSION['playerID'] will tell you which player sent the request (0 or 1)

	$pid = $_SESSION['playerID'];
	//check that the player's turn is valid
	if ($pid == $this->nextTurnPlayer){

		//set the current player
		$player;
		$otherPlayer;

//    $player = $_SESSION['playerID'];
//    $otherPlayer = opponent($_SESSION['playerID']);

		if ($pid == 0){
			$player = $this->players[0];
			$otherplayer = $this->players[1];
		}
		elseif ($pid == 1){
			$player = $this->players[1];
			$otherPlayer = $this->players[0];
		}

		//check if the turn is normal
		if ($this->turnType == 1){
			$deckCount = count($player->cards);
			$currentCard = $player->cards[$deckCount - 1];
			array_push($this->cardsInPlay, $currentCard);

			unset($player->cards[$deckCount - 1]);
			$temp = [];
			$temp = array_values($player->cards);
			$player->cards = $temp;

			//check for a face card
			//face cards give special turns to the other player
			$this->specialTurns = 0;
			//ace
			if ($currentCard->value == 0){
				$this->specialTurns = 4;
				$this->turnType = 2;
			}
			//jack, queen, king
			elseif($currentCard->value == 10){
				$this->specialTurns = 1;
				$this->turnType = 2;
			}
			elseif($currentCard->value == 11){
				$this->specialTurns = 2;
				$this->turnType = 2;
			}
			elseif($currentCard->value == 12){
				$this->specialTurns = 3;
				$this->turnType = 2;
			}

			if (count($player->cards) < 1){
				$this->winner = ($pid + 1) % 2;
			}

			$this->nextTurnPlayer = ($pid + 1) % 2;
			$this->turnNumber++;
			$this->save();
		}
		//check if the turn is special (previous card was a face card)
		elseif ($this->turnType == 2){
			$deckCount = count($player->cards);
			$currentCard = $player->cards[$deckCount - 1];
			array_push($this->cardsInPlay, $currentCard);

			unset($player->cards[$deckCount - 1]);
			$temp = [];
			$temp = array_values($player->cards);
			$player->cards = $temp;

			$result = false;

			if ($currentCard->value == 0){
				$result = true;
			}
			elseif($currentCard->value > 9){
				$result = true;
			}

			if ($result == true){
				//play resumes normally with other player
				$this->specialTurns = 0;
				if ($currentCard->value == 0){
					$this->specialTurns = 4;
					$this->turnType = 2;
				}
				//jack, queen, king
				elseif($currentCard->value == 10){
					$this->specialTurns = 1;
					$this->turnType = 2;
				}
				elseif($currentCard->value == 11){
					$this->specialTurns = 2;
					$this->turnType = 2;
				}
				elseif($currentCard->value == 12){
					$this->specialTurns = 3;
					$this->turnType = 2;
				}
				$this->nextTurnPlayer = ($pid + 1) % 2;
				$this->turnType = 2;
			}
			else{
				$this->specialTurns--;
				//check that there are special turns left to take
				if ($this->specialTurns < 1){
					//other player wins the round
					$deckCount = count($this->cardsInPlay);
					//give all the cards in the deck to the other player

					$this->players[(($pid + 1) % 2)]->giveCards($this->cardsInPlay);
					while($deckCount > 0){
						$deckCount--;
						//array_push($otherPlayer->cards, $this->cardsInPlay[$deckCount]);
						array_pop($this->cardsInPlay);
					}


					$this->turnType = 1;
					$this->nextTurnPlayer = ($pid + 1) % 2;
          //set the alert for the round winner
          $this->roundWinner = ($pid + 1) % 2;
          $this->otherClientHasUpdated = false;
				}
				//more special turns for this player to take
				else{
					$this->turnType = 2;
					$this->nextTurnPlayer = $pid;
				}
			}

			if (count($player->cards) < 1){
				$this->winner = ($pid + 1) % 2;
			}

			//end special turn
			$this->turnNumber++;
			$this->save();
		}
	}
  }

  // Called when a player slaps the cards
  public function slap() {

    // $_SESSION['playerID'] will tell you which player sent the request (0 or 1)
    $playerId = $_SESSION['playerID'];
    // $penalty sets the amout of penalty for an incorrect slap
    $penalty = 2;

    //Slapping an Empty Deck
    if (sizeof($this->cardsInPlay) < 2){
      // Loop for game penalty
      for ($i = 0; $i < $penalty; $i++){
        if (sizeof($this->players[$playerId]->cards) > 1){
          // Take a card from the player and put in the discard
          $this->playCard($this->players[$playerId]->takeCard());
        } else {
            // Cue the end of the game
            $this->winner = $this->opponent($playerId);
          }
      }
      // Change turn to player
      $this->nextTurnPlayer = $playerId;
      // Set game to normal turn
	    $this->turnType = 1;
    } else {
      //Slapping a deck of at least 2 cards

      // set the values of the last two cards played for comparison
      $cardOne = $this->cardsInPlay[sizeof($this->cardsInPlay) - 1]->value;
      $cardTwo = $this->cardsInPlay[sizeof($this->cardsInPlay) - 2]->value;

      // The two cards match
      if (strcmp($cardOne, $cardTwo) == 0){
          // Merge the cards from the discard to the player
          $this->players[$playerId]->giveCards($this->cardsInPlay);
          // Reset the discard pile to zero
          $this->cardsInPlay = [];
          // Change turn to opponent
          $this->nextTurnPlayer = ($playerId + 1) % 2;
          // Set game to normal turn
		      $this->turnType = 1;
          $this->slapWinner = $playerId;
          $this->otherClientHasUpdated = false;

      // The two cards do not match
      } else {
          // Loop for game penalty
          for ($i = 0; $i < $penalty; $i++){
            if (sizeof($this->players[$playerId]->cards) > 1){
              // Take a card from the player and put in the discard
              $this->playCard($this->players[$playerId]->takeCard());
            } else {
                // Cue the end of the game
                $this->winner = $this->opponent($playerId);
            }
            // Change turn to player
            $this->nextTurnPlayer = $playerId;
            // Set game to normal turn
			      $this->turnType = 1;
          }
        }
    }
    // Save all three decks
    $this->save();

  } // end slap()


  // put a card in play
  protected function playCard($card) {
    array_push($this->cardsInPlay, $card);
  }


  // returns the opponent
  protected function opponent($player){
    if ($player == 0) {
      $value = 1;
      return $value;
    } else {
        $value = 0;
        return $value;
      }
  }

  // join a game
  // returns the game
  public static function join() {
    // see if there is an existing game that needs a second player
    $sql = "SELECT id FROM Games WHERE hasSecondPlayer = 0 LIMIT 1;";
    $result = DB::select($sql);

    if ($result->num_rows > 0) {
      // found a game that needs a second player. join it
      $row = $result->fetch_assoc();
      $gameID = $row['id'];
      $game = self::find($row['id']);

      // update the session
      $_SESSION['gameID'] = $gameID;
      $_SESSION['playerID'] = 1;

      // update the game
      $game->players[1]->name = $_SESSION['playerName'];
      $game->save();

      return $game;
    } else {
      // didn't find an existing game to join. create a new one
      $game = self::create();
      return $game;
    }
  }

  // create a new game
  protected static function create() {
    $game = new Game();
    $game->players[0]->name = $_SESSION['playerName'];

    // shuffle the cards
    $cards = Card::shuffle();

    // deal the cards
    for ($i = 0; $i < sizeof($cards); ++$i) {
      $game->players[$i % 2]->giveCard($cards[$i]);
    }

    // save the game in the database
    $game->save();
    $gameID = DB::lastID();

    // update the session
    $_SESSION['gameID'] = $gameID;
    $_SESSION['playerID'] = 0;

    // return the created game
    return self::find($gameID);
  }

  // returns a JSON object representing the current game state
  // the JSON object is interpretable by the client
  public function getStateForClient() {
    $cardsInPlay = [];
    for ($i = 0; $i < 5 && $i < sizeof($this->cardsInPlay); ++$i) {
      $index = sizeof($this->cardsInPlay) - $i - 1;
      array_unshift($cardsInPlay, $this->cardsInPlay[$index]);
    }

    $state = [
      // card counts for each player
      "cardCounts" => [
        sizeof($this->players[0]->cards),
        sizeof($this->players[1]->cards)
      ],
      // all the cards currently in play
      "cardsInPlay" => $cardsInPlay,
      // name of the player who won the game
      "winner" => $this->winner,
      // name of the round winner
      "roundWinner" => $this->roundWinner,
      // name of the slap winner
      "slapWinner" => $this->slapWinner
    ];

    return json_encode($state);
  }

  protected function getStateForDB() {
    return json_encode($this);
  }

  // saves the game in the database
  public function save() {
    if (isset($_SESSION['gameID'])) {
      // update existing game
      $sql = "UPDATE Games SET state = ?, hasSecondPlayer = ? WHERE id = ?;";

      DB::execute($sql, "sii", [
        $this->getStateForDB(),
        $this->players[1]->name != "",
        $_SESSION['gameID']
      ]);
    } else {
      // create new game
      $sql = "INSERT INTO Games (state) VALUES (?)";

      DB::execute($sql, "s", [$this->getStateForDB()]);
    }
  }

  // find the game with the given id
  public static function find($id) {
    $sql = "SELECT * FROM Games WHERE id = ?;";
    $result = DB::execute($sql, "i", [$id]);

    // return null if the game does not exist
    if ($result->num_rows == 0) {
      return NULL;
    }

    $game = new Game();
    $stateJSON = $result->fetch_assoc()['state'];
    $gameState = json_decode($stateJSON, true);
    $game->init($gameState);
    return $game;
  }
}
?>
