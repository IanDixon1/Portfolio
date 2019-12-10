import { Request } from "./Request.js";

// auto-refilling buffer to store data retrieved from the server
export class Buffer {
  // dataSource is the url that the buffer will send a request to when it
  // needs more data
  constructor(dataSource) {
    this.dataSource = dataSource;
    this.items = [];
    this.isFetchingData = false;

    this.getMoreData();
  }

  // send a request to dataSource to get more data from the server.
  // expects a response containing an object with an array of data items
  getMoreData() {
    // don't request more data until a response has been received for the
    // previous request
    if (this.isFetchingData) {
      return;
    }
    this.isFetchingData = true;

    new Request(
      this.dataSource,
      Request.GET,
      (data) => {
        // get the array of items from the object
        // we don't know what its name is, but it should always be the only
        // property of the object
        let array = Object.values(data)[0];
        this.addItems(array);
        this.isFetchingData = false;
      }
    );
  }

  // add an array of items to the buffer
  addItems(items) {
    this.items.unshift(...items);
  }

  // add an item to the buffer
  addItem(item) {
    this.items.unshift(item);
  }

  // returns true if there is anything in the buffer
  hasNext() {
    return this.items.length != 0;
  }

  // remove the next item from the buffer, and return it
  getNext() {
    let item = this.items.pop();

    // get more data if we are running low
    if (this.items.length < Buffer.LOW_WATERMARK) {
      this.getMoreData();
    }

    return item;
  }
}

// if a buffer has fewer than LOW_WATERMARK items, it will request more data
// from the server
Buffer.LOW_WATERMARK = 5;
