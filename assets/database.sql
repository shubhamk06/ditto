CREATE TABLE blobs
(
  id   CHAR(36) PRIMARY KEY NOT NULL,
  user CHAR(36)             NOT NULL,
  date INT(20)              NOT NULL,
  hash VARCHAR(128)         NOT NULL
);
CREATE TABLE dataPoints
(
  id   CHAR(36) PRIMARY KEY NOT NULL,
  user CHAR(36)             NOT NULL,
  type TINYINT(1)           NOT NULL,
  date INT(20)              NOT NULL,
  data TEXT                 NOT NULL
);
CREATE TABLE users
(
  id             CHAR(36) PRIMARY KEY NOT NULL,
  email          VARCHAR(256)         NOT NULL,
  salt           VARCHAR(128)         NOT NULL,
  password       VARCHAR(128)         NOT NULL,
  dateRegistered INT(20)              NOT NULL,
  admin          TINYINT(1)           NOT NULL
);
ALTER TABLE blobs ADD FOREIGN KEY (user) REFERENCES users (id);
CREATE INDEX users_blobs ON blobs (user);
ALTER TABLE dataPoints ADD FOREIGN KEY (user) REFERENCES users (id);
CREATE INDEX users_dataPoints ON dataPoints (user);