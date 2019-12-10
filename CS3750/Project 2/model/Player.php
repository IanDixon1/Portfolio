<?php
require_once("model/Card.php");

class Player {
  // array of the cards the player has
  public $cards;
  // the player's name
  public $name;

  public function __construct() {
    $this->cards = [];
    $this->name = "";
  }

  // initialize the object with the data from an associative array
  public function init($data) {
    $this->name = $data['name'];
    $this->cards = Card::makeCards($data['cards']);
  }

  // take the next card from the player
  public function takeCard() {
    return array_pop($this->cards);
  }

  // give a card to the player
  public function giveCard($card) {
    array_unshift($this->cards, $card);
  }

  // give an array of cards to the player
  public function giveCards($cards) {
    array_unshift($this->cards, ...$cards);
  }
}
?>
