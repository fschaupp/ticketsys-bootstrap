DROP DATABASE IF EXISTS ticketsys;
CREATE DATABASE ticketsys;
USE ticketsys;

CREATE TABLE IF NOT EXISTS users(
  UUID      INT          NOT NULL AUTO_INCREMENT,
  email     VARCHAR(128) NOT NULL,
  password  VARCHAR(512) NOT NULL,

  firstname VARCHAR(128) NOT NULL,
  surname   VARCHAR(128) NOT NULL,

  rank			ENUM('administrator', 'user') NOT NULL,

  PRIMARY KEY(UUID)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS movies(
  UMID                INT          NOT NULL AUTO_INCREMENT,
  name                VARCHAR(128) NOT NULL,
  date                DATETIME     NOT NULL,
  trailerLink         VARCHAR(256),
  workerUUID          INT          DEFAULT NULL,
  emergencyWorkerUUID INT          DEFAULT NULL,

  bookedCards TINYINT NOT NULL check(number >= 0 and number <= 20),

  PRIMARY KEY(UMID),
  FOREIGN KEY (workerUUID) REFERENCES users(UUID) ON DELETE CASCADE,
  FOREIGN KEY (emergencyWorkerUUID) REFERENCES users(UUID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings(
  UBID BIGINT NOT NULL AUTO_INCREMENT,
  UUID INT    NOT NULL,
  UMID INT    NOT NULL,

  count TINYINT NOT NULL check(number >= 0 and number <= 20),

  PRIMARY KEY(UBID),
  FOREIGN KEY (UUID) REFERENCES users(UUID) ON DELETE CASCADE,
  FOREIGN KEY (UMID) REFERENCES movies(UMID) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO movies(name, date, trailerLink) VALUE ("Titanic", "2017-09-21 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Titanic 2", "2017-09-22 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Matrix", "2017-09-23 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Matrix Reloaded", "2017-09-24 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Matrix 3", "2017-09-28 00:00:00", "https://google.com");