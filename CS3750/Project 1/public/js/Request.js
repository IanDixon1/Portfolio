// AJAX request wrapper
export class Request {
  // url is the url that the request will be sent to
  // method is the HTTP request method that will be used (GET/POST)
  // callback is the function that will be called when a response is received
  // data is an object containing the data that will go in the request body
  constructor(url, method, callback, data = null) {
    this.url = url;
    this.method = method;
    this.callback = callback;
    this.data = data;
    this.body = null;

    this.bindData();
    this.send();
  }

  // send the request
  send() {
    let request = new XMLHttpRequest();

    let obj = this;
    request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        obj.handleResponse(this.responseText);
      }
    };

    request.open(this.method, this.url);
    request.send(this.body);
  }

  // parse the JSON response, and hand it off to callback
  handleResponse(response) {
    let data = JSON.parse(response);
    this.callback(data);
  }

  // attach data to the request
  bindData() {
    // do nothing if there is no data
    if (this.data == null) {
      return;
    }

    if (this.method == Request.GET) {
      this.bindGetData();
    } else {
      this.bindPostData();
    }
  }

  // put data on the end of the url
  bindGetData() {
    let items = [];

    let props = Object.entries(this.data);
    for (let [key, value] of props) {
      items.push(key + "=" + value);
    }

    this.url += "?" + items.join("&");
  }

  // put data in the body
  bindPostData() {
    this.body = new FormData();

    // create form data entries for all properties of this.data
    let props = Object.entries(this.data);
    for (let [key, value] of props) {
      this.body.append(key, value);
    }
  }
}

// constants for HTTP request methods
Request.GET = "GET";
Request.POST = "POST";
