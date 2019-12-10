export class Word {
  constructor(word) {
    this.value = word;

    // set timestamps
    this.timeCreated = new Date();
    this.expireTime = new Date();
    this.expireTime.setSeconds(this.timeCreated.getSeconds() + Word.lifetime);

    this.addToPage();
  }

  // returns true if this word matches the given word
  matches(word) {
    return this.value == word;
  }

  // returns true if the word doesn't have any time left
  isExpired() {
    return this.getTimeLeft() <= 0;
  }

  // returns the time in seconds that this word has left before it expires
  getTimeLeft() {
    let now = new Date();
    let timeLeft = Math.ceil((this.expireTime.getTime() - now.getTime()) / 1000);
    return timeLeft;
  }

  // update the DOM element to bold the part of the word that matches boldPart
  updateBold(boldPart) {
    let html;

    if (this.value.startsWith(boldPart)) {
      // put bold tags around the bold part of the word
      let nonBoldPart = this.value.substring(boldPart.length);
      html = "<b>" + boldPart + "</b>" + nonBoldPart;
    } else {
      // word does not match input, so remove bold tags
      html = this.value;
    }

    this.wordElement.innerHTML = html;
  }

  // update the text for the timer
  updateTime() {
    this.timerElement.innerHTML = this.getTimeLeft();
  }

  // add the word to the page
  addToPage() {
    // create the elements
    let domElement = document.createElement("div");
    domElement.setAttribute("class", "word");

    let wordElement = document.createElement("div");
    wordElement.setAttribute("class", "word-value");
    wordElement.innerHTML = this.value;

    let timerElement = document.createElement("div");
    timerElement.setAttribute("class", "word-timer");
    timerElement.innerHTML = this.getTimeLeft();

    // add elements to the DOM
    domElement.appendChild(wordElement);
    domElement.appendChild(timerElement);
    Word.parentElement.appendChild(domElement);

    // save references to the elements
    this.domElement = domElement;
    this.wordElement = wordElement;
    this.timerElement = timerElement;
  }

  // remove this word from the page
  remove() {
    this.domElement.remove();
  }
}

// the amount of time in seconds that a word will be alive
Word.lifetime = 20;

// the element containing the list of words
// set by Game.buildUI
Word.parentElement = null;
