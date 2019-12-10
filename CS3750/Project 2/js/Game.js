import { Request } from './Request.js';
import { Card } from './Card.js';

// main game class
export class Game {
  // targetElementID is the id of the div element that the ui will build inside
  constructor(rootElementID) {
    this.rootElement = document.getElementById(rootElementID);
    this.names = ["", ""];
    this.winner = -1;
    this.roundWinner = -1;
    this.slapWinner = -1;
    this.cardCounts = [26, 26];
    this.cardsInPlay = [];

    this.buildUI();
    this.waitForGameStart();
  }

  // constantly poll the server for updates
  // called by the tickInterval
  tick() {
    // send a request to the server for the current game state
    new Request("tick.php", Request.GET, this.update.bind(this));
  }

  // the method called when the player takes a turn
  turn() {
    // send a request to the server to do all the processing
    new Request("turn.php", Request.GET, this.update.bind(this));
  }

  // the method called when the player slaps the cards
  slap() {
    // send a request to the server to do all the processing
    new Request("slap.php", Request.GET, this.update.bind(this));
  }

  // poll the server for initial data
  init() {
    new Request("init.php", Request.GET, this.checkStart.bind(this));
  }

  // update the game based on the response from the server after a player
  // takes a turn or does a slap
  update(newState) {
    // update card counts
    this.cardCounts = newState.cardCounts;

    this.playerCardCountElement.innerHTML = this.cardCounts[this.playerID];
    this.opponentCardCountElement.innerHTML = this.cardCounts[this.otherPlayerID()];

    // replace all the cards with the ones in the new state
    for (let card of this.cardsInPlay) {
      card.remove();
    }

    this.cardsInPlay = [];
    for (let cardData of newState.cardsInPlay) {
      this.cardsInPlay.push(new Card(cardData));
    }

    // check for a round winner
    if (this.roundWinner != newState.roundWinner){
      this.roundWinner = newState.roundWinner;
      if (this.roundWinner != -1) {
        this.roundEnd();
      }
    }

    // check for a slap winner
    if (this.slapWinner != newState.slapWinner){
      this.slapWinner = newState.slapWinner;
      if (this.slapWinner != -1) {
        this.slapAlert();
      }
    }

    // check for winner
    this.winner = newState.winner;
    if (this.winner != -1) {
      this.end();
    }
  }

  // poll init.php until the game starts
  waitForGameStart() {
    this.initInterval = setInterval(this.init.bind(this), Game.TICK_INTERVAL);
  }

  // process the response from init.php to see if the game has started
  checkStart(data) {
    // update properties
    this.playerID = data.playerID;
    this.names = data.names;

    // update the page
    this.playerNameElement.innerHTML = this.names[this.playerID];
    this.opponentNameElement.innerHTML = this.names[this.otherPlayerID()];

    // if there is a second player, start the game
    if (this.names[1] != "") {
      // stop the init interval
      clearInterval(this.initInterval);

      // start the game
      this.start();
    }
  }

  // start the game
  start() {
    // it's really hard to debug when the network tab is full of tick requests,
    // so for now we can just click a button to update the game state
    document.getElementById("tickButton").onclick = this.tick.bind(this);
    document.getElementById("slapButton").onclick = this.slap.bind(this);

    // uncomment this to make ticking work normally
    this.tickInterval = setInterval(this.tick.bind(this), Game.TICK_INTERVAL);
  }

  // end the game
  end() {
    // stop ticking
    clearInterval(this.tickInterval);

    // show an alert to say who won
    let text = this.winner == this.playerID ?
      "Nice." : "You get nothing! You lose! Good day, sir.";

    Swal.fire({
      title: this.names[this.winner] + " wins!",
      text: text
    });
  }

  // popup for round winner
  roundEnd() {
    //stop ticking
    clearInterval(this.tickInterval);

    // show an alert to say who won the round
    let text = this.roundWinner == this.playerID ?
      "Nice." : "Maybe the next one..."
    Swal.fire({
      title: this.names[this.roundWinner] + " takes the round!",
      text: text
    });

    this.roundWinner = -1;
    setTimeout(this.start.bind(this), 3000);
  }

  // popup for a slap winner
  slapAlert() {
    //stop ticking
    clearInterval(this.tickInterval);

    // show an alert to say who won the slap
    let text = this.slapWinner == this.playerID ?
      "Nice Slap." : "Too Slow!"
    Swal.fire({
      title: this.names[this.slapWinner] + " slapped successfully!",
      text: text
    });

    this.slapWinner = -1;
    setTimeout(this.start.bind(this), 3000);
  }

  // return the id of the other player
  otherPlayerID() {
    return (this.playerID + 1) % 2;
  }

  buildUI() {
    this.rootElement.setAttribute("class", "game");

    // create the player
    let playerElement = document.createElement("div");
    playerElement.setAttribute("class", "player me");
    playerElement.onclick = this.turn.bind(this);

    let playerNameElement = document.createElement("div");
    playerNameElement.setAttribute("class", "name");

    let playerCardCountElement = document.createElement("div");
    playerCardCountElement.setAttribute("class", "count");

    playerElement.appendChild(playerNameElement);
    playerElement.appendChild(playerCardCountElement);

    //create the board
    let boardElement = document.createElement("div");
    boardElement.setAttribute("class", "deck");
    boardElement.onclick = this.slap.bind(this);

    //create the opponent
    let opponentElement = document.createElement("div");
    opponentElement.setAttribute("class", "player");

    let opponentNameElement = document.createElement("div");
    opponentNameElement.setAttribute("class", "name");

    let opponentCardCountElement = document.createElement("div");
    opponentCardCountElement.setAttribute("class", "count");

    opponentElement.appendChild(opponentNameElement);
    opponentElement.appendChild(opponentCardCountElement);

    // add elements to the DOM
    this.rootElement.appendChild(playerElement);
    this.rootElement.appendChild(boardElement);
    this.rootElement.appendChild(opponentElement);

    // save references to the elements
    this.playerNameElement = playerNameElement;
    this.opponentNameElement = opponentNameElement;
    this.playerCardCountElement = playerCardCountElement;
    this.opponentCardCountElement = opponentCardCountElement;

    // set Card.parentElement so cards will add themselves to the board
    Card.parentElement = boardElement;
  }
}

// number of milliseconds between game ticks
Game.TICK_INTERVAL = 100;
