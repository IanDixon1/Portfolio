DROP TABLE IF EXISTS USERS;

CREATE TABLE USERS (
  Username VARCHAR(128) NOT NULL,
  Salt VARCHAR(128) NOT NULL,
  Hash VARCHAR(128) NOT NULL,
  PRIMARY KEY (Username)
);