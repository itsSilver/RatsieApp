CREATE OR REPLACE PROCEDURE payBet
( p_userId IN NUMBER,
  p_stake IN NUMBER)
AS
BEGIN
  UPDATE Users
  SET balance = balance - p_stake
  WHERE userId = p_userId;
 END payBet;
 /