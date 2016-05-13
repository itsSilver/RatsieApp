DROP TABLE Bets;
DROP TABLE Races;
DROP TABLE Tickets;
DROP TABLE Users;
/
CREATE TABLE Users
      (userId NUMBER(7) PRIMARY KEY,
       username VARCHAR2(30) UNIQUE,
       password VARCHAR2(30) NOT NULL,
       balance NUMBER(7) DEFAULT 0 , 
       email VARCHAR2(30) NOT NULL,
       zipCode VARCHAR2(7) DEFAULT '000000', 
       address VARCHAR2(30) ,
       phoneNr VARCHAR2(12) ,
       role VARCHAR2(10) NOT NULL  );
/
CREATE OR REPLACE TRIGGER userId_AI
BEFORE INSERT ON users
FOR EACH ROW
DECLARE
  currId INTEGER;
BEGIN
  SELECT MAX(userId)+1
  INTO currId
  FROM users;
  
  IF currId IS NULL THEN
    :new.userId := 1;
  ELSE
    SELECT MAX(userId)+1
    INTO :new.userId
    FROM users;
  END IF;
END;
/
CREATE TABLE Tickets
      (ticketId NUMBER(7) PRIMARY KEY,
       userId NUMBER(7) ,
       stake NUMBER(7,2) NOT NULL,
       isPaid NUMBER(1) DEFAULT 0,
       CONSTRAINT fk_userId
         FOREIGN KEY (userId)
         REFERENCES Users(userId));
/
CREATE OR REPLACE TRIGGER ticketId_AI
BEFORE INSERT ON Tickets
FOR EACH ROW
DECLARE
  currId INTEGER;
BEGIN
  SELECT MAX(ticketId)+1
  INTO currId
  FROM Tickets;
  
  IF currId IS NULL THEN
    :new.ticketId := 1;
  ELSE
    SELECT MAX(ticketId)+1
    INTO :new.ticketId
    FROM Tickets;
  END IF;
END;
/
CREATE TABLE Races
      (raceId NUMBER(7) PRIMARY KEY,
       finishTime DATE,
       finished NUMBER(1) DEFAULT 0,
       pos1 NUMBER(1) DEFAULT 0,
       pos2 NUMBER(1) DEFAULT 0,
       pos3 NUMBER(1) DEFAULT 0,
       pos4 NUMBER(1) DEFAULT 0,
       pos5 NUMBER(1) DEFAULT 0,
       pos6 NUMBER(1) DEFAULT 0,
       rat1Odd NUMBER(4,2),
       rat2Odd NUMBER(4,2),
       rat3Odd NUMBER(4,2),
       rat4Odd NUMBER(4,2),
       rat5Odd NUMBER(4,2),
       rat6Odd NUMBER(4,2));
/
CREATE OR REPLACE TRIGGER raceId_AI
BEFORE INSERT ON Races
FOR EACH ROW
DECLARE
  currId INTEGER;
BEGIN
  SELECT MAX(raceId)+1
  INTO currId
  FROM Races;
  
  IF currId IS NULL THEN
    :new.raceId := 1;
  ELSE
    SELECT MAX(raceId)+1
    INTO :new.raceId
    FROM Races;
  END IF;
END;
/
CREATE TABLE Bets
      (betId NUMBER(7) PRIMARY KEY,
       ticketId NUMBER(7),
       raceId NUMBER(7),
       choice NUMBER(1),
       CONSTRAINT fk_ticketId
         FOREIGN KEY (ticketId)
         REFERENCES Tickets(ticketId),
         CONSTRAINT fk_raceId
         FOREIGN KEY (raceId)
         REFERENCES Races(raceId));
/
CREATE OR REPLACE TRIGGER betId_AI
BEFORE INSERT ON Bets
FOR EACH ROW
DECLARE
  currId INTEGER;
BEGIN
  SELECT MAX(betId)+1
  INTO currId
  FROM Bets;
  
  IF currId IS NULL THEN
    :new.betId := 1;
  ELSE
    SELECT MAX(betId)+1
    INTO :new.betId
    FROM Bets;
  END IF;
END;
/