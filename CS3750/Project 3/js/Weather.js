import { Request } from './Request.js';

export class Weather {
  // rootElementID is the id of the div element that the ui will build inside
  constructor(rootElementID) {
    this.rootElement = document.getElementById(rootElementID);
    this.temperature = "";
    this.image = "";
    this.humidity = "";
    this.pressure = "";
    this.wind = "";

    this.buildUI();
  }

  // send a request to the api
  fetchData() {
    let city = this.inputElement.value;

    new Request(Weather.DATA_SOURCE, Request.GET, this.processData.bind(this), {
        q: city,
        APPID: Weather.API_KEY
    });
  }

  // process the data returned by the api
  processData(data) {
    // we will need to change this to work with the data from the api
    this.city = data.name;
    this.temperature = data.main.temp;
    this.image = data.weather[0].icon;
    this.humidity = data.main.humidity;
    this.pressure = data.main.pressure;
    this.wind = data.wind.speed;

    // update the page
    this.redraw();
  }

  // rebuild the display element with all the data in it
  redraw() {
    this.displayElement.remove();

    let displayElement = document.createElement("div");
    displayElement.setAttribute("class", "display");

    displayElement.innerHTML = `
      <div class="display">
        <div class="city">
          ${this.city}
        </div>

        <div class="temperature">
          ${this.temperature} K
        </div>

        <div class="image">
          <img src="http://openweathermap.org/img/wn/${this.image}@2x.png" width="500px" />
        </div>

        <div class="humidity">
          Humidity: ${this.humidity}%
        </div>

        <div class="pressure">
          Pressure: ${this.pressure} hPa
        </div>

        <div class="wind">
          Wind: ${this.wind} MPH
        </div>
      </div>
    `;

    this.rootElement.appendChild(displayElement);
    this.displayElement = displayElement;
  }

  buildUI() {
    this.rootElement.setAttribute("class", "weather");

    // input for the user to give us a city
    let inputContainerElement = document.createElement("div");
    inputContainerElement.setAttribute("class", "input");

    let inputElement = document.createElement("input");
    inputElement.setAttribute("type", "text");
    inputElement.setAttribute("placeholder", "Feed me a city");

    // button to submit the city
    let buttonElement = document.createElement("button");
    buttonElement.setAttribute("type", "button");
    buttonElement.innerHTML = "Do the thing";
    buttonElement.onclick = this.fetchData.bind(this);

    // center the button vertically
    let verticalCenterElement = document.createElement("div");
    verticalCenterElement.setAttribute("class", "center-vertical");
    let topPushElement = document.createElement("div");
    topPushElement.setAttribute("class", "push");
    let bottomPushElement = document.createElement("div");
    bottomPushElement.setAttribute("class", "push");

    verticalCenterElement.appendChild(topPushElement);
    verticalCenterElement.appendChild(buttonElement);
    verticalCenterElement.appendChild(bottomPushElement);

    // center the input stuff horizontally
    let leftPushElement = document.createElement("div");
    leftPushElement.setAttribute("class", "push");
    let rightPushElement = document.createElement("div");
    rightPushElement.setAttribute("class", "push");

    inputContainerElement.appendChild(leftPushElement);
    inputContainerElement.appendChild(inputElement);
    inputContainerElement.appendChild(verticalCenterElement);
    inputContainerElement.appendChild(rightPushElement);

    // element that the weather stuff will be displayed inside
    // this will be rebuilt by the redraw method, so we don't need to do
    // anything with it here
    let displayElement = document.createElement("div");

    this.rootElement.appendChild(inputContainerElement);
    this.rootElement.appendChild(displayElement);

    inputElement.focus();

    this.inputElement = inputElement;
    this.displayElement = displayElement;
  }
}

// url of the api
Weather.DATA_SOURCE = "http://api.openweathermap.org/data/2.5/weather";
// api key if we need one
Weather.API_KEY = "7b945224e789e7f4f7539bfa1e26a9b8";
