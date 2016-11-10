CREATE database IF NOT EXISTS dinya;
GRANT ALL PRIVILEGES ON dinya.* TO root@localhost;


DROP TABLE IF EXISTS users;

CREATE TABLE users(
  userid int AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(100),
  email VARCHAR(100)
);

INSERT INTO users (username, email) VALUES (sha1('admin'), 'akarmi@live.com');
