<?php
require_once("database/DB.php");

// Card model class
// Do not instantiate this class. Use the static methods to load cards from
// the database.
class Card {
  // id of the card in the database
  public $id;
  // number between 0 and 14 that we can use to compare cards
  public $value;
  // the card's face value
  public $face;
  // the card's suit
  public $suit;
  // the relative url of the card's image
  public $imgSrc;

  // create a Card object with the properties from the given associative array
  public function __construct($data) {
    $this->id = $data['id'];
    $this->value = $data['value'];
    $this->face = $data['face'];
    $this->suit = $data['suit'];
    $this->imgSrc = $data['imgSrc'];
  }

  // returns an array containing all the cards, ordered by suit
  public static function all() {
    // get the cards from the database
    $sql = "SELECT * FROM Cards ORDER BY suit, value DESC;";
    $result = DB::select($sql);

    // use the results of the query to create an array of Card objects
    $cards = [];
    while ($row = $result->fetch_assoc()) {
      array_push($cards, new Card($row));
    }

    return $cards;
  }

  // returns an array containing a shuffled deck of cards
  public static function shuffle() {
    // get the cards from the database in a random order
    $sql = "SELECT * FROM Cards ORDER BY RAND();";
    $result = DB::select($sql);

    // use the results of the query to create an array of Card objects
    $cards = [];
    while ($row = $result->fetch_assoc()) {
      array_push($cards, new Card($row));
    }

    return $cards;
  }

  // returns the object for the card with the given id
  // returns null if the card does not exist
  public static function find($id) {
    $sql = "SELECT * FROM Cards WHERE id = ?;";
    $result = DB::execute($sql, 'i', [$id]);

    if ($result->num_rows == 0) {
      return null;
    }

    return new Card($result->fetch_assoc());
  }

  // get an array of cards with the given ids
  // $ids is an array of card ids
  public static function get($ids) {
    $cards = [];

    foreach ($ids as $id) {
      array_push($cards, self::find($id));
    }

    return $cards;
  }

  // take an array of card data, and return an array of Card objects
  public static function makeCards($cardData) {
    $cards = [];
    foreach ($cardData as $data) {
      array_push($cards, new Card($data));
    }

    return $cards;
  }
}
?>
