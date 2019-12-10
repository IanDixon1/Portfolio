DROP TABLE IF EXISTS HighScores;

CREATE TABLE HighScores (
  id INT AUTO_INCREMENT NOT NULL,
  user VARCHAR(255) NOT NULL,
  score INT NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO HighScores (user, score) VALUES
('NES','1000'),
('Blinky', '900'),
('Inky', '800'),
('Pinky', '700'),
('Clyde', '600'),
('Sue', '500'),
('Dinky', '400'),
('Yum-Yum', '300'),
('Mario', '200'),
('Toad', '100');
