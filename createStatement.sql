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

  bookedCards         TINYINT DEFAULT 0,

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

INSERT INTO movies(name, date, trailerLink) VALUE ("Titanic", "2018-02-22 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Titanic 2", "2018-02-23 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Matrix", "2018-02-24 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Matrix Reloaded", "2018-02-25 00:00:00", "https://google.com");

INSERT INTO movies(name, date, trailerLink) VALUE ("Kevin allein zuhause", "2018-03-01 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Kevin allein zuhause 2", "2018-03-02 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Kevin allein zuhause 3", "2018-03-03 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Zurück in die Zukunft 1", "2018-03-04 00:00:00", "https://google.com");

INSERT INTO movies(name, date, trailerLink) VALUE ("Zurück in die Zukunft 2", "2018-03-11 00:00:00", "https://google.com");
INSERT INTO movies(name, date, trailerLink) VALUE ("Zurück in die Zukunft 3", "2018-03-12 00:00:00", "https://google.com");