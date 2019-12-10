export class Card {
  // Do not try to manually instantiate this class. Send a request to the server,
  // and give this constructor the card object from the response.
  constructor(dataObject) {
    this.id = dataObject.id;
    this.value = dataObject.value;
    this.face = dataObject.face;
    this.suit = dataObject.suit;
    this.imgSrc = dataObject.imgSrc;

    this.addToPage();
  }

  // create the DOM elements for the card
  addToPage() {
    // create elements
    let cardElement = document.createElement("div");
    cardElement.setAttribute("class", "card");

    let imgElement = document.createElement("img");
    imgElement.setAttribute("src", this.imgSrc);
    imgElement.setAttribute("alt", this.toString());
    imgElement.onclick = function() {
      Card.parentElement.onclick();
    }

    // add elements to the page
    cardElement.appendChild(imgElement);
    Card.parentElement.appendChild(cardElement);

    // bind elements to the object
    this.cardElement = cardElement;
    this.imgElement = imgElement;
  }

  remove() {
    this.cardElement.remove();
  }

  toString() {
    return this.face + " of " + this.suit;
  }
}

// the element containing the cards
// set by Game.buildUI
Card.parentElement = null;
