import { Word } from './Word.js';
import { Buffer } from './Buffer.js';
import { Request } from './Request.js';

// main class for the game
export class Game {
  // rootElementId is the id of the element that the game will build itself inside
  // wordDataSource is the url the game will load words from
  // gameOverAction is the url the game will post the score to when the player loses
  constructor(rootElementId, wordDataSource, gameOverAction) {
    this.rootElement = document.getElementById(rootElementId);
    this.wordBuffer = new Buffer(wordDataSource);
    this.activeWords = [];
    this.gameOverAction = gameOverAction;
    this.startTime = new Date();
    this.score = 0;

    this.buildUI();
    this.start();
  }

  // take the next word from the buffer, and add it to the list of active words
  addWord() {
    let word = this.wordBuffer.getNext();
    this.activeWords.push(new Word(word));
  }

  // check the input
  // bold words that partially match, and remove words that fully match
  checkInput() {
    let input = this.inputElement.value;

    for (let i = 0; i < this.activeWords.length; ++i) {
      let word = this.activeWords[i];

      // update the bold stuff
      word.updateBold(input);

      // check for full match
      if (word.matches(input)) {
        // remove the word from the page
        word.remove();

        // remove the word from active words
        this.activeWords.splice(i--, 1);

        // get the next word
        this.addWord();

        // reset the input
        this.inputElement.value = "";
      }
    }
  }

  // start the game
  start() {
    // wait for the buffer to be filled, then add words
    let initInterval = setInterval(() => {
      if (this.wordBuffer.hasNext()) {
        clearInterval(initInterval);

        for (let i = 0; i < Game.WORD_COUNT; ++i) {
          this.addWord();
        }

        // start the tick interval
        this.tickInterval = setInterval(this.tick.bind(this), Game.TICK_TIME);

        // start the difficulty interval
        this.difficultyInterval = setInterval(this.increaseDifficulty.bind(this),
          Game.DIFFICULTY_INCREASE_TIME * 1000);
      }
    }, 100);
  }

  // update the score
  updateScore() {
    this.score = Math.floor((new Date() - this.startTime) / 100);
    this.timerElement.innerHTML = "Score: " + this.score;
  }

  // update timers for all the words
  updateWords() {
    for (let word of this.activeWords) {
      word.updateTime();
    }
  }

  // returns true if any active words are expired
  checkForLoss() {
    for (let word of this.activeWords) {
      if (word.isExpired()) {
        return true;
      }
    }

    return false;
  }

  // executed every [Game.TICK_TIME] milliseconds
  tick() {
    this.updateScore();
    this.updateWords();

    // end the game when the player loses
    if (this.checkForLoss()) {
      this.end();
    }
  }

  // make the words expire faster
  increaseDifficulty() {
    if (Word.lifetime > 0) {
      --Word.lifetime;
    }
  }

  // end the game
  end() {
    // stop ticking
    clearInterval(this.tickInterval);
    clearInterval(this.difficultyInterval);

    // show an alert with a game over message, and redirect to the high scores
    // page after the user clicks away from the alert
    Swal.fire({
      title: "Game Over",
      text: "You get nothing! You lose! Good day, sir."
    }).then((result) => {
      this.sendGameOver();
    });
  }

  // build and submit a form to post the score to the server
  sendGameOver() {
    let form = document.createElement("form");
    form.setAttribute("action", this.gameOverAction);
    form.setAttribute("method", "POST");

    let scoreInput = document.createElement("input");
    scoreInput.setAttribute("type", "hidden");
    scoreInput.setAttribute("name", "score");
    scoreInput.value = this.score;

    form.appendChild(scoreInput);
    this.rootElement.appendChild(form);
    form.submit();
  }

  // create the UI for the game inside the root element
  buildUI() {
    this.rootElement.setAttribute("class", "game");

    // create the elements
    let timerElement = document.createElement("div");
    timerElement.setAttribute("class", "timer");
    timerElement.innerHTML = "Score: " + this.score;

    let inputElement = document.createElement("input");
    inputElement.setAttribute("type", "text");
    inputElement.setAttribute("autofocus", "");
    inputElement.addEventListener("keyup", this.checkInput.bind(this));

    let wordListElement = document.createElement("div");
    wordListElement.setAttribute("class", "word-list");

    // add elements to the DOM
    this.rootElement.appendChild(timerElement);
    this.rootElement.appendChild(inputElement);
    this.rootElement.appendChild(wordListElement);

    // save references to the elements
    this.timerElement = timerElement;
    this.inputElement = inputElement;
    Word.parentElement = wordListElement;
  }
}

// number of milliseconds between game ticks
Game.TICK_TIME = 100;

// after this many seconds, the words will expire 1 second faster
Game.DIFFICULTY_INCREASE_TIME = 10;

// the number of words on the screen
Game.WORD_COUNT = 5;
